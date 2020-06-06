<?php

/*
 * This file provides a trivial method to toggle verbose PHP error reporting. It also contains configuration globals
 * used throughout the minimalist MVC framework. Whilst no longer strictly necessary, I utilise DIRECTORY_SEPARATOR to
 * support legacy platforms.
 */

// Toggle HTML form validation, intentionally independent of DEV mode
define('HTML5_VAL', FALSE);
define('DEV', TRUE);

if (DEV)
{
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else
{
    ini_set("display_errors", 0);
}

// Usertype globals
define('USER_GUEST', 0);
define('USER_AUTHED', 1);

// Folder globals
define('DS', DIRECTORY_SEPARATOR);
define('APP_DIR', __DIR__ . DS);
define('VIEW_DIR', APP_DIR . 'Views' . DS);
// define('URL_SUB_DIR', DS . 'wiki');
define('URL_SUB_DIR', '');

// Image globals
define('IMAGE_DIR', APP_DIR . '..' . DS . 'images' . DS);
define('PLACEHOLDER_IMG', 'placeholder.png');

// DB conf globals
define('DB_IP',   '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'wiki');
define('DB_USER', '');
define('DB_PASS', '');
