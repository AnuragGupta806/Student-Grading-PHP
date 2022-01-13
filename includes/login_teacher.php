<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['employee_no']))
{
    header("location: ../teachers/teachers_home.php");
    exit;
}
require_once "database.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['employee_no'])) || empty(trim($_POST['password_t'])))
    {
        $err = "Please enter employee No. + password";
    }
    else{
        $username = trim($_POST['employee_no']);
        $password = trim($_POST['password_t']);
    }


if(empty($err))
{
    $sql = "SELECT id,username, employee_no, password FROM teacher WHERE employee_no = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    
    
    // Try to execute this statement
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    mysqli_stmt_bind_result($stmt, $id,$name, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // this means the password is corrct. Allow user to login
                            session_start();
                            $_SESSION["username"] = $name;
                            $_SESSION["employee_no"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;

                            //Redirect user to welcome page
                            header("location: ../teachers/teachers_home.php");
                            
                        }
                    }

                }

    }
}    


}
