<?php
require_once 'includes/header.php';
session_start();

// check if the user is already logged in
if(isset($_SESSION['regno']))
{
    header("location: ./students/students_home.php");
    exit;
}
if(isset($_SESSION['employee_no']))
{
    header("location: ./teachers/teachers_home.php");
    exit;
}

?>


<div class="container">
    <div class="card signup_v4 mb-30">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation"> <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Student Login</a> </li>
                <li class="nav-item" role="presentation"> <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Teacher Login</a> </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                    <h4 class="text-center mt-4 mb-4" style="text-transform: uppercase;">For Students</h4>
                    <form action="./includes/login_student.php" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-12"> <label for="inputName">Registration Number</label> <input type="text" class="form-control" name="regno" placeholder="Enter your registration number" required=""> </div>
                            <div class="form-group col-md-12"> <label for="inputName">Password</label> <input type="password" class="form-control" name="password" placeholder="Enter your password" required=""> </div>
                        </div>
                        <div class="mt-2 mb-3"> <button class="btn btn-primary full-width" type="submit">Login</button> </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                    <h4 class="text-center mt-4 mb-4" style="text-transform: uppercase;">For Teachers</h4>
                    <form action="./includes/login_teacher.php" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-12"> <label for="inputName">Employment Number</label> <input type="text" class="form-control" name="employee_no" placeholder="Enter your employment no." required=""> </div>
                            <div class="form-group col-md-12"> <label for="inputName">Your Password</label> <input type="password" class="form-control" name="password_t" placeholder="Enter your password" required=""> </div>
                        </div>
                        <!-- <div class="form-row">
                        </div> -->
                        <hr class="mt-3 mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="custom-checkbox d-block"> <a href="./register.php" class="nav-link-inline font-size-sm">Don't Have a account? Register</a> </div> <button class="btn btn-primary mt-3 mt-sm-0" type="submit">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'includes/footer.php';
?>