<!--
    PHP code can be combined with HTML markup.
    PHP code is contained in tag '<?php /*code*/ ?>'
    If the whole file contains PHP exclusively,
    closing tag '?>' is not necessary.
-->

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/src/css/site.css">
</head>
<body>
<!--
    $_SERVER array contains info of web server & its environment
    The dot in between $_SERVER property and string
    is string concatenation operator
    "DOCUMENT_ROOT" property holds current project's path

    Include directive loads the file/library requested.
    It loads connection code here,
-->
<?php include $_SERVER["DOCUMENT_ROOT"].'/connection_to_db.php'; ?>

    <header>
        <!-- and the navbar there. -->
        <?php include $_SERVER["DOCUMENT_ROOT"].'/navbar.php';?>
    </header>

    <main>
        <h1 class="text-center my-5">Hello world!</h1>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Фото</th>
                    <th>Ім'я</th>
                    <th>Пошта</th>
                    <th>Команди</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <!-- This code connects to the database and displays info selected -->
                <?php
                    // 'isset' is the function indicating variable is not null or undefined
                    if (!isset($dbh)) return;

                    // variables in PHP have dollar sign in their names
                    // and can be initialized without any other keyword.

                    $sqlcommand = 'SELECT id, name, email, image FROM users';
                    $result = $dbh->query($sqlcommand); // execute request to the DB via PDO instance

                    // foreach syntax: foreach(iterable(collection) as iterator(item of collection)
                    foreach ($result as $row) {
                        // currently $row is mixed (Object object in JavaScript)
                        // and allows the same square bracket property syntax as in JS

                        $id = $row["id"];
                        $name = $row["name"];
                        $email = $row["email"];
                        $image = $row["image"];
                        // Create text description via concatenating $name and string
                        $alt = $name."&apos;s image";

                        // 'echo' puts its content directly in the place of PHP code
                        echo "
                              <tr>
                                <th scope='row'>$id</th>
                                <td><img class='mx-auto' alt='$alt' src='$image' width='50' /></td>
                                <td>$name</td>
                                <td>$email</td>
                                <td>
                                <form method='post' action='/delete.php'>
                                    <input type='hidden' value=$id name='id'>
                                    <button class='btn btn-delete' type='submit'>Видалити</button>
                                </form>
                                </td>
                              </tr>
                              ";
                    }
                ?>
            </tbody>
        </table>
    </main>

    <footer>

    </footer>

    <script src="/src/js/bootstrap.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>
</html>