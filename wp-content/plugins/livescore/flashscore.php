<?php
/**
 * Plugin Name: Livecore
 * Description: A simple plugin that returns specific HTML from flashscore.com via shortcode.
 * Version: 1.1
 * Author: Seth Borey
 */

function show_flashscore_container() {
    return '<div id="score-live">
        <div id="loading-indicator">Loading scores...</div>
    </div>';
}

function show_flashscore_menu_html($url = null) {
    if (!$url) {
        $url = 'https://www.livescores.com/football/live/?tz=7';
    }

    $response = fetch_with_retry($url);

    if (!$response) {
        return '<p>Failed to fetch content after retry.</p>';
    }

    $html = wp_remote_retrieve_body($response);
    if (empty($html)) {
        return '<p>No content retrieved.</p>';
    }

    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    $xpath = new DOMXPath($doc);

    $date = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' vd ')]//*[contains(concat(' ', normalize-space(@class), ' '), ' Gc ')]");
    $date_output = '';
    foreach ($date as $dateItem) {
        $tempDoc = new DOMDocument();
        $tempDoc->appendChild($tempDoc->importNode($dateItem, true));

        $aTags = $tempDoc->getElementsByTagName('a');
        foreach ($aTags as $aTag) {
            if ($aTag->hasAttribute('href')) {
                $href = $aTag->getAttribute('href');
                if (strpos($href, 'http') !== 0) {
                    $href = 'https://www.livescores.com' . $href;
                }
                $aTag->setAttribute('href', '#');
                $aTag->setAttribute('data-url', $href);
                $aTag->setAttribute('class', 'date-link'); // separate class for JS targeting
            }
        }

        $date_output .= '<div class="date-block">';
        $date_output .= $tempDoc->saveHTML($tempDoc->documentElement);
        $date_output .= '</div>';
    }

    // Fetch leagues (.ec)
    $leagues_output = '';
    $leagues = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' ec ')]");
    foreach ($leagues as $league) {
        $tempDoc = new DOMDocument();
        $tempDoc->appendChild($tempDoc->importNode($league, true));

        $aTags = $tempDoc->getElementsByTagName('a');
        foreach ($aTags as $aTag) {
            if ($aTag->hasAttribute('href')) {
                $href = $aTag->getAttribute('href');
                if (strpos($href, 'http') !== 0) {
                    $href = 'https://www.livescores.com' . $href;
                }
                $aTag->setAttribute('href', '#');
                $aTag->setAttribute('data-url', $href);
                $aTag->setAttribute('class', 'league-link');
            }
        }

        $leagues_output .= '<div class="league-block league-item">';
        $leagues_output .= $tempDoc->saveHTML($tempDoc->documentElement);
        $leagues_output .= '</div>';
    }

    // Fetch games (.Le)
    $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' Le ')]");
    $games_output = '<table class="score-table">';
    $games_output .= '<thead><tr>';
    $games_output .= '<th>Time</th><th>Home Team</th><th>Score</th><th>Away Team</th><th>Status</th>';
    $games_output .= '</tr></thead><tbody>';

    foreach ($nodes as $node) {
        $timeNode = $xpath->query(".//span[contains(concat(' ', normalize-space(@class), ' '), ' og ')]", $node);
        $time = $timeNode->length ? trim($timeNode->item(0)->textContent) : 'N/A';

        if (preg_match("/^\d{1,3}'(\+\d{1,2})?'?$|^HT$/", $time)) {
            $status = 'Inplay';
        } elseif (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
            $status = 'Incoming';
        } else {
            $status = 'Completed';
        }
        

        $homeNode = $xpath->query(".//span[contains(concat(' ', normalize-space(@class), ' '), ' Xh ')]/span[contains(@class, 'Zh')]", $node);
        $homeTeam = $homeNode->length ? trim($homeNode->item(0)->textContent) : 'N/A';

        $awayNode = $xpath->query(".//span[contains(concat(' ', normalize-space(@class), ' '), ' Yh ')]/span[contains(@class, 'Zh')]", $node);
        $awayTeam = $awayNode->length ? trim($awayNode->item(0)->textContent) : 'N/A';

        $homeScoreNode = $xpath->query(".//span[contains(concat(' ', normalize-space(@class), ' '), ' ci ')]", $node);
        $homeScore = $homeScoreNode->length ? trim($homeScoreNode->item(0)->textContent) : '-';

        $awayScoreNode = $xpath->query(".//span[contains(concat(' ', normalize-space(@class), ' '), ' di ')]", $node);
        $awayScore = $awayScoreNode->length ? trim($awayScoreNode->item(0)->textContent) : '-';

        $games_output .= '<tr>';
        $games_output .= '<td class="team">' . htmlspecialchars($time) . '</td>';
        $games_output .= '<td class="team">' . htmlspecialchars($homeTeam) . '</td>';
        $games_output .= '<td class="score team">' . htmlspecialchars($homeScore) . ' - ' . htmlspecialchars($awayScore) . '</td>';
        $games_output .= '<td class="team">' . htmlspecialchars($awayTeam) . '</td>';
        $games_output .= '<td class="status">' . $status . '</td>';
        $games_output .= '</tr>';
    }

    $games_output .= '</tbody></table>';

    return '<div id="date-container">' . $date_output . '</div>' .
            '<div id="games-container">' . $games_output . '</div>'.
            '<div id="leagues-container">' . $leagues_output . '</div>' ;
}

function fetch_with_retry($url, $retries = 1, $delay = 1) {
    $response = wp_remote_get($url);
    while (is_wp_error($response) && $retries-- > 0) {
        sleep($delay);
        $response = wp_remote_get($url);
    }
    return is_wp_error($response) ? false : $response;
}

add_shortcode('wp_livescore', 'show_flashscore_container');

add_action('wp_ajax_get_flashscore_html', 'show_flashscore_menu_html_ajax');
add_action('wp_ajax_nopriv_get_flashscore_html', 'show_flashscore_menu_html_ajax');

function show_flashscore_menu_html_ajax() {
    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : null;
    echo show_flashscore_menu_html($url);
    wp_die();
}

add_action('wp_enqueue_scripts', 'flashscore_enqueue_assets');

function flashscore_enqueue_assets() {
    wp_enqueue_style(
        'flashscore-style',
        plugin_dir_url(__FILE__) . 'style.css'
    );

    wp_enqueue_script(
        'flashscore-auto-refresh',
        plugin_dir_url(__FILE__) . 'auto-refresh.js',
        [],
        false,
        true
    );

    wp_localize_script('flashscore-auto-refresh', 'flashscore_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}
