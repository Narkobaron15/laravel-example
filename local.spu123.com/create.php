<?php
    $name="";
    $email="";
    $image="";
    $password="";

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        if(isset($_POST['name']))
            $name=$_POST['name'];
        if(isset($_POST['email']))
            $email=$_POST['email'];
        if(isset($_POST['image']))
            $image=$_POST['image'];
        if(isset($_POST['password']))
            $password=$_POST['password'];
        if(!empty($name) && !empty($image) && !empty($email) && !empty($password)) {
            include $_SERVER["DOCUMENT_ROOT"] . '/connection_to_db.php';
            $sql = "INSERT INTO users(name, email, image, password) VALUES(?, ?, ?, ?);";
            if(isset($dbh)) {
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$name, $email, $image, $password]);
                header('Location: /');
                exit;
            }
        }
    }
?>

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
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/connection_to_db.php'; ?>

    <header>
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
            <button type="submit" class="submitbtn">Submit</button>
        </form>
    </main>

    <footer>

    </footer>

    <script src="/src/js/bootstrap.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>

</html>