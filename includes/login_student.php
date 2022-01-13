<?php
//This script will handle login
session_start();

// check if the user is already logged in
if (isset($_SESSION['regno'])) {
    header("location: ../students/students_home.php");
    exit;
}
require_once "database.php";

$regno = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['regno'])) || empty(trim($_POST['password']))) {
        $err = "Please enter registration No. + password";
    } else {
        $regno = trim($_POST['regno']);
        $password = trim($_POST['password']);
    }


    if (empty($err)) {
        $sql = "SELECT id,username, password FROM student WHERE regno = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $regno;


        // Try to execute this statement
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $name, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {
                        // this means the password is corrct. Allow user to login
                        session_start();
                        $_SESSION["username"] = $name;
                        $_SESSION["regno"] = $regno;
                        $_SESSION["id"] = $id;
                        $_SESSION["loggedin"] = true;

                        //Redirect user to welcome page
                        header("location: ../students/students_home.php");
                    }
                }
            }
        }
    }
}
