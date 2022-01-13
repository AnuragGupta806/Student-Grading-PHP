<?php
require_once '../includes/header.php';
require_once '../includes/database.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true ||  !isset($_SESSION['employee_no'])) {
    header("location: ../login.php");
}

$marks_array = "";
$degree = "";
$branch = "";
$course_id = "";
$subject = "";
$sub_code = "";

// if ($_SERVER['REQUEST_METHOD'] == "GET") {
$course_id = $_GET['id'];
$sql = "SELECT subject_name,sub_code,distribution,degree,branch FROM courses WHERE course_id = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ./teachers/marks_entry.php?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $course_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $rowCount = mysqli_stmt_num_rows($stmt);
    if ($rowCount > 0) {
        mysqli_stmt_bind_result($stmt, $subject, $sub_code, $distribution, $deg, $bran);
        if (mysqli_stmt_fetch($stmt)) {
            $marks_array = explode(",", $distribution);
            $degree = $deg;
            $branch = $bran;
        }
    } else {
        echo '<script language="javascript">';
        echo 'alert("Course not found"); location.href="./teachers/marks_entry.php"';
        echo '</script>';
        exit();
    }
}
// }
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $student_id = $_POST['submit'];
    $all_marks = [];
    foreach ($marks_array as $i => $mark) {
        $str = "M" . $i;
        array_push($all_marks, $_POST[$str]);
    }
    $total_marks = array_sum($all_marks);
    // echo '<script language="javascript">';
    // echo 'alert('.$total_marks.'); location.href="./teachers/marks_entry.php?id=$course_id"';
    // echo '</script>';
    $sql = "SELECT id FROM students_marks WHERE student_id = ? AND course_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: marks_entry.php?id=$course_id&error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $student_id, $course_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $rowCount = mysqli_stmt_num_rows($stmt);

        if ($rowCount > 0) {
            header("Location: marks_entry.php?id=$course_id&error=already_taken");
            exit();
        } else {
            $sql = "INSERT INTO students_marks (student_id, course_id, marks) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: marks_entry.php?id=$course_id&error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "sss", $student_id, $course_id, $total_marks);
                mysqli_stmt_execute($stmt);
                header("Location: marks_entry.php?id=$course_id&success=entered");
                exit();
            }
        }
    }
}


?>
<div class="container mt-2 p-2">
    <div class="row justify-content-center">
        <div class="col-xs-12 center-block text-center">
            <h2>Hello! <?php echo $_SESSION['username'] . " - " . $_SESSION['employee_no']
                        ?>
                </h3>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xs-12 center-block text-center">
            <h3>Current Semester : <?php echo  $curr_sem;
                                    ?>
            </h3>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xs-12 center-block text-center">
            <h3>Subject : <?php echo  $subject . " - " . $sub_code;
                            ?>
            </h3>
        </div>
    </div>
</div>
<div class="conatiner">
    <div class="row justify-content-center">
        <div class="col-auto">
            <table class="table table-responsive">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Student Name</th>
                        <th scope="col">Student Reg. No.</th>
                        <?php
                        foreach ($marks_array as $i => $mark) {
                            echo "<th scope='col' class='text-center'>" . $mark . "</th>";
                        }
                        ?>
                        <!-- <th scope="col">Total</th> -->
                        <th scope="col">Submit</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $sql =  mysqli_query($conn, "SELECT id,username,regno FROM student WHERE degree = '$degree' AND branch = '$branch' AND NOT EXISTS (SELECT * from students_marks WHERE students_marks.student_id = student.id AND students_marks.course_id = $course_id)");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        echo '<form action="" method="post">';
                        echo "<tr>
                        <th scope='row'>{$row['username']}</th>
                        <td>{$row['regno']}</td>";
                        foreach ($marks_array as $i => $mark) {
                            echo "<td><input type='number' name=M" . $i . " max=" . $mark . " required=''></td>";
                        };
                        echo "<td><button class='btn btn-primary full-width' type='submit' name='submit' value='{$row['id']}'>Submit</button></td>";
                        echo '</form>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
require_once '../includes/footer.php';
?>