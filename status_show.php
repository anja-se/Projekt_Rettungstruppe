<?php
include 'userDB.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    updateStatus($_GET["user"], $_POST["module"], $_POST["status"]);
}
?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>Überblick</h1>

        <!-- create a table with courses and students from database -->
        <table border="1">
            <tr>
                <th>Code</th><th>Modul</th><th>in Bearbeitung</th><th>abgeschlossen</th>
            </tr>
            <?php
            $courses = getCourses();

            while ($ergebnis_zeile = $courses->fetch_assoc()) {

                $current_students = array();
                $passed_students = array();

                $get_current_students = getStudents("in progress", $ergebnis_zeile["courseID"]);

                $get_passed_students = getStudents("completed", $ergebnis_zeile["courseID"]);

                while ($student = $get_current_students->fetch_assoc()) {
                    $current_students[] = $student["firstname"] . " " . $student["lastname"];
                }

                while ($student = $get_passed_students->fetch_assoc()) {
                    $passed_students[] = $student["firstname"] . " " . $student["lastname"];
                }

                echo "<tr><td>" . $ergebnis_zeile["courseNum"] . "</td>";
                echo "<td>" . $ergebnis_zeile["courseName"] . "</td>";
                echo "<td>" . implode(", ", $current_students) . "</td>";
                echo "<td>" . implode(", ", $passed_students) . "</td></tr>";
            }
            ?>

        </table>
        <br>
        <br>
        <!-- The following section creates a from to update user's status of a course-->
        <h3>Mein Status:</h3>
        <form action="" method="POST">
            <select name="module" id="module">
                <?php
                //create select menue over all courses
                $courses = getCourses();

                while ($zeile = $courses->fetch_assoc()) {
                    echo "<option value = '" . $zeile["courseID"] . "'>" . $zeile["courseNum"] . " - " . $zeile["courseName"] . "</option>";
                }
                ?>
            </select>
            <!-- select course status -->
            <select name="status", id="status">
                <option id="not_started" value="not started">nicht begonnen</option>
                <option id="started" value="in progress">in Bearbeitung</option>
                <option id="completed" value="completed">abgeschlossen</option>
            </select>
            <input type="submit" name="update" id="update" value="speichern">
        </form>
        <br>
        <a href="index.php">logout</a>
    </body>
</html>