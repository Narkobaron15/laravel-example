<?php
    $name = "";
    $email = "";
    $image = "";
    $password = "";

    // handling different http request methods is possible
    // on the same page, in the same file

    // handling HTTP POST Request
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        // extracting posted values if they are set
        if(isset($_POST['name']))
            $name=$_POST['name'];
        if(isset($_POST['email']))
            $email=$_POST['email'];
        if(isset($_POST['image']))
            $image=$_POST['image'];
        if(isset($_POST['password']))
            $password=$_POST['password'];

        // If all of them are filled,
        if(!empty($name) && !empty($image) && !empty($email) && !empty($password)) {
            // the script imports DB connection handler object
            include $_SERVER["DOCUMENT_ROOT"] . '/connection_to_db.php';
            global $dbh;

            // and executes sql request
            // if the handler is not null or unset.
            $sql = "INSERT INTO users(name, email, image, password) VALUES(?, ?, ?, ?);";
            include $_SERVER["DOCUMENT_ROOT"] . '/execsql.php';
            ExecuteSql($dbh, $sql, [$name, $email, $image, $password]);
        }
    }
?>

<!-- Handling HTTP GET Request -->

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
    <!-- Getting DB connection handler object (same as in index.php) -->
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/connection_to_db.php'; ?>

    <header>
        <!-- Displaying navbar (same as in index.php) -->
        <?php include $_SERVER["DOCUMENT_ROOT"] . '/navbar.php'; ?>
    </header>

    <main>
        <h1 class="text-center my-5">Create a new user</h1>

        <form method="post" class="form">
            <div class="mb-6">
                <label for="name">Your name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name..." required>
            </div>
            <div class="mb-6">
                <label for="email">Your email</label>
                <input type="email" name="email" id="email" placeholder="name@flowbite.com" required>
            </div>
            <div class="mb-6">
                <label for="password">Your password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password..." required>
            </div>
            <div class="mb-6">
                <label for="image">Your image</label>
                <input type="text" name="image" id="image" placeholder="Enter your pfp URL address..." required>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>
    </main>

    <footer>

    </footer>

    <script src="/src/js/bootstrap.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>

</html>