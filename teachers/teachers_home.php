<?php
require_once '../includes/header.php';
require_once '../includes/database.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true ||  !isset($_SESSION['employee_no'])) {
  header("location: ../login.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if ($course_entry) {
    $degree = $_POST['degree'];
    $branch = $_POST['branch'];
    $sub_code = $_POST['sub_code'];
    $sub_name = $_POST['sub_name'];
    $credits = $_POST['credits'];
    $course_type = $_POST['course_type'];
    $distribution = $_POST['distribution'];
    $err = "";
    if (empty(trim($_POST['sub_code'])) || empty(trim($_POST['distribution']))) {
      $err = "Please fill all the fields";
    }


    if (empty($err)) {
      $sql = "SELECT sub_code FROM courses WHERE sub_code = ?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./teachers/teachers_home.php?error=sqlerror");
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "s", $sub_code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $rowCount = mysqli_stmt_num_rows($stmt);

        if ($rowCount > 0) {
          // header("Location: ./teachers_home.php?error=subject_already_taken");
          echo '<script language="javascript">';
          echo 'alert("Subject code already taken"); location.href="./teachers/teachers_home.php"';
          echo '</script>';
          exit();
        } else {
          $sql = "INSERT INTO courses (employee_id, degree, branch, sub_code, subject_name,credits,course_type,distribution,semester) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            // header("Location: ../teachers_home.php?error=sqlerror");
            echo '<script language="javascript">';
            echo 'alert("SQL error"); location.href="./teachers/teachers_home.php"';
            echo '</script>';
            exit();
          } else {
            mysqli_stmt_bind_param($stmt, "sssssssss", $_SESSION['id'], $degree, $branch, $sub_code, $sub_name, $credits, $course_type, $distribution, $curr_sem);
            mysqli_stmt_execute($stmt);
            echo '<script language="javascript">';
            echo 'alert("Course entered successfully"); location.href="./teachers/teachers_home.php"';
            echo '</script>';
            exit();
          }
        }
      }
    }
  } else if ($grade_entry) {
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
</div>

<?php if ($course_entry) : ?>
  <div class="container">
    <div class="row">
      <h2><i>***Course entry is now open</i></h2>
      <form action="" method="post">
        <h3 class="text-danger">Enter a Course for this semester</h3>
        <div class="form-row">
          <div class="form-group col-md-12"> <label class="font-weight-bold" for="inputName">Degree</label> <input type="text" class="form-control" name="degree" placeholder="Enter degree - btech,mtech,mca,phd" required=""> </div>
          <div class="form-group col-md-12"> <label class="font-weight-bold" for="inputName">Branch</label> <input type="text" class="form-control" name="branch" placeholder="Enter branch" required=""> </div>
          <div class="form-group col-md-12"> <label class="font-weight-bold" for="inputName">Subject Code</label> <input type="text" class="form-control" name="sub_code" placeholder="Enter subject code" required=""> </div>
          <div class="form-group col-md-12"> <label class="font-weight-bold" for="inputName">Subject Name</label> <input type="text" class="form-control" name="sub_name" placeholder="Enter subject name" required=""> </div>
          <div class="form-group col-md-12"> <label class="font-weight-bold" for="inputName">Course type</label> <input type="text" class="form-control" name="course_type" placeholder="Type of course - Thoery or Practical" required=""> </div>
          <div class="form-group col-md-12"> <label class="font-weight-bold" for="inputName">Credits</label> <input type="number" class="form-control" name="credits" placeholder="No. of Credits of the course" required=""> </div>
          <div class="form-group col-md-12"> <label class="font-weight-bold" for="inputName">Marks Distribution <i>(Comma seperated in format "endsem,midsem,extras" Eg. 60,20,20)</i></label> <input type="text" class="form-control" name="distribution" placeholder="Enter marks distribution" required=""> </div>
        </div>
        <div class="mt-2 mb-3"> <button class="btn btn-primary full-width" type="submit">Submit</button> </div>
      </form>
    </div>

  </div>

<?php endif; ?>

<?php if ($grade_entry) : ?>
  <div class="container">
    <h2><i>***Marks entry is now open</i></h2>
    <table>

      <tbody>
        <?php
      $id = $_SESSION['id'];
      $sql =  mysqli_query($conn, "SELECT * FROM courses WHERE employee_id = $id AND semester = $curr_sem");
      while ($row = mysqli_fetch_assoc($sql)) {
        echo "<tr><td><a href='./teachers/marks_entry.php?id=" . $row['course_id'] . "'>" . $row['subject_name'] . " - " . $row['sub_code'] . "</a></td></tr>";
      }
      ?>
    </tbody>
  </table>
  </div>

<?php endif; ?>

<br>
<br>
<br>
<div class="container">
  <h3>Current Semester Courses</h3>
  <br>
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Sub Code</th>
        <th scope="col">Subject Name</th>
        <th scope="col">Subject Type</th>
        <th scope="col">Credits</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $id = $_SESSION['id'];
      $sql =  mysqli_query($conn, "SELECT * FROM courses WHERE employee_id = $id AND semester = $curr_sem");
      while ($row = mysqli_fetch_assoc($sql)) {
        echo "<tr>
                <th scope='row'>{$row['sub_code']}</th>
                <td>{$row['subject_name']}</td>
                <td>{$row['course_type']}</td>
                <td>{$row['credits']}</td>
            </tr>";
      }
      ?>
      <!-- <tr>
        <td scope="row">EC15101</td>
        <td>VLSI Design</td>
        <td>3</td>
        <td>3</td>
      </tr>
      <tr>
        <th scope="row">EC15101</th>
        <td>VLSI Design</td>
        <td>3</td>
        <td>3</td>
      </tr>
      <tr>
        <th scope="row">EC15101</th>
        <td>VLSI Design</td>
        <td>3</td>
        <td>3</td>
      </tr> -->
    </tbody>
  </table>
</div>
<br>
<br>



<?php
require_once '../includes/footer.php';
?>