<?php

    # Singleton
    require_once('resources/singleton.php');
    $singleton = Singleton::init();

    # Shortcut for some commonly used classes
    $controller = $singleton::$controller;
    $session = $singleton::$session;

    echo'<title>Web Helpers</title>';
    echo'<link rel="alternate" href="https://sloa.dk" hreflang="dk" />';
    echo'<meta charset="utf-8">';
    echo'<meta http-equiv="content-language" content="en">';
    echo'<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    echo'<meta name="author" content="Henrik Jeppesen" />';
    echo'<meta name="description" content="A set of tools to quickly get you up and running with new web projects">';
    echo'<meta name="keywords" content="CSS, PHP, OOP, web creator, framework" />';
    echo'<meta name="robot" content="index, follow"/>';
    echo'<meta name="viewport" content="width=device-width, initial-scale=0.8">';

    # Stylesheet(s)
    echo'<link href="css/styles.css" rel="stylesheet">';
?>
