<?php
    require_once("proc_gallery.php");
    echo "<h2>Gallery Viewer</h2>";

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