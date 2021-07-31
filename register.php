<?php
$username = "";
$firstname = "";
$lastname = "";

include 'userDB.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];

    if ($_POST["password"] === $_POST["password_rep"]) {
        createUser($username, $firstname, $lastname, $_POST["password"]);
    } else {
        echo "<p>Die eingegebenen Passwörter stimmen nicht überein.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h3>Register</h3>
        <form action="" method="POST">
            <label for="username">Username:</label><input type="text" name="username" id="username" value= "<?= $username ?>"><br>
            <label for="firstname">First name: </label><input type="text" name="firstname" id="firstname"><br>
            <label for="lastname">Last Name: </label><input type="text" name="lastname" id="lastname"><br>
            <label for="password">Password: </label><input type="password" name="password" id="password"><br>
            <label for="password_rep">Repeat your password: </label><input type="password" name="password_rep" id="password_rep"><br><br>
            <input type="submit" value="register">
        </form><br><br>
        Already registered? Please <a href = "index.php">login.</a>    
    </body>
</html>