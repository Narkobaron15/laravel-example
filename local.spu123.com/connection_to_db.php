<?php
// includes config file defined HTML & PHP code
// (here allows to read custom project constants
include $_SERVER["DOCUMENT_ROOT"].'/config.php';

try {
    // constructing Connection String with string concatenation operator '.'
    $constr = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
    // creating database handler object using 'PDO' - PHP Database object
    // (modern & supports multiple DB providers
    $dbh = new PDO($constr, DB_USER, DB_PWD);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    // 'die' directive terminates program's execution,
    // and is equivalent to 'exit'
    // parentheses here are optional
    die();
}

/* ?> - no need to close the tag, because PHP code is in the end of a file */