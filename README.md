# Livecore

Livecore is a simple WordPress plugin that fetches and displays live football scores from [livescores.com](https://www.livescores.com/) using AJAX. It scrapes the live scores and leagues data and displays them dynamically on your WordPress site.

---

## Features

- Fetches live football scores and leagues from livescores.com
- AJAX-powered for smooth real-time updates without page reload
- Displays matches with time, teams, scores, and status (Inplay, Incoming, Completed)
- Clickable league and date filters to view specific matches
- Easy shortcode integration

---

## Installation

1. Upload the `livescore` folder to your WordPress plugin directory (`/wp-content/plugins/`).
2. Activate the plugin through the WordPress admin dashboard.
3. Use the shortcode `[wp_livescore]` to display the live scores container anywhere on your site.

---

## Usage

Place the shortcode `[wp_livescore]` inside any post, page, or widget where you want the live scores to appear.

The plugin automatically fetches data via AJAX and updates the scores dynamically.

---

## Development

- The main plugin file is `livescore.php`.
- AJAX actions handle fetching and updating score data.
- Includes JavaScript file `auto-refresh.js` to manage AJAX requests.
- Style the plugin output with `style.css`.

---

## Changelog

### 1.1
- Added AJAX-based live score fetching
- Added clickable leagues and date filters
- Improved error handling and retry logic

---

