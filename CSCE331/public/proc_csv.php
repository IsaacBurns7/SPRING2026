<?php 

function proc_csv($filename, $delimiter, $quote, $columns_to_show){
    $mode = "r";
    $handle = fopen($filename, $mode) or die ("Cannot open $filename");
    $cols = [];
    $data = fgets($handle);
    // echo "<br/>RAW DATA: "; 
    // var_dump($data);
    $data = trim($data);
    // echo "<br/>non-raw data: "; 
    // var_dump($data);

    /*
    3 cases: you're in quotes and you hit a delimiter, you're not in quotes and you hit a delimiter
    you're 
    */
    $data = my_fgetcsv2("$data", $delimiter, $quote);
    // echo "<br/>processed array: ";
    // var_dump($data);

    // $data = preg_split("/$delimiter/", $data);
    // echo "<br/>DATA AFTER SPLIT BY DELIMITER: $delimiter ";
    // var_dump($data);
    //ignore delimiters in strings 

    $pattern = '/(\d+:)*\d/';
    if($columns_to_show == "ALL"){
        for($k = 0;$k < count($data); $k++){
            $cols[$k] = $k; 
        }
    }elseif(preg_match($pattern, $columns_to_show)) { 
        $cols = preg_split("/:/", $columns_to_show);
        // echo "<br/>COLUMNS RAW: $columns_to_show";    
        // echo "<br/>COLUMNS: $cols";
    }else{
        die ("$columns_to_show did not match pattern \'ALL\' or $reg");
    }
    // echo "<br/>";
    // var_dump($cols);

    echo "<table  border=\"1\">\n";
    echo "<tr>\n";
    foreach($cols as $index){
        echo "  <td> ".$data[$index]." </td>\n";
    }
    echo "</tr>\n";
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
    // echo "<br/>";
    $fields = [];
    $field = '';
    $in_quotes = false;
    $len = strlen($line);

    for ($i = 0; $i < $len; $i++) {
        $char = $line[$i];
        // echo "inquotes at $i: ";
        // var_dump($in_quotes);
        // echo "given ";
        // var_dump($char);
        // echo "<br/>";

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
                $fields[] = substr($line, 0, $pos);
                $line = substr($line, $pos+1);
            }
            // echo "not string!<br/>";
        }else if(count($matches) == 1){
            //string!! 
            $len = strlen($matches[0]);
            $fields[] = $matches[0];
            $line = substr($line, $len+1); 
            // echo "string!<br/>";
        }else{
            //something hath gone astray
            die("Error: regex matched twice, not supposed to!");
        }
        // echo "Line: $line<br/>";
        // foreach($fields as $field){
        //     echo "$field    ";
        // }
        // echo "<br/>";
    }
    return $fields; 
}

?>