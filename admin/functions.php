<?php
// login funcitons
function checkLogin($connection)
{
    if (isset($_COOKIE['login'])) {
        $user = $_COOKIE['login'];
        $query = "SELECT `id`, `username`, `email`, `password`, `privilege` FROM `users` WHERE `username` = '$user'";
        $sql = mysqli_query($connection, $query);
        if ($sql == false) {
            echo $user . '<br>';
            header("Location: login.php");
            die();
        }
    } else {
        header("Location: login.php");
        die();
    }
}

setlocale(LC_ALL, 'en_US.UTF8');
function toAscii($str, $replace = array(), $delimiter = '-')
{
    if (!empty($replace)) {
        $str = str_replace((array)$replace, ' ', $str);
    }

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    return $clean;
}

// enque style sheet
