<?php
    // Function syntax in PHP: function fnName(args): returnType
    // mixed types: TypeA | TypeB | ...
    function ExecuteSql(PDO | null $handler, string $sql, Array $params): void
    {
        // if the handler is not null or unset
        if(isset($handler)) {
            // Generating PDOStatement
            $stmt = $handler->prepare($sql);
            // that will allow to execute SQL with injection
            $stmt->execute($params);

            // Sets HTTP Response headers. Should be called before any HTML markup
            // Location header inflicts further redirect
            header('Location: /');

            // 'exit' directive terminates program's execution,
            // and is equivalent to 'die'
            exit;
        }
    }