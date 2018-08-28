<?php

if(isset($_POST['deleteFile']))
    echo $controller->deleteFile();


# List all of the models
$br = 0;
echo'<table style="width:100%;">';
echo'<tr>';
foreach ($controller->getModules() as $key) {
    if($key == "." || $key == "..")
        continue;

    $bg = "white";
    $br++;
    if ($br % 2 == 0)
        $bg = "#f1f1f1";

    echo'<td class="examplesTd" style="background-color:'.$bg.';">';
        echo $key;
    echo'</td>';
    echo'<td class="examplesTd" style="background-color:'.$bg.';">';
        echo'<form method="post" >';
            echo'<button type="submit" title="'.$key.'" class="addButton" name="deleteFile">Delete</button>';
            echo'<input type="hidden" name="file" value="'.$key.'">';
        echo'</form>';
    echo'</td>';
    echo'</tr><tr>';
}
echo'</tr>';
echo'</table>';

?>
