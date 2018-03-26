<?php
echo'<!DOCTYPE html>';
echo'<html lang="da">';
echo'<head>';

	# Include the meta/headers
	require_once('resources/meta.php');

echo'</head>';
echo'<body>';

    # This will load the appropriate view
    require_once('views/'.$singleton::$page.'.php');

    # Include JS
    echo'<script src="js/dynamics.js"></script>';

echo'</body>';
echo'</html>';
?>
