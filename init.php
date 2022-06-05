<?php
/**
 * initialisation
 */

include 'config/config.php';

// class autoload
spl_autoload_register(function ($class_name) {
    $class_arr = explode('\\', $class_name);
    $path = implode('/', $class_arr);
    include($path.'.php');
});

use models\App;

// connect to database
$db = null;
try {
    $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    App::setDb($db);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
