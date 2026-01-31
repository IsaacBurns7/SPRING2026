<html>

<!-- HEAD section ............................................................................ -->
<head>
  <title> Yoonsuck Choe's Experimental Web Site </title>


  <!-- javascript functions -->
  <script>
  function randText() {
      let randomBits = ["hello world", "random thoughts", "pinky and the brain"];
      document.getElementById("demo").innerHTML = randomBits[Math.floor(Math.random()*3)];
  }
  </script>

  <!-- style -->
 
  <!--
  <style>
    div.defaultFont {
        font-family: Helvetica, Arial, sans-serif;
    }
    
    div.secondaryFont {
        font-family: serif;
    }

    h3 {
        color: blue;
    }
    <link href="default.css" rel="stylesheet" type="text/css>
  </style> -->

  <LINK REL=StyleSheet HREF="simple.css" TYPE="text/css" MEDIA=screen>
  

</head>

<!-- BODY section ............................................................................. -->
<body>
<div class="defaultFont">

<!-- PHP testing area ................................ --> 
<?php

   echo "<h1> Choe's Experimental CSCE 331 Docker Web Site </h1>\n";

   echo "<font color=\"green\"> Haha update and redeploy </font><p/>\n";
   echo " Testing PHP <br>\n";
   echo " Hello world!<p/>\n";

   echo "<h3>Testing file loading:</h3>\n";

   # FILE access 
   # $h = fopen("false.dat","r");

   $handle = fopen("data.dat","r") or die("Cannot open data.dat");

   echo "<table  border=\"1\">\n";

   while ($data = fgets($handle)) {
        echo "<tr>\n";
        $data_cols = preg_split('/,/',$data);
        for ($k=0; $k<count($data_cols); ++$k) {
            echo "  <td> ".$data_cols[$k]." </td>\n";
        }
        echo "</tr>\n";
   }

   fclose($handle);

   echo "</table>\n<p/>";
   

   echo "Debug: ";
   print_r($data_cols); 

   echo "<p/>";

   echo "rand : ".$data_cols[rand(0,1)]."\n";

   echo "<p/>";

?>


<!-- Java script testing area ............................... -->

<div class="secondaryFont"> 

<h3>Java script test</h3>

<p id="demo"> Content to be changed: </p> 

<button type="button" onclick="randText()">Click Me!</button>

<button onClick="window.location.reload();">Reload Page</button>

<p>For more javascript examples, see <a href="jstest.php">jstest.php</a>.</p>

</div>

<!-- Fetch testing............................... -->
<div class="SecondaryFont">
<h3> Fetch html source code </h3>
<a href="fetch.php">fetch.php</a>
</div>

<!-- HTML form input handling .......................... -->

<h3>HTML Form input test</h3>
<p/>
<form action="action.php" method="post">
Search: <input type="text" name="name"> 
<input type="submit">
</form>
<p/>

<h3>HTML Form input test 2 </h3>

Search academic genealogy (external link: <a href="https://www.mathgenealogy.org">https://www.mathgenealogy.org</a>): <p/>
<form action="https://www.mathgenealogy.org/query-prep.php" method="post">
Firstname:
<input type="text" name="given_name" value="Yoonsuck">  <br/>
Lastname:
<input type="text" name="family_name" value="Choe"> 
<input type="submit">
</form>
<p/>
</div> <!-- end of big div -->

<?php echo "Container IP Address:".getenv('MY_IP')."\n"; ?>
</body>

</html>

<?php 
    // echo "<br/><br/><br/>TESTING PROC CSV"; 
    // require_once("proc_csv.php");
    // proc_csv("../data/dat-doublequote-comma.csv",",","\"", "ALL");
    // proc_csv("../data/dat-doublequote-comma.csv",",","\"", "1:3:5:7");

    // proc_csv("../data/dat-doublequote-tab.csv","\t","\"", "ALL");
    // proc_csv("../data/dat-doublequote-tab.csv","\t","\"", "1:3:5:7");

    // proc_csv("../data/dat2-doublequote-comma.csv",",","\"", "ALL");
    // proc_csv("../data/dat2-doublequote-comma.csv",",","\"", "1:3:5:7");

    // proc_csv("../data/dat2-doublequote-tab.csv","\t","\"", "ALL");
    // proc_csv("../data/dat2-doublequote-tab.csv","\t","\"", "1:3:5:7");

    // proc_csv("../data/dat2-singlequote-tab.csv","\t","'", "ALL");
    // proc_csv("../data/dat2-singlequote-tab.csv","\t","'", "1:3:5:7");

    // proc_csv("../data/dat-singlequote-comma.csv",",","'", "ALL");
    // proc_csv("../data/dat-singlequote-comma.csv",",","'", "1:3:5:7");

    // require_once("proc_markdown.php");
    // $test_files = [
    //     'edge-case-inline.md',
    //     'edge-case-headings.md',
    //     'edge-case-lists.md',
    //     'edge-case-paragraphs.md',
    //     'edge-case-combined.md',
    //     'edge-case-basic.md'
    // ];

    // foreach($test_files as $file) {
    //     echo "<h2>Testing: $file</h2>";
    //     proc_markdown("../data/markdown/$file");
    //     echo "<hr>";
    // }

    require_once("proc_gallery.php");

    // 18 calls: sortmode (outer) x displaymode (inner)
    // sortmode: orig, date_newest, date_oldest, size_largest, size_smallest, rand
    // displaymode: list, matrix, details

    // orig
    echo "<h2>Testing: orig</h2>";
    proc_gallery("../data/my_favorites.csv", "list", "orig");
    proc_gallery("../data/my_favorites.csv", "matrix", "orig");
    proc_gallery("../data/my_favorites.csv", "details", "orig");
    echo "<hr>";

    // date_newest
    echo "<h2>Testing: date_newest</h2>";
    proc_gallery("../data/my_favorites.csv", "list", "date_newest");
    proc_gallery("../data/my_favorites.csv", "matrix", "date_newest");
    proc_gallery("../data/my_favorites.csv", "details", "date_newest");
    echo "<hr>";

    // date_oldest
    echo "<h2>Testing: date_oldest</h2>";
    proc_gallery("../data/my_favorites.csv", "list", "date_oldest");
    proc_gallery("../data/my_favorites.csv", "matrix", "date_oldest");
    proc_gallery("../data/my_favorites.csv", "details", "date_oldest");
    echo "<hr>";

    // size_largest
    echo "<h2>Testing: size_largest</h2>";
    proc_gallery("../data/my_favorites.csv", "list", "size_largest");
    proc_gallery("../data/my_favorites.csv", "matrix", "size_largest");
    proc_gallery("../data/my_favorites.csv", "details", "size_largest");
    echo "<hr>";

    // size_smallest
    echo "<h2>Testing: size_smallest</h2>";
    proc_gallery("../data/my_favorites.csv", "list", "size_smallest");
    proc_gallery("../data/my_favorites.csv", "matrix", "size_smallest");
    proc_gallery("../data/my_favorites.csv", "details", "size_smallest");
    echo "<hr>";

    // rand
    echo "<h2>Testing: rand</h2>";
    proc_gallery("../data/my_favorites.csv", "list", "rand");
    proc_gallery("../data/my_favorites.csv", "matrix", "rand");
    proc_gallery("../data/my_favorites.csv", "details", "rand");

?>
