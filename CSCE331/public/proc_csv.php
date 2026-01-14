<?php 

function proc_csv($filename, $delimiter, $quote, $columns_to_show){
    $mode = "r";
    $handle = fopen($filename, $mode) or die ("Cannot open $filename");
    $cols = [];
    $data = fgets($handle);
    echo "<br/>RAW DATA: "; 
    var_dump($data);
    $data = trim($data);
    echo "<br/>non-raw data: "; 
    var_dump($data);

    /*
    3 cases: you're in quotes and you hit a delimiter, you're not in quotes and you hit a delimiter
    you're 
    */
    $data = my_fgetcsv("$data", $delimiter, $quote);
    echo "<br/>processed array: ";
    var_dump($data);

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
        $cols = preg_split(":", $columns_to_show);    
    }else{
        die ("$columns_to_show did not match pattern \'ALL\' or $reg");
    }
    echo "<br/>";
    var_dump($cols);

    echo "<table  border=\"1\">\n";
    echo "<tr>\n";
    foreach($cols as $index){
        echo "  <td> ".$data[$index]." </td>\n";
    }
    echo "</tr>\n";
    while ($data = fgets($handle)) {
        echo "<tr>\n";
        $data_cols = my_fgetcsv($data,$delimiter,$quote);
        foreach ($cols as $index) {
            echo "  <td> ".$data_cols[$index]." </td>\n";
        }
        echo "</tr>\n";
    }
}

function my_fgetcsv($line, $delimiter = ",", $enclosure = '"'){
    echo "<br/>";
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

?>