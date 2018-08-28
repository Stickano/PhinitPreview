<?php
echo'<!DOCTYPE html>';
echo'<html lang="da">';
echo'<head>';

	# Include the meta/headers
	require_once('resources/meta.php');

echo'</head>';
echo'<body>';

    # Print out any errors
    if($singleton->getError()){
        echo '<div class="errorContainer">';
            echo $singleton->getError();
            echo '<button class="closeErr right"><i class="fa fa-times" aria-hidden="true"></i></button>';
        echo '</div>';
    }

    echo'<div class="container">';

        # Menu (header)
        echo'<div class="menuContainer">';
            echo'PHPinit CMS';
            if (!isset($_GET['edit']) && !isset($_GET['add']))
                echo'<a href="index.php?add" class="addButton">Add Example</a>';
            else
                echo'<a href="index.php" class="addButton">Close</a>';

            if (!isset($_GET['modules']))
                echo'<a href="index.php?modules" class="addButton">Files '.$singleton->spaces(5).'</a>';
            else
                echo'<a href="index.php" class="addButton">Close '.$singleton->spaces(5).'</a>';

            if (!isset($_GET['announcement']))
                echo'<a href="index.php?announcement" class="addButton">Announcement '.$singleton->spaces(5).'</a>';
            else
                echo'<a href="index.php" class="addButton">Close '.$singleton->spaces(5).'</a>';
        echo'</div>';

        # Load the appropriate view
        require_once('views/'.$singleton::$page.'.php');

    echo'</div>';

    # Include JS
    echo'<script src="js/dynamics.js"></script>';

echo'</body>';
echo'</html>';
?>
