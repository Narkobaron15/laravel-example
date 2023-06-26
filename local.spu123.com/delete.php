<?php
    // if the request is valid,
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
        // the script imports DB connection handler object
        include $_SERVER["DOCUMENT_ROOT"] . '/connection_to_db.php';
        // and executes sql request
        $sql = "DELETE FROM users WHERE id = ?;";
        // if the handler is not null or unset.
        if(isset($dbh)) {
            // Generating PDOStatement
            $stmt = $dbh->prepare($sql);
            // that will allow to execute SQL with injection
            $stmt->execute([$_POST["id"]]);

            // Sets HTTP Response headers. Should be called before any HTML markup
            // Location header inflicts further redirect
            header('Location: /');

            // 'exit' directive terminates program's execution,
            // and is equivalent to 'die'
            exit;
        }
    }
