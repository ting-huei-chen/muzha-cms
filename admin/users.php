<?php include 'defines.php';
$connection = mysqli_connect($sever, $user, $pass, $database); ?>
<h2>Users</h2>
<div>
    <?php if (isset($_GET['action'])) : ?>
        <a href="index.php?dashboard=users" class="text-btn">
            << Go Back</a>
            <?php else : ?>
                <a href="index.php?dashboard=users&action=add" class="btn btn-theme btn-md">Add Users</a>
            <?php endif; ?>
</div>
<section>
    <?php

    // $_SERVER['REQUEST_METHOD'] == 'POST'

    if (isset($_GET['action'])) :
        if ($_GET['action'] == "add") {
            echo '<h3>Add a User</h3>';
        } elseif ($_GET['action'] == "edit") {
            echo '<h3>Edit User</h3>';
        } elseif ($_GET['action'] == "delete") {
            echo '<h3>Delte User</h3>';
        }

        // if post: execute query, if not give form to entery
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 'POST execute query:';
            $username = mysqli_real_escape_string($connection, $_POST['username']);
            $password = hash('whirlpool', mysqli_real_escape_string($connection, $_POST['password']));
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $privilege = mysqli_real_escape_string($connection, $_POST['privilege']);

            if ($_GET['action'] == "add") {
                $query = "INSERT INTO `users`( `username`, `password`, `email` ,`privilege`) VALUES ('$username','$password','$email','$privilege')";
                $sql = mysqli_query($connection, $query);
                echo '<small class="msg">The user has been created!</small>';
            } elseif ($_GET['action'] == "edit") {
                $id = $_GET['id'];
                $query = "UPDATE `users` SET `username`='$username',`email`='$email',`password`='$password',`privilege`='$privilege' WHERE `id`= '$id'";
                $sql = mysqli_query($connection, $query);
                echo '<small class="msg">The changes have been saved!</small>';
            }
            // elseif ($_GET['action'] == "delete") {
            //     $id = $_GET['id'];
            //     $sql = "DELETE FROM users WHERE id='$id'";
            //     $sql = mysqli_query($connection, $query);
            //     echo '<small class="msg">The post has been deleted!</small>';
            // }
            echo '<a href="index.php?dashboard=users" class="btn btn-primary">User List</a>';
        } elseif ($_GET['action'] == "delete") {
            $id = $_GET['id'];
            $query = "DELETE FROM users WHERE id='$id'";
            $sql = mysqli_query($connection, $query);
            echo '<small class="msg">The user has been deleted!</small>';
            echo '<a href="index.php?dashboard=users" class="btn btn-primary">User List</a>';
        } else {
            $prefill_username = '';
            $prefill_email = '';
            $prefill_pass = '*******';
            $id = '';
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
            }
            if ($_GET['action'] == "edit") {
                $id = $_GET['id'];
                $query = "SELECT * FROM `users` WHERE id = $id";
                $sql = mysqli_query($connection, $query);
                $row = mysqli_fetch_array($sql);
                $prefill_username = $row['username'];
                $prefill_email = $row['email'];
            }
            echo '<form action="index.php?dashboard=users&action=' . $_GET['action'] . '&id=' . $id . '" method="post">
            <label for="username">username</label>
            <input type="text" name="username" id="username" value="' . $prefill_username . '" required>
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="' . $prefill_email . '" required>
            <label for="privilege">Role</label>
        <select name="privilege" id="privilege" required>
            <option value="admin">Admin</option>
            <option value="editor">Editor</option>
        </select>
            <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <label for="confirmpass">Confrim Password</label>
        <input type="password" name="confirmpass" id="confirmpass" required><small class="danger hidden" id="notice"> <i class="fa-solid fa-circle-exclamation"></i> Please enter same password</small>
            <div class="row"><input type="submit" value="Submit" class="btn btn-outlined btn-md"><div id="loading"></div></div>
        </form>';
        }
    ?>


    <?php else : ?>
        <!-- Post List -->
        <table>
            <thead>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Edit/Delete</th>
            </thead>

            <?php
            $query = "SELECT `id`,`username`, `email`, `privilege` FROM `users` ";
            $sql = mysqli_query($connection, $query);
            while ($row = mysqli_fetch_array($sql)) {
                echo '<tr id= "' . $row['id'] . '">';
                echo '<td>' . $row['username'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['privilege'] . '</td>';
                echo '<td><a href="index.php?dashboard=users&action=edit&id=' . $row['id'] . '" class="btn btn-sm btn-outlined">Edit</a><a href="index.php?dashboard=users&action=delete&id=' . $row['id'] . '" class="btn btn-sm text-btn btn-danger">Delete</a></td>';
                echo '</tr>';
            }

            ?>
        </table>


    <?php endif; ?>
</section>