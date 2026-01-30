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
    while(count($list_stack) > $depth) {
        $type = array_pop($list_stack);
        echo "</$type>";
    }
    // Open new lists if going down
    while(count($list_stack) < $depth) {
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
    echo "<li>" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "</li>";
}

// Handle heading
function handle_heading(&$list_stack, $line, $matches) {
    close_all_lists($list_stack);
    $hash_count = strlen($matches[0]);
    $content = trim(ltrim($line, '#'));
    echo "<h$hash_count>" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "</h$hash_count>";
}

// Handle paragraph
function handle_paragraph(&$list_stack, $line) {
    close_all_lists($list_stack);
    echo "<p>" . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . "</p>";
}

// Main markdown processor
function proc_markdown($filename) {
    $lines = file($filename);
    $list_stack = [];
    
    foreach($lines as $line) {
        $original_line = $line;
        $line = trim($line);
        $depth = get_depth($original_line);
        
        // Unordered list
        if(strpos($line, '*') === 0 || strpos($line, '-') === 0) {
            $content = trim(ltrim($line, '*- '));
            handle_list($list_stack, $depth, 'ul', $content);
        }
        // Ordered list
        elseif(preg_match('/^\d+\./', $line)) {
            $content = trim(preg_replace('/^\d+\./', '', $line));
            handle_list($list_stack, $depth, 'ol', $content);
        }
        // Heading
        elseif(preg_match('/^#+/', $line, $matches)) {
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