<?php

//connect to database
function connect() {
    $connection = new mysqli("localhost", "root", "", "rettungstrupp");
    if ($connection->connect_error) {
        return null;
    }
    return $connection;
}

//login for existing users
function login() {
    //connect to database
    $connection = connect();

    if ($connection === null) {
        echo "Fehler bei der Verbindung" . mysqli_connect_error();
        exit();
    }

    //check password and redirect to main page
    $result = $connection->query("SELECT username, password FROM user;");
    while ($line = $result->fetch_assoc()) {
        if ($line["username"] == $_POST["username"]) {
            if ($line["password"] == $_POST["password"]) {
                $connection->close();
                header("Location: status_show.php?user=" . $_POST["username"]);
                exit();
            }
        }
    }
    //close database connection
    $connection->close();
}

//add new user to database
function createUser($username, $firstname, $lastname, $password) {
    //connect to database
    $connection = connect();

    if ($connection === null) {
        echo "Fehler bei der Verbindung" . mysqli_connect_error();
        exit();
    }

    //add user to database
    $sql = "INSERT INTO user(username, firstname, lastname, password) VALUES(?,?,?,?);";

    $stmt = $connection->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssss", $username, $firstname, $lastname, $password);
        $stmt->execute();
        $stmt->close();
        $connection->close();
        header("Location: status_show.php?user=" . $username);
        exit();
    } else {
        $connection->close();
        echo "Das hat nicht geklappt.";
    }
}

//update a student's course status
function updateStatus($username, $courseID, $status) {
    //connect to database
    $connection = connect();

    if ($connection === null) {
        echo "Fehler bei der Verbindung" . mysqli_connect_error();
        exit();
    }

    //get userID (for database use)
    $result = $connection->query("SELECT userID FROM user WHERE username = '$username'");
    $line = $result->fetch_assoc();
    $userID = $line["userID"];

    //check if course status is set
    //if so, update status
    $sql_check = "SELECT userID FROM rel_status WHERE userID = '$userID' AND courseID = '$courseID'";
    $result = $connection->query($sql_check);

    if ($result->num_rows > 0) {
        $sql_update = "UPDATE rel_status SET status = '$status' WHERE courseID = '$courseID' AND userID = '$userID'";
        $connection->query($sql_update);
        $connection->close();
    }

    //else set status
    else {
        $sql_insert = "INSERT INTO rel_status(courseID, userID, status) VALUES ('" . $courseID . "','" . $userID . "','" . $status . "')";
        if ($connection->query($sql_insert)) {
            echo "insert successfull";
        } else {
            echo "insert failed";
        }
        $connection->close();
    }
}

//get courses 
function getCourses() {
    $connection = connect();

    if ($connection === null) {
        echo "Fehler bei der Verbindung" . mysqli_connect_error();
        exit();
    }

    $courses = $connection->query("SELECT courseID, courseNum, courseName FROM courses;");
    $connection->close();
    return $courses;
}

//get students filtered by course status
function getStudents($status, $courseID) {
    $connection = connect();

    if ($connection === null) {
        echo "Fehler bei der Verbindung" . mysqli_connect_error();
        exit();
    }

    $students = $connection->query("SELECT firstname, lastname FROM user WHERE userID IN (SELECT userID FROM rel_status WHERE courseID = '" . $courseID . "' AND status ='" . $status . "')");
    $connection->close();
    return $students;
}