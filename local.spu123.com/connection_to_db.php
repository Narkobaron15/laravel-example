<?php
include $_SERVER["DOCUMENT_ROOT"].'/config.php';

try {
    $constr = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
    $dbh = new PDO($constr, DB_USER, DB_PWD);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}