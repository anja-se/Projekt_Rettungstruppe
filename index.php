<?php
include 'userDB.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    login();
}
?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h3>Login</h3>
        <form action="" method="POST">
            <label for="username">Username: </label><input type="text" name="username" id="username"><br>
            <label for="password">Password: </label><input type="password" name="password" id="password"><br><br>
            <input type="submit" value="login">
        </form><br><br>
        <p>Have no Account yet? <a href = "register.php">Register here.</a></p>
    </body>
</html>