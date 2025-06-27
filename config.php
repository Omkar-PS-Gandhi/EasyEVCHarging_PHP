<?php

//Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');       
define('DB_NAME', 'easy_ev_db');

//Autoload classes
spl_autoload_register(function($class) {
    require_once __DIR__ . '/classes/' . $class . '.php';
});

//Start PHP session for login tracking
session_start();
?>
