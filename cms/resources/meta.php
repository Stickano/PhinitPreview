<?php

    # Singleton
    require_once('resources/singleton.php');
    $singleton = Singleton::init();

    # Shortcut for some commonly used classes
    $controller = $singleton::$controller;
    $session = $singleton::$session;

    echo'<title>Phinit CMS</title>';
    echo'<link rel="alternate" href="https://sloa.dk" hreflang="dk" />';
    echo'<meta charset="utf-8">';
    echo'<meta http-equiv="content-language" content="en">';
    echo'<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    echo'<meta name="author" content="Henrik Jeppesen" />';
    echo'<meta name="description" content="Content Management System for the preview page of the Phinit modules">';
    echo'<meta name="keywords" content="PHP, OOP, web, Phinit" />';
    echo'<meta name="robot" content="noindex, nofollow"/>';
    echo'<meta name="viewport" content="width=device-width, initial-scale=0.8">';

    # Stylesheet(s)
    echo'<link href="css/styles.css" rel="stylesheet">';

    # JQuery
    echo'<script src="js/jquery-3.2.1.min.js"></script>';
?>
