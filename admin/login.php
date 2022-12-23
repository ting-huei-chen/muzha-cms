<?php
include 'defines.php';
$connection = mysqli_connect($sever, $user, $pass, $database);
ob_start();
if (!$connection && $_SERVER['REQUEST_METHOD'] == 'POST') :
    // die(mysqli_connect_error());  
    include 'connect.php';
    echo 'connect.php linked.';
elseif (!$connection) :
    header("Location: setup.php");
    die();
endif;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muzha - Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body id="login">
    <main>
        <h1>Login</h1>
        <div class="wrap" id="mainWrap">
            <h2>Welcome!</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // echo 'POST: ' . $_POST['username'] . '<br>';
                $userinput = mysqli_real_escape_string($connection, $_POST['username']);
                $query = "SELECT `id`, `username`, `password`, `privilege` FROM `users` WHERE `username` = '$userinput'";
                $sql = mysqli_query($connection, $query);
                if ($sql && $row = mysqli_fetch_array($sql)) {
                    // echo 'sql done<br>';
                    // print_r($row);
                    // echo $row;
                    if (hash('whirlpool', $_POST['password']) == $row['password']) {
                        setcookie("username", $userinput, strtotime("+1 month"));
                        setcookie("login", $userinput, strtotime("+1 month"));
                        setcookie("role", $row['privilege'], strtotime("+1 month"));
                        echo "<p class='msg'>Login successfully.</p>";
                        header("Location: index.php?dashboard=dashboard");
                        die();
                    } else {
                        echo "<p class='msg'>Invalid username or password</p>";
                        echo '<a href="login.php" class="btn btn-primary">Back to Login</a>';
                    }
                } else {
                    echo "<p class='msg'>Invalid username or password</p>";
                    echo '<a href="login.php" class="btn btn-primary">Back to Login</a>';
                }

                exit;
            }
            if ($_GET['logout'] == true) {
                unset($_COOKIE['username']);
                setcookie("username", FALSE, time() - 6000);
                setcookie("login", FALSE, time() - 6000);
                echo "<p class='msg'>Logged out successfully.</p>";
                header("Location: login.php");
                exit;
            }
            if (isset($_COOKIE['username']) && isset($_COOKIE['login'])) {
                echo "<h2>Welcome back, " . $_COOKIE['username'] . "</h2>";
                echo '<a href="index.php" class="bt">Go to dashboard</a>';
                echo "<style>form,#login_h2{display:none;}</style>";
            }

            ?>

            <form action="login.php" method="post">
                <fieldset>
                    <legend>User Name</legend>
                    <label for="user_name">
                        <input type="text" name="username" id="username" required value="<?php if (isset($_COOKIE['username'])) {
                                                                                                echo $_COOKIE['username'];
                                                                                            } ?>">
                    </label>
                </fieldset>
                <fieldset>
                    <legend>Password</legend>
                    <label for="password">
                        <input type="password" name="password" id="password" required>
                    </label>
                </fieldset>
                <input type="submit" value="Log In" class="btn btn-primary">
            </form>
        </div>
    </main>
    <footer>Muzha CMS</footer>
</body>

</html>
<?php ob_end_flush(); ?>