<?php
    require_once("proc_csv.php");

    echo "<br/><br/><br/>TESTING PROC CSV"; 
    proc_csv("../data/dat-doublequote-comma.csv",",","\"", "ALL");
    proc_csv("../data/dat-doublequote-comma.csv",",","\"", "1:3");

    proc_csv("../data/dat-doublequote-tab.csv","\t","\"", "ALL");
    proc_csv("../data/dat-doublequote-tab.csv","\t","\"", "1:3");

    proc_csv("../data/dat2-doublequote-comma.csv",",","\"", "ALL");
    proc_csv("../data/dat2-doublequote-comma.csv",",","\"", "1:3");

    proc_csv("../data/dat2-doublequote-tab.csv","\t","\"", "ALL");
    proc_csv("../data/dat2-doublequote-tab.csv","\t","\"", "1:3");

    proc_csv("../data/dat2-singlequote-tab.csv","\t","'", "ALL");
    proc_csv("../data/dat2-singlequote-tab.csv","\t","'", "1:3");

    proc_csv("../data/dat-singlequote-comma.csv",",","'", "ALL");
    proc_csv("../data/dat-singlequote-comma.csv",",","'", "2");
?>