<?php
    // imports DB connection handler object
    include $_SERVER["DOCUMENT_ROOT"] . '/connection_to_db.php';
    // visibility of the handler
    global $dbh;

    // handling HTTP GET Request
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        // Checking if id is valid. If not, return to main page
        // If is valid, filling in the fields
        if (is_numeric($_GET['id'])){
            $stmt = $dbh->prepare('SELECT * FROM users WHERE id=?');
            $stmt->execute([$_GET['id']]);
            $result = $stmt->fetch();

            if ($stmt->rowCount() < 1) {
                header('Location: /');
            }
            else {
//                $id = $result["id"];
                $name = $result["name"];
                $email = $result["email"];
                $image = $result["image"];
                $password = $result["password"];
            }
        }
        else {
            header('Location: /');
        }
    }

    // handling HTTP POST Request
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = "";
        $name = "";
        $email = "";
        $image = "";
        $password = "";

        // extracting posted values if they are set
        if (isset($_GET['id']))
            $id = $_GET['id'];
        if (isset($_POST['name']))
            $name = $_POST['name'];
        if (isset($_POST['email']))
            $email = $_POST['email'];
        if (isset($_POST['image']))
            $image = $_POST['image'];
        if (isset($_POST['password']))
            $password = $_POST['password'];

        // If all of them are filled,
        if (!empty($name) && !empty($image) && !empty($email) && !empty($password)) {
            //  the script executes sql request
            $sql = "
                UPDATE users
                SET name = ?, image = ?, email = ?, password = ?
                WHERE id = ? 
            ";

            include $_SERVER["DOCUMENT_ROOT"] . '/execsql.php';
            ExecuteSql($dbh, $sql, [$name, $image, $email, $password, $id]);
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

    <header>
        <!-- Displaying navbar (same as in index.php) -->
        <?php include $_SERVER["DOCUMENT_ROOT"] . '/navbar.php'; ?>
    </header>

    <main>
        <h1 class="text-center my-5">Create a new user</h1>
<?php echo
"
        <form method='post' class='form'>
            <div class='mb-6'>
                <label for='name'>Your name</label>
                <input type='text' name='name' id='name' placeholder='Enter your name...' value='$name' required>
            </div>
            <div class='mb-6'>
                <label for='email'>Your email</label>
                <input type='email' name='email' id='email' placeholder='name@flowbite.com' value='$email' required>
            </div>
            <div class='mb-6'>
                <label for='password'>Your password</label>
                <input type='password' name='password' id='password' placeholder='Enter your password...' value='$password' required>
            </div>
            <div class='mb-6'>
                <label for='image'>Your image</label>
                <input type='text' name='image' id='image' placeholder='Enter your pfp URL address...' value='$image' required>
            </div>
            <button type='submit' class='btn'>Submit</button>
        </form>
"?>
    </main>

    <footer>

    </footer>

    <script src="/src/js/bootstrap.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>

</html>