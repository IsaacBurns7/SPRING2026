<?php

/**
 * display_list()
 * IN:  $lines - array of associative arrays with keys: filepath, description, size, timestamp
 * OUT: void - echoes HTML for list view (vertical stack of images with descriptions)
 */
function display_list($lines) {
    echo "<div class = 'gallery-list'>";
    foreach($lines as $line){
        echo "<div class = 'list-item'>";
        echo "  <img src = '" . htmlspecialchars($line['filepath']) . "' alt = 'image' style='max-width:200px; max-height:200px;'>";
        echo "  <div class='info'>";
        echo "    <p class='description'>" . htmlspecialchars($line['description']) . "</p>";
        // echo "    <p class='metadata'>";
        // echo "      Size: " . number_format($line['size']) . " bytes | ";
        // echo "      Date: " . date('Y-m-d H:i', $line['timestamp']);
        // echo "    </p>";
        echo "  </div>";
        echo "</div>";
    }
    echo "</div>";
}

/**
 * display_matrix()
 * IN:  $lines - array of associative arrays with keys: filepath, description, size, timestamp
 * OUT: void - echoes HTML for 3-column matrix grid view
 */
function display_matrix($lines) {
    echo "<div class='gallery-matrix'>";
    echo "<table style='width:100%; border-collapse:collapse;'>";
    
    $count = 0;
    foreach($lines as $line) {
        if ($count % 3 == 0) {
            echo "<tr>";
        }
        
        echo "<td style='padding:10px; text-align:center; border:1px solid #ddd;'>";
        echo "  <img src='" . htmlspecialchars($line['filepath']) . "' style='max-width:150px; max-height:150px;'>";
        echo "  <p style='font-size:12px;'>" . htmlspecialchars($line['description']) . "</p>";
        echo "</td>";
        
        $count++;
        if ($count % 3 == 0) {
            echo "</tr>";
        }
    }
    
    // Close last row if incomplete
    if ($count % 3 != 0) {
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
}

/**
 * display_details()
 * IN:  $lines - array of associative arrays with keys: filepath, description, size, timestamp
 * OUT: void - echoes HTML for table view with file details (name, description, size, date)
 */
function display_details($lines) {
    echo "<div class='gallery-details'>";
    echo "<table style='width:100%; border-collapse:collapse; margin:10px 0;'>";
    echo "<thead>";
    echo "<tr style='background-color:#f0f0f0; border-bottom:2px solid #333;'>";
    echo "  <th style='padding:8px; text-align:left; border-right:1px solid #ddd;'>Filename</th>";
    echo "  <th style='padding:8px; text-align:left; border-right:1px solid #ddd;'>Description</th>";
    echo "  <th style='padding:8px; text-align:right; border-right:1px solid #ddd;'>Size (bytes)</th>";
    echo "  <th style='padding:8px; text-align:left;'>Modified</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach($lines as $line) {
        echo "<tr style='border-bottom:1px solid #ddd;'>";
        echo "  <td style='padding:8px; border-right:1px solid #ddd;'>" . htmlspecialchars($line['filepath']) . "</td>";
        echo "  <td style='padding:8px; border-right:1px solid #ddd;'>" . htmlspecialchars($line['description']) . "</td>";
        echo "  <td style='padding:8px; text-align:right; border-right:1px solid #ddd;'>" . number_format($line['size']) . "</td>";
        echo "  <td style='padding:8px;'>" . date('Y-m-d H:i:s', $line['timestamp']) . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}

/**
 * proc_gallery()
 * IN:  $image_list_filename - path to CSV file with columns: filename, description
 *      $mode - display mode: "list", "matrix", or "details"
 *      $sort_mode - sort order: "orig", "date_newest", "date_oldest", "size_largest", "size_smallest", "rand"
 * OUT: void - echoes formatted HTML gallery based on mode and sort
 */
function proc_gallery($image_list_filename, $mode, $sort_mode) {

   #read in the csv file as an array of pairs
    $file = fopen($image_list_filename, "r");
    if(!$file){
        return;
    }
    $lines = [];
    $directory = dirname($image_list_filename); 
    while(($row = fgetcsv($file)) !== false){
        $filepath = "images/" . $row[0];
            //assumption: .csv lines in dirA, dirA/images/* contains all files listed
        $lines[] = [
            'filepath' => $filepath,
            'description' => $row[1],
                //filesizes and timestamps need to be retrieved for sorting functions 
            'size' => filesize($filepath),
            'timestamp' => filemtime($filepath),
        ];
    }
    fclose($file);

    //<=> -> 1 if left is greater, 0 if equal, -1 if right is greater
    $sorters = [
        "date_newest" => function($a, $b) { return $b['timestamp'] <=> $a['timestamp']; },
        "date_oldest" => function($a, $b) { return $a['timestamp'] <=> $b['timestamp']; },
        "size_largest" => function($a, $b) { return $b['size'] <=> $a['size']; },
        "size_smallest" => function($a, $b) { return $a['size'] <=> $b['size']; },
        "rand" => function($a, $b) { return rand(-1, 1); },
        "orig" => function($a, $b) { return 0; },
    ];

   # sort by sort_mode 
    usort($lines, $sorters[$sort_mode]); //errors if not a valid sorter - intended

   # display by display_mode
    switch($mode) {
        case "list":
            display_list($lines);
            break;
        case "matrix":
            display_matrix($lines);
            break;
        case "details":
            display_details($lines);
            break;
        default:
            break;
    }

}

