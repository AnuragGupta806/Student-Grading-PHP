<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require 'database.php';

    $username = trim($_POST["username_t"]);
    $employee_no = $_POST['employee_no'];   
    $password = $_POST['password_t'];
    $confirmPass = $_POST['confirmpassword_t'];

    if (empty($username) || empty($password) || empty($confirmPass) || empty($employee_no)) {
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
        $sql = "SELECT username FROM teacher WHERE username = ?";
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
                $sql = "INSERT INTO teacher (username, password, employee_no) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../register.php?error=sqlerror");
                    exit();
                } else {
                    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $hashedPass, $employee_no);
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
?>