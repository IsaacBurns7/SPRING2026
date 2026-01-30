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

// Helper function: check if line is unordered list
function is_unordered_list($line) {
    return strpos($line, '*') === 0 || strpos($line, '-') === 0;
}

// Helper function: check if line is ordered list
function is_ordered_list($line) {
    return preg_match('/^\d+\./', $line) === 1;
}

// Helper function: check if line is heading, extract hash count
function is_heading($line, &$hash_count) {
    if(preg_match('/^#+/', $line, $matches)) {
        $hash_count = strlen($matches[0]);
        return true;
    }
    return false;
}

// Helper function: get indent depth from original line
function get_indent_depth($original_line) {
    $indent = strlen($original_line) - strlen(ltrim($original_line, " \t"));
    return intval($indent / 2);
}

// Helper function: close lists down to a certain depth
function close_lists_to_depth(&$list_stack, $depth) {
    while(count($list_stack) > $depth) {
        $type = array_pop($list_stack);
        echo "</$type>";
    }
}

// Helper function: open lists up to a certain depth
function open_lists_to_depth(&$list_stack, $depth, $type) {
    while(count($list_stack) < $depth) {
        array_push($list_stack, $type);
        echo "<$type>";
    }
}

// Helper function: start or switch list type at current level
function start_or_switch_list(&$list_stack, $type) {
    if(count($list_stack) == 0) {
        array_push($list_stack, $type);
        echo "<$type>";
    } elseif(end($list_stack) !== $type) {
        $old_type = array_pop($list_stack);
        echo "</$old_type>";
        array_push($list_stack, $type);
        echo "<$type>";
    }
}

// Helper function: close all open lists
function close_all_lists(&$list_stack) {
    while(count($list_stack) > 0) {
        $type = array_pop($list_stack);
        echo "</$type>";
    }
}

// Process unordered list item
function process_unordered_list($line, &$list_stack, $depth) {
    close_lists_to_depth($list_stack, $depth);
    open_lists_to_depth($list_stack, $depth, 'ul');
    start_or_switch_list($list_stack, 'ul');
    $content = trim(ltrim($line, '*-'));
    echo "<li>" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "</li>";
}

// Process ordered list item
function process_ordered_list($line, &$list_stack, $depth) {
    close_lists_to_depth($list_stack, $depth);
    open_lists_to_depth($list_stack, $depth, 'ol');
    start_or_switch_list($list_stack, 'ol');
    $content = trim(preg_replace('/^\d+\./', '', $line));
    echo "<li>" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "</li>";
}

// Process heading
function process_heading($line, &$list_stack, $hash_count) {
    close_all_lists($list_stack);
    $content = trim(ltrim($line, '#'));
    echo "<h$hash_count>" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "</h$hash_count>";
}

// Process paragraph
function process_paragraph($line, &$list_stack) {
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
        $depth = get_indent_depth($original_line);
        
        if(is_unordered_list($line)) {
            process_unordered_list($line, $list_stack, $depth);
        } 
        elseif(is_ordered_list($line)) {
            process_ordered_list($line, $list_stack, $depth);
        }
        elseif(is_heading($line, $hash_count)) {
            process_heading($line, $list_stack, $hash_count);
        }
        elseif(empty($line)) {
            close_all_lists($list_stack);
        }
        else {
            process_paragraph($line, $list_stack);
        }
    }
    
    // Close any remaining open lists
    close_all_lists($list_stack);
}
?>