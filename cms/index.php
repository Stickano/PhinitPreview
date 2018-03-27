<?php
echo'<!DOCTYPE html>';
echo'<html lang="da">';
echo'<head>';

	# Include the meta/headers
	require_once('resources/meta.php');

echo'</head>';
echo'<body>';

    # Print out any errors
    if($controller->getError()){
        echo '<div class="errorContainer">';
            echo $controller->getError();
            echo '<button class="closeErr right"><i class="fa fa-times" aria-hidden="true"></i></button>';
        echo '</div>';
    }

    # This will load the appropriate view
    require_once('views/'.$singleton::$page.'.php');

    # Include JS
    echo'<script src="js/dynamics.js"></script>';

echo'</body>';
echo'</html>';
?>
