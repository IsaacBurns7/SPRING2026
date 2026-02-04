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


</div>
<p/>

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

<!-- Site Search .......................... -->
<h3>Site Search</h3>
<form method="GET" action="index.php">
    <label for="q">Search:</label>
    <input type="text" id="q" name="q" placeholder="Enter search keyword..." 
           value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<div class="results">
<?php
    //no need to protect against infinite recursion here b/c search php already does that :) 
    require_once("search.php");
    $searchQuery = $_GET['q'];
    $sanitizedQuery = sanitize($searchQuery);
    
    if ($sanitizedQuery === '') {
        echo "<p>Please enter a valid search term (alphanumeric characters and spaces only).</p>";
    } else {
        $results = search($searchQuery);
        display_results($results, $sanitizedQuery);
    }
?>

<?php 
    require_once("proc_csv.php");
    require_once("proc_markdown.php");

    // var_dump(file_get_contents('http://webserver/jstest.php'));

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

    $test_files = [
        // 'edge-case-inline.md',
        // 'edge-case-headings.md',
        // 'edge-case-lists.md',
        // 'edge-case-paragraphs.md',
        // 'edge-case-combined.md',
        'edge-case-basic.md'
    ];

    echo "<h2>Markdown Tests</h2>";
    proc_markdown("../data/markdown.md");
    // echo "<hr>";
    // foreach($test_files as $file) {
    //     echo "<h3>Testing: $file</h3>";
    //     proc_markdown("../data/markdown/$file");
    //     echo "<hr>";
    // }


    

    echo "<h2>Gallery Viewer</h2>";
    require_once("proc_gallery.php");

    // Get default values from URL parameters or use defaults
    $displayMode = isset($_POST['displayMode']) ? $_POST['displayMode'] : 'list';
    $sortMode = isset($_POST['sortMode']) ? $_POST['sortMode'] : 'orig';

    // Validate inputs to prevent misuse
    $validDisplayModes = ['list', 'matrix', 'details'];
    $validSortModes = ['orig', 'date_newest', 'date_oldest', 'size_largest', 'size_smallest', 'rand'];
    
    if (!in_array($displayMode, $validDisplayModes)) $displayMode = 'list';
    if (!in_array($sortMode, $validSortModes)) $sortMode = 'orig';

    echo "<form id='galleryForm' method='POST' style='margin-bottom: 20px;'>";
    echo "  <label for='sortMode'>Sort by:</label>";
    echo "  <select id='sortMode' name='sortMode' onchange='this.form.submit();'>";
    echo "    <option value='orig' " . ($sortMode == 'orig' ? 'selected' : '') . ">Original</option>";
    echo "    <option value='date_newest' " . ($sortMode == 'date_newest' ? 'selected' : '') . ">Newest First</option>";
    echo "    <option value='date_oldest' " . ($sortMode == 'date_oldest' ? 'selected' : '') . ">Oldest First</option>";
    echo "    <option value='size_largest' " . ($sortMode == 'size_largest' ? 'selected' : '') . ">Largest First</option>";
    echo "    <option value='size_smallest' " . ($sortMode == 'size_smallest' ? 'selected' : '') . ">Smallest First</option>";
    echo "    <option value='rand' " . ($sortMode == 'rand' ? 'selected' : '') . ">Random</option>";
    echo "  </select>";
    
    echo "  &nbsp;&nbsp;&nbsp;";
    
    echo "  <label for='displayMode'>Display as:</label>";
    echo "  <select id='displayMode' name='displayMode' onchange='this.form.submit();'>";
    echo "    <option value='list' " . ($displayMode == 'list' ? 'selected' : '') . ">List</option>";
    echo "    <option value='matrix' " . ($displayMode == 'matrix' ? 'selected' : '') . ">Matrix</option>";
    echo "    <option value='details' " . ($displayMode == 'details' ? 'selected' : '') . ">Details</option>";
    echo "  </select>";
    // echo "  <input type='hidden' name='displayMode' value='" . htmlspecialchars($displayMode) . "'>";
    // echo "  <input type='hidden' name='sortMode' value='" . htmlspecialchars($sortMode) . "'>";
    echo "</form>";

    #proc_gallery internally uses proc_csv, maybe add a mode to disable sideeffects 
    proc_gallery("../data/my_favorites.csv", $displayMode, $sortMode);
?>