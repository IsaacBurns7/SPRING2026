<html>

<!-- HEAD section ............................................................................ -->
<head>
  <title> Javascript Test </title>

<?php

 # PHP functions to be used in javascript

 $data = array("foo", "bar", "kit", "kat", "hello", "world");

 function sort_by_char($dat) {
   $dat_copy = $dat;
   sort($dat_copy);
   echo "<pre>Sorted alphabetically: \n";
   print_r($dat_copy);
   echo "\n\n - check the page source, especially the javascript functions!!\n";
   echo "<pre>";
 }

 function sort_by_char_reverse($dat) {
   $dat_copy = $dat;
   rsort($dat_copy,);
   echo "<pre>Sorted alphabetically: \n";
   print_r($dat_copy);
   echo "\n\n - check the page source, especially the javascript functions!!\n";
   echo "<pre>";
 }

?>

  <!-- javascript functions -->
  <script>

  // Note: You can include PHP code in javascript, if the javascript resides in a PHP file.
  //   - PHP server will replace the PHP code inside the javascript, before the final HTML is generated.
  //   - Because of this, when you inspect the source, you will see the resulting text from the PHP code,
  //     not the PHP code itself.

  function randText() {
      // this is a native javascript function, only using javascript language.
      let randomBits = ["hello world", "random thoughts", "pinky and the brain"];
      document.getElementById("demo").innerHTML = randomBits[Math.floor(Math.random()*3)];
	window.alert("We're in radnText");
  }

  function sortText() {
      // this function uses PHP code to replace the string.
      // note: use [`] to allow multi-line string assigned. 
      document.getElementById("demo").innerHTML = `<?php sort_by_char($data); ?>`;
	
  }

  function sortReverseText() {
      // this function uses PHP code to replace the string.
      // note: use [`] to allow multi-line string assigned. 
      document.getElementById("demo").innerHTML = `<?php sort_by_char_reverse($data); ?>`;
	
  }

  function replaceText() {
      // search for text nodes in DOM and replace content
      // See https://www.digitalocean.com/community/tutorials/how-to-make-changes-to-the-dom for a tutorial on nodes and DOM

      var pattern=/hello/ig;
      var replace='howdy';

      for (const item of Array.from(document.body.getElementsByTagName('*'))) {
         for (const node of Array.from(item.childNodes).filter(node => node.nodeType === Node.TEXT_NODE)) {

	     var dat = node.data;
	     node.replaceWith(dat.replaceAll(pattern,replace));

	 }
      } // end outer for loop
  }
 
  </script>


  <!-- style -->
 
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
    <!-- link href="default.css" rel="stylesheet" type="text/css" -->
  </style>


</head>

<!-- BODY section ............................................................................. -->
<body>
<div class="defaultFont">

<!-- PHP testing area ................................ --> 
<?php

   echo "<h1> Javascript test </h1>\n";

   echo '<blockquote> DEBUG Canvas : <p id="debug_canvas"> Hello world! hello world! </p> </blockquote>';

?>

<!-- Java script testing area ............................... -->

<div class="secondaryFont"> 

<h3>Java script test</h3>

<p id="demo"> Content to be changed: </p> 

<div id="debug"> Debug canvas </div> 

<button type="button" onclick="randText()">Click Me for Random text!</button>

<button type="button" onclick="sortText()">Click Me for Sorted data!</button>

<button type="button" onclick="sortReverseText()">Click Me for Reverse Sorted data!</button>

<button type="button" onclick="replaceText()">Click to highlight text!</button>

<button onClick="window.location.reload();">Reload Page</button>

</div>

</body>

</html>
