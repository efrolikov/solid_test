<?php
/**
 * Config file
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'solid_test');

$delim = strtolower(PHP_OS)=='linux'? '/' : '\\';
define('ROOT_DIR', __DIR__.$delim.'..'.$delim);