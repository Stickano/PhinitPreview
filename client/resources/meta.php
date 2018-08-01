<?php

    # Singleton
    require_once('resources/singleton.php');
    $singleton = Singleton::init();

    # Shortcut for some commonly used classes
    $controller = $singleton::$controller;
    $session    = $singleton::$session;

    echo'<title>PHPinit</title>';
    echo'<link rel="alternate" href="https://phpinit.com" hreflang="en" />';
    echo'<meta charset="utf-8">';
    echo'<meta http-equiv="content-language" content="en">';
    echo'<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    echo'<meta name="author" content="Henrik Jeppesen" />';
    echo'<meta name="description" content="PHP models for backend web development">';
    echo'<meta name="keywords" content="PHP, OOP, backend, web" />';
    echo'<meta name="robot" content="index, follow"/>';
    echo'<meta name="viewport" content="width=device-width, initial-scale=0.8">';
    echo'<link rel="shortcut icon" href="media/favicon.ico">';
    echo'<meta name="flattr:id" content="knol91">';
    echo'<meta name="google-site-verification" content="GnnB3EVZMc3ArEZDu2wHH4hkgXmUJZHTZ72eiB76uFo" />';

    # Stylesheet(s)
    echo'<link href="css/styles.css" rel="stylesheet">';

    # Thanks to https://highlightjs.org for syntax highlight
    echo'<link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">';
    echo'<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>';
    echo'<script>hljs.initHighlightingOnLoad();</script>';
?>
