<?php 

function proc_csv($filename, $delimiter, $quote, $columns_to_show){
    $mode = "r";
    $handle = fopen($filename, $mode) or die ("Cannot open $filename");
    $cols = []; //denotes which columns are to be output
    $data = fgets($handle);
    $data = trim($data); //remove whitespace
    $data = my_fgetcsv2("$data", $delimiter, $quote); //custom implementation of fgetcsv
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
        $data_cols = my_fgetcsv2($data,$delimiter,$quote);
        foreach ($cols as $index) {
            echo "  <td> ".$data_cols[$index]." </td>\n";
        }
        echo "</tr>\n";
    }
}

function my_fgetcsv($line, $delimiter = ",", $enclosure = '"'){
    $fields = [];
    $field = '';
    $in_quotes = false;
    $len = strlen($line);

    for ($i = 0; $i < $len; $i++) {
        $char = $line[$i];
        if ($char === $enclosure) {
            $in_quotes = !$in_quotes; // Toggle in_quotes if regular quote
        } elseif ($char === $delimiter && !$in_quotes) {
            $fields[] = $field;
            $field = '';
        } else {
            $field .= $char;
        }
    }

    //add in the last field 
    $fields[] = $field;

    return $fields;
}

function my_fgetcsv2($line, $delimiter = ",", $enclosure = '"'){
    $pattern = '/^\"[^\"]+"/';
    /*  this pattern does the following: 
            - only look at the start of the string
            - match "
            - then match 1 or more characters that is NOT " 
            - match "

            effectively, the idea here is to check whether the next column of the line being processed is a string
    */
    $fields = [];
    $matches = []; //should at most be 1 element given our specific regex
    echo "<br/>";
    while(strlen($line) != 0){
        preg_match($pattern, $line, $matches);
        if(count($matches) == 0){
            //NOT a string -> delimiter is safe 
            //look to delimiter or to newline
            $pos = strpos($line, $delimiter);
            if(!$pos){
                $fields[] = $line; 
                break; //end of line, can also do $line = '';  
            }else{
                $fields[] = substr($line, 0, $pos); //includes until the comma
                $line = substr($line, $pos+1); //skips until after comma 
            }
        }else if(count($matches) == 1){
            //string!! 
            $len = strlen($matches[0]);
            $fields[] = $matches[0]; //includes the entire string
            $line = substr($line, $len+1); //skips the string and comma 
        }else{
            die("Error: regex matched twice, not supposed to!");
        }
    }
    return $fields; 
}

?>