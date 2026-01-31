<?php
/*
stages
1. normalize input (trimming, line endings)
2. block-level elements like headings, list, paragraphs
3. inline elements like bold, italic, underline, links, images

split input into lines of $text
    for each line, 
        trim
        check block-level first (at the front)
            first check lists 
            then check # 
                - note: if # then list, no work, but if list then #, then it works
        handle lists with state
            start a list when u see * or 1. and close it when u hit a blank line or a non-list line
            # this avoids unclosed <ul> or <ol> tags
        now handle inline formatting
            just replace underline, bold, italics with regex
        FINALLY,
            "escape" user input by replacing dangerous chars 
                < -> &lt; 
                > -> &gt; 
                " -> &quot;
                ' -> &#039;
                & -> &amp;
            $safeText = htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
*/

// Get indent depth from original (untrimmed) line
function get_depth($original_line) {
    $indent = strlen($original_line) - strlen(ltrim($original_line, " \t"));
    return intval($indent / 2);
}

// Process inline formatting (bold, italic, underline, links, images)
function format_inline($text) {
    // First escape HTML to prevent XSS
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    // Bold: **text** or __text__
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);
    
    // Italic: *text* or _text_ (but not inside ** or __)
    $text = preg_replace('/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/', '<em>$1</em>', $text);
    $text = preg_replace('/(?<!_)_(?!_)(.+?)(?<!_)_(?!_)/', '<em>$1</em>', $text);
    
    // Underline: <ins>text</ins> (already escaped, so match &lt;ins&gt;)
    $text = preg_replace('/&lt;ins&gt;(.+?)&lt;\/ins&gt;/', '<ins>$1</ins>', $text);
    
    // Links: [text](url)
    $text = preg_replace('/\[(.+?)\]\((.+?)\)/', '<a href="$2">$1</a>', $text);
    
    // Images: ![alt](url)
    $text = preg_replace('/!\[(.+?)\]\((.+?)\)/', '<img src="$2" alt="$1">', $text);
    
    return $text;
}

// Close all open lists in stack
function close_all_lists(&$list_stack) {
    while(count($list_stack) > 0) {
        $type = array_pop($list_stack);
        echo "</$type>";
    }
}

// Handle list item (both ordered and unordered)
function handle_list(&$list_stack, $depth, $list_type, $content) {
    // Close deeper lists if going up
    while(count($list_stack) > $depth+1) {
        $type = array_pop($list_stack);
        echo "</$type>";
    }
    // Open new lists if going down
    while(count($list_stack) < $depth+1) {
        array_push($list_stack, $list_type);
        echo "<$list_type>";
    }
    // Start or switch list at this level
    if(count($list_stack) == 0) {
        array_push($list_stack, $list_type);
        echo "<$list_type>";
    } elseif(end($list_stack) !== $list_type) {
        $old = array_pop($list_stack);
        echo "</$old>";
        array_push($list_stack, $list_type);
        echo "<$list_type>";
    }
    echo "<li>" . format_inline($content) . "</li>";
}

// Handle heading
function handle_heading(&$list_stack, $line, $matches) {
    close_all_lists($list_stack);
    $hash_count = strlen($matches[0]);
    $content = trim(ltrim($line, '#'));
    echo "<h$hash_count>" . format_inline($content) . "</h$hash_count>";
}

// Handle paragraph
function handle_paragraph(&$list_stack, $line) {
    close_all_lists($list_stack);
    echo "<p>" . format_inline($line) . "</p>";
}

// Get line type for debug table
function get_line_type($line) {
    if(strpos($line, '* ') === 0 || strpos($line, '- ') === 0) return 'ul';
    if(preg_match('/^\d+\. /', $line)) return 'ol';
    if(preg_match('/^#+ /', $line)) return 'heading';
    if(empty($line)) return 'blank';
    return 'paragraph';
}

// Print debug table for a file
function print_debug_table($filename) {
    $lines = file($filename);
    echo "<h3>Debug: " . htmlspecialchars(basename($filename)) . "</h3>";
    echo "<table border='1' cellpadding='5' style='margin-bottom:20px;'>";
    echo "<tr><th>#</th><th>Line</th><th>Depth</th><th>Type</th><th>Stack</th></tr>";
    
    $list_stack = [];
    $i = 1;
    
    foreach($lines as $line) {
        $original_line = $line;
        $line = trim($line);
        $depth = get_depth($original_line);
        $type = get_line_type($line);
        
        // Simulate stack changes
        if($type === 'ul' || $type === 'ol') {
            while(count($list_stack) > $depth+1) array_pop($list_stack);
            while(count($list_stack) < $depth+1) array_push($list_stack, $type);
            if(count($list_stack) == 0 || end($list_stack) !== $type) {
                if(count($list_stack) > 0 && end($list_stack) !== $type) array_pop($list_stack);
                array_push($list_stack, $type);
            }
        } elseif($type === 'blank' || $type === 'heading' || $type === 'paragraph') {
            $list_stack = [];
        }
        
        $stack_str = empty($list_stack) ? '[]' : '[' . implode(', ', $list_stack) . ']';
        $display_line = htmlspecialchars($line);
        if(empty($display_line)) $display_line = '<em>(empty)</em>';
        
        echo "<tr>";
        echo "<td>$i</td>";
        echo "<td><code>$display_line</code></td>";
        echo "<td>$depth</td>";
        echo "<td>$type</td>";
        echo "<td>$stack_str</td>";
        echo "</tr>";
        $i++;
    }
    echo "</table>";
}

// Main markdown processor
function proc_markdown($filename, $debug = true) {
    if($debug) {
        print_debug_table($filename);
    }
    
    $lines = file($filename);
    $list_stack = [];
    
    foreach($lines as $line) {
        $original_line = $line;
        $line = trim($line);
        $depth = get_depth($original_line);
        
        // Unordered list
        if(strpos($line, '* ') === 0 || strpos($line, '- ') === 0) {
            $content = trim(ltrim($line, '*- '));
            handle_list($list_stack, $depth, 'ul', $content);
        }
        // Ordered list
        elseif(preg_match('/^\d+\. /', $line)) {
            $content = trim(preg_replace('/^\d+\. /', '', $line));
            handle_list($list_stack, $depth, 'ol', $content);
        }
        // Heading (# followed by space)
        elseif(preg_match('/^(#+) /', $line, $matches)) {
            handle_heading($list_stack, $line, $matches);
        }
        // Blank line
        elseif(empty($line)) {
            close_all_lists($list_stack);
        }
        // Paragraph
        else {
            handle_paragraph($list_stack, $line);
        }
    }
    close_all_lists($list_stack);
}
?>