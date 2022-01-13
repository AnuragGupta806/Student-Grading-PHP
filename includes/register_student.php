<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require 'database.php';

    $username = trim($_POST["username"]);
    $regno = $_POST['regno'];
    $branch = $_POST['branch'];
    $degree = $_POST['degree'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmpassword'];
    echo "1. " . $username . $regno . $branch . $degree . $password;

    if (empty($username) || empty($password) || empty($confirmPass) || empty($regno) || empty($branch) || empty($degree)) {
        header("Location: ../register.php?error=emptyfields&username=".$username);
        exit();
    } elseif (!preg_match("/^[a-zA-Z0-9]*/", $username)) {
        header("Location: ../register.php?error=invalidusername&username=".$username);
        exit();
    } elseif($password !== $confirmPass) {
        header("Location: ../register.php?error=passwordsdonotmatch&username=".$username);
        exit();
    }

    else {
        echo "reched here";
        $sql = "SELECT username FROM student WHERE username = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../register.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $rowCount = mysqli_stmt_num_rows($stmt);

            if ($rowCount > 0) {
                header("Location: ../register.php?error=usernametaken");
                exit();
            } else {
                $sql = "INSERT INTO student (username, password, regno, degree, branch) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../register.php?error=sqlerror");
                    exit();
                } else {
                    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sssss", $username, $hashedPass, $regno, $degree, $branch);
                    mysqli_stmt_execute($stmt);
                        header("Location: ../register.php?succes=registered");
                        exit();
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
else{
    echo "false statement";
}
?>