<?php 


/**
 * Processes CSV file and displays selected columns as HTML table
 * 
 * @param string $filename - Path to CSV file to process
 * @param string $delimiter - Field separator character (',' or '\t')
 * @param string $quote - Quote character for enclosing fields ('"' or "'")
 * @param string $columns_to_show - "ALL" or colon-separated indices ("1:3:5")
 * @return void - Outputs HTML table directly to page
 */
function proc_csv($filename, $delimiter, $quote, $columns_to_show){
    $mode = "r";
    $handle = fopen($filename, $mode) or die ("Cannot open $filename");
    $cols = []; //denotes which columns are to be output
    $data = fgets($handle);
    $data = trim($data); //remove whitespace
    $data = my_fgetcsv($data, $delimiter, $quote); //custom implementation of fgetcsv
    $pattern = '/(\d+:)*\d/';
    /*
        The above regex does the following: 
        denotes a group that is <number> followed by ':' character
        allows that to happen 0 or more times
        ends with a <number> character
        effectively, this matches the '1:3:5:7'and similiar patterns 
        please note that '1' is then an appropriate match
    */
    if($columns_to_show == "ALL"){
        //all columns are valid
        for($k = 0;$k < count($data); $k++){
            $cols[$k] = $k; 
        }
    }elseif(preg_match($pattern, $columns_to_show)) { 
        //only some columns are valid
        $cols = preg_split("/:/", $columns_to_show); //if no :, entire array is one element, the $columns_to_show string
    }else{
        //confused as to what user did 
        die ("$columns_to_show did not match pattern \'ALL\' or $reg");
    }
    //output first line
    echo "<table  border=\"1\">\n";
    echo "<tr>\n";
    foreach($cols as $index){
        echo "  <td> ".$data[$index]." </td>\n";
    }
    echo "</tr>\n";
    //walk through and continue outputting
   while ($data = fgets($handle)) {
        echo "<tr>\n";
        $data_cols = my_fgetcsv($data,$delimiter,$quote);
        foreach ($cols as $index) {
            echo "  <td> ".$data_cols[$index]." </td>\n";
        }
        echo "</tr>\n";
    }
    echo "<br></br>";
}

/**
 * Custom CSV parser using regex-based quoted string detection
 * 
 * Parses a single line of CSV data into an array of fields.  Uses regex pattern 
 * matching to detect quoted strings allowing delimiters to be safely ignored when inside quoted fields.
 * 
 * @param string $line - Single line of CSV data to parse
 * @param string $delimiter - Field separator character (default: ",")
 * @param string $enclosure - Quote character for enclosing fields (default: '"')
 * 
 * @return array - Array of field values extracted from the line, preserving
 *                 quote characters around string fields
 * 
 * @example my_fgetcsv2('John,"Doe, Jr.",25', ',', '"') 
 *          returns:  ['John', '"Doe, Jr."', '25']
 */

function my_fgetcsv($line, $delimiter = ",", $enclosure = '"'){
    /*This is the pattern with 
        hardcoded enclosure of double quotes and 
        hardcoded delimiter of comma
    $pattern = '/,(?=(?:[^"]*"[^"]*")*[^"]*$)/';
    The pattern does the following: accept all commas that follow
        - ?= -> lookahead
        - (?:[^"]*"[^"]*")* -> pairs of quotes
        - [^"]*$ -> match anything else, then end of line
    effectively, this looks forward and finds if there's an even number of quotes ahead 
        - if there isnt, it wont match b/c uneven quotes (implies ->) you're in a string, therefore do NOT act 
        that , is a delimiter
        - if there is, it knows its not a string.
    */
    $pattern = '/' . preg_quote($delimiter) . '(?=(?:[^' 
        . preg_quote($enclosure, '/') . ']*'
        . preg_quote($enclosure, '/') . '[^'
        . preg_quote($enclosure, '/') . ']*'
        . preg_quote($enclosure, '/') . ')*[^'
        . preg_quote($enclosure, '/') . ']*$)/';
    $ret = preg_split($pattern, $line, 0);
    return $ret;
}