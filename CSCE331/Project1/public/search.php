<?php

function sanitize($string) {
    $string = preg_replace("/[^a-zA-Z0-9 ]+/", "", $string);
    $string = preg_replace('/\s+/', ' ', trim($string));
    return $string;
}

function fetch_html($url) {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 5,
            // 'ignore_errors' => true
        ]
    ]);
    
    $html = file_get_contents($url, false, $context);
    return $html !== false ? $html : "";
}

/**
 * Search all web pages in the local web directory for a keyword/phrase
 * Searches the final generated HTML, not the PHP source file 
 * 
 * @param string $string The search keyword or phrase
 * @return array List of URLs containing the search term
 */
function search($string) {
    // Prevent recursive searching - if this page was fetched internally, don't search
    if (isset($_GET['_internal_fetch'])) {
        return [];
    }
    
    // only alphanumeric and spaces allowed
    $query = sanitize($string);
    
    if ($query === "") {
        return [];
    }

    $results = [];
    
    // Docker internal network - 'webserver' is the nginx service name
    $internalBaseUrl = "http://webserver";
    
    // External URL for browser access (mapped port from docker-compose)
    $externalBaseUrl = "http://localhost:5555";

    // Get all PHP files in the public directory
    $pages = glob(__DIR__ . "/*.php");

    foreach ($pages as $page) {
        $name = basename($page);
        
        // Skip the search page itself to avoid recursion
        if ($name === "search.php") {
            continue;
        }

        // Add _internal_fetch parameter to prevent any recursive search calls
        $url = $internalBaseUrl . "/" . $name . "?_internal_fetch=1";
        $html = fetch_html($url);

        // Case-insensitive search for the query in the rendered HTML
        if ($html !== "" && stripos($html, $query) !== false) {
            // Add the external URL for browser access
            $results[] = $externalBaseUrl . "/" . $name;
        }
    }

    return $results;
}

function display_results($results, $query) {
    if (empty($results)) {
        echo "<p>No results found for: <strong>" . htmlspecialchars($query) . "</strong></p>";
        return;
    }
    
    echo "<p>Found " . count($results) . " result(s) for: <strong>" . htmlspecialchars($query) . "</strong></p>";
    echo "<ol>";
    foreach ($results as $url) {
        $displayName = basename($url);
        echo "<li><a href=\"" . htmlspecialchars($url) . "\">" . htmlspecialchars($url) . "</a></li>";
    }
    echo "</ol>";
}
?>
