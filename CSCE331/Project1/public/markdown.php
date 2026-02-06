<?php
    require_once("proc_markdown.php");
    $test_files = [
        'edge-case-inline.md',
        'edge-case-headings.md',
        'edge-case-lists.md',
        'edge-case-paragraphs.md',
        'edge-case-combined.md',
        'edge-case-basic.md'
    ];

    echo "<h2>Markdown Tests</h2>";
    proc_markdown("../data/markdown.md");
    echo "<hr>";
    foreach($test_files as $file) {
        echo "<h3>Testing: $file</h3>";
        proc_markdown("../data/markdown/$file");
        echo "<hr>";
    }
?>