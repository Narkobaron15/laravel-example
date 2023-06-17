<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">-->
    <!--<meta http-equiv="X-UA-Compatible" content="ie=edge">-->
    <title>Document</title>
    <link rel="stylesheet" href="/src/css/site.css">
</head>
<body>
<?php include $_SERVER["DOCUMENT_ROOT"].'/connection_to_db.php'; ?>

    <header>
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
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                    if (!isset($dbh)) return;

                    $sqlcommand = 'SELECT id, name, email, image FROM users';
                    $result = $dbh->query($sqlcommand);

                    foreach ($result as $row) {
                        $id = $row["id"];
                        $name = $row["name"];
                        $email = $row["email"];
                        $image = $row["image"];
                        $alt = $name."&apos; image"; // Create text description
                        echo "
                              <tr>
                                <th scope='row'>$id</th>
                                <td><img class='mx-auto' alt='$alt' src='$image' width='50' /></td>
                                <td>$name</td>
                                <td>$email</td>
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