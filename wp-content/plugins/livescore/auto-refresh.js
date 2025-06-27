document.addEventListener('DOMContentLoaded', function () {
    function loadScores(url = null) {
        const data = url ? { url } : {};
        const formData = new FormData();
        if (url) formData.append('url', url);

        formData.append('action', 'get_flashscore_html');

        const scoreLive = document.getElementById('score-live');
        scoreLive.innerHTML = '<div id="loading-indicator">Loading scores...</div>';

        fetch(flashscore_ajax_obj.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(html => {
            scoreLive.innerHTML = html;
        })
        .catch(err => {
            scoreLive.innerHTML = '<p>Failed to load scores.</p>';
            console.error(err);
        });
    }

    // Initial load
    loadScores();

    // Event delegation for dynamic league links
    document.addEventListener('click', function (e) {
        if (e.target.closest('.league-link')) {
            e.preventDefault();
            const url = e.target.closest('.league-link').getAttribute('data-url');
            if (url) {
                loadScores(url);
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.date-link')) {
            e.preventDefault();
    
            const dateLink = e.target.closest('.date-link');
            const url = dateLink.getAttribute('data-url');
    
            // Optional: loading spinner
            const scoreLive = document.getElementById('score-live');
            if (scoreLive) {
                scoreLive.innerHTML = '<div id="loading-indicator">Loading scores...</div>';
            }
    
            // Fetch content
            fetch(flashscore_ajax_obj.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'get_flashscore_html',
                    url: url
                })
            })
                .then(res => res.text())
                .then(html => {
                    if (scoreLive) {
                        scoreLive.innerHTML = html;
                    }
                })
                .catch(err => {
                    if (scoreLive) {
                        scoreLive.innerHTML = '<p>Failed to load date data.</p>';
                    }
                    console.error('Fetch error:', err);
                });
        }
    });
    
});
