<?php

if(isset($_POST['deleteExample']))
    $controller->deleteExample();


# List all examples
$br = 0;
echo'<table style="width:100%;">';
echo'<tr>';
foreach ($controller->getExamples() as $key) {
    $bg = "white";
    $br++;
    if ($br % 2 == 0)
        $bg = "#f1f1f1";

    echo'<td class="examplesTd" style="background-color:'.$bg.';">';
        echo "<a href='index.php?add&edit=".$key['id']."' title='Edit Example'>".$key['headline']."</a>";
    echo'</td>';
    echo'<td class="examplesTd" style="background-color:'.$bg.';">';
        echo'<form method="post" >';
            echo'<button type="submit" title="'.$key['headline'].'" class="addButton" name="deleteExample">Delete</button>';
            echo'<input type="hidden" name="id" value="'.$key['id'].'">';
            echo'<input type="hidden" name="headline" value="'.$key['headline'].'">';
        echo'</form>';
    echo'</td>';
    echo'</tr><tr>';
}
echo'</tr>';
echo'</table>';

?>
