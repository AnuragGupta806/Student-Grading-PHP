<?php
require_once 'includes/database.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_sem = $curr_sem;
    $new_grade = $grade_entry;
    $new_course = $course_entry;
    if (!empty($_POST['currsem'])) {
        if ($_POST['currsem'] >= 1 && $_POST['currsem'] <= 8)
            $new_sem = $_POST['currsem'];
    }
    if (!empty($_POST['grade_enter'])) {
        if ($_POST['grade_enter'] == 1)
            $new_grade = $_POST['grade_enter'];
        else
            $new_grade = 0;
    }
    if (!empty($_POST['course_enter'])) {
        if ($_POST['course_enter'] == 1)
            $new_course = $_POST['course_enter'];
        else
            $new_course = 0;
    }
    $sql = "UPDATE admin SET curr_sem = ?, grade_entry = ?, course_entry = ? WHERE id = 1";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./admin_page.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "sss", $new_sem, $new_grade, $new_course);
        mysqli_stmt_execute($stmt);
        header("Location: ./admin_page.php?succes=updated");
        // header("Location: ./index.php);
    }
}
?>
<?php
require_once 'includes/header.php';
?>
<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-6">
            <h2>
                Current semester : <?php echo $curr_sem;
                                    ?>
            </h2>
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group col-md-12"> <label for="inputName">Change semester</label> <input type="number" class="form-control" name="currsem" placeholder="Enter semester" required=""> </div>
                </div>
                <div class="mt-2 mb-3"> <button class="btn btn-primary full-width" type="submit">Submit</button> </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <h2>
                Course entry : <?php if ($course_entry) {
                                    echo "Open";
                                } else {
                                    echo "Close";
                                }
                                ?>
            </h2>
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group col-md-12"> <label for="inputName">Toggle course entry</label> <input type="number" class="form-control" name="course_enter" placeholder="Enter semester" required=""> </div>
                </div>
                <div class="mt-2 mb-3"> <button class="btn btn-primary full-width" type="submit">Submit</button> </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <h2>
                Grade entry : <?php if ($grade_entry) {
                                    echo "Open";
                                } else {
                                    echo "Close";
                                }
                                ?>
            </h2>
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group col-md-12"> <label for="inputName">Toggle grade entry</label> <input type="number" class="form-control" name="grade_enter" placeholder="Enter semester" required=""> </div>
                </div>
                <div class="mt-2 mb-3"> <button class="btn btn-primary full-width" type="submit">Submit</button> </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <h3>Students without marks</h3>
    <br>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Student</th>
                <th scope="col">Professor</th>
                <th scope="col">Subject</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT t1.course_id,t1.subject_name,t1.sub_code,t1.employee_id,t2.id FROM student t2 INNER JOIN courses t1 ON t1.semester = $curr_sem WHERE NOT EXISTS(SELECT 1 from students_marks t3 WHERE (t3.student_id = t2.id or t2.id is NULL) AND t3.course_id = t1.course_id)";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $stu_id = $row['id'];
                $proff_id = $row['employee_id'];
                $res1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM student WHERE id =$stu_id"));
                $res2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM teacher WHERE id =$proff_id"));
                echo "<tr>
                <th scope='row'>{$res1['username']}</th>
                <td>{$res2['username']}</td>
                <td>{$row['subject_name']} - {$row['sub_code']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<br>
<br>
<br>
<?php
require_once 'includes/footer.php';
?>