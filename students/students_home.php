<?php
require_once '../includes/database.php';
require_once '../includes/header.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['regno'])) {
  header("location: ../login.php");
}
$regno = $_SESSION['regno'];
$id = $_SESSION['id'];
$stu_branch = $stu_degree = "";
$sql = "SELECT * FROM student WHERE regno = $regno";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
  $stu_branch = $row['branch'];
  $stu_degree = $row['degree'];
}

$grade_points = [
  'A+' => 10,
  'A' => 9,
  'B+' => 8,
  'B' => 7,
  'C' => 6,
  'D' => 5,
  'E' => 4,
  'F' => 3,
];
$spi = "";
$cpi = "";


if (!$grade_entry && !$course_entry && !isset($_SESSION['spi'])) {
  $sql = "SELECT m.marks,m.course_id,c.course_id,c.credits FROM students_marks m JOIN courses c ON (m.course_id = c.course_id AND c.semester = $curr_sem AND m.student_id = $id)";
  $result = mysqli_query($conn, $sql);
  $total_credits = $total_score = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    $grade = "";
    if ($row['marks'] >= 85) {
      $grade = 'A+';
    } else if ($row['marks'] >= 80) {
      $grade = 'A';
    } else if ($row['marks'] >= 75) {
      $grade = 'B+';
    } else if ($row['marks'] >= 70) {
      $grade = 'B';
    } else if ($row['marks'] >= 60) {
      $grade = 'C';
    } else if ($row['marks'] >= 50) {
      $grade = 'D';
    } else if ($row['marks'] >= 40) {
      $grade = 'E';
    } else {
      $grade = 'F';
    }
    $total_credits += $row['credits'];
    $total_score += $grade_points[$grade] * $row['credits'];
  }
  if($total_credits)
  $spi = $total_score / $total_credits;
  $spi = number_format($spi, 2, '.', ',');

  $sql = "SELECT spi FROM student_spi WHERE student_id = ? AND semester = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo '<script language="javascript">';
    echo 'alert("SQL error"); location.href="./students/students_home.php"';
    echo '</script>';
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ss", $id, $curr_sem);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $rowCount = mysqli_stmt_num_rows($stmt);

    if ($rowCount > 0) {
      mysqli_stmt_bind_result($stmt, $spi);
      mysqli_stmt_fetch($stmt);
      echo '<script language="javascript">';
      echo 'alert("Already filled"); location.href="./students/students_home.php"';
      echo '</script>';
      $_SESSION["spi"] = $spi;
      exit();
    } else {
      $sql = "INSERT INTO student_spi (student_id, semester, spi) VALUES (?, ?, ?)";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo '<script language="javascript">';
        echo 'alert("SQL error"); location.href="./students/students_home.php"';
        echo '</script>';
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "sss", $id, $curr_sem, $spi);
        mysqli_stmt_execute($stmt);
        // header("Location: ./students/students_home.php?marks=entered");
        echo '<script language="javascript">';
        echo 'alert("Marks entered successfully"); location.href="./students/students_home.php"';
        echo '</script>';
        $_SESSION["spi"] = $spi;
        exit();
      }
    }
  }
}

$sql = "SELECT spi FROM student_spi WHERE student_id = $id";
$result = mysqli_query($conn, $sql);
$total_spi = $num_sem = 0;
while ($row = mysqli_fetch_assoc($result)) {
  $total_spi += $row['spi'];
  $num_sem++;
}
if ($num_sem)
  $cpi = $total_spi / $num_sem;

?>

<div class="container mt-2 p-2">
  <div class="row justify-content-center">

    <h2>Hello! <?php echo $_SESSION['username'] . " - " . $_SESSION['regno']
                ?>
    </h2>
  </div>
  <div class="row justify-content-center">
    <h3>Current Semester : <?php echo  $curr_sem;
                            ?>
    </h3>
  </div>
  <div class="row justify-content-center">
    <h3>Your Current CPI : <?php echo  $cpi;
                            ?>
    </h3>
  </div>

</div>
<br>
<br>
<div class="container">
  <h3>Academic Summary</h3>
  <br>
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <!-- <th scope="col">#</th> -->
        <th scope="col">Semester</th>
        <th scope="col">SPI</th>
        <th scope="col">Result</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $id = $_SESSION['id'];
      $sql =  mysqli_query($conn, "SELECT * FROM student_spi WHERE student_id = $id ORDER BY semester ASC");
      while ($row = mysqli_fetch_assoc($sql)) {
        echo "<tr>
                <th scope='row'>{$row['semester']}</th>
                <td>{$row['spi']}</td>";
        if ($row['spi'] >= 5)
          echo "<td>Passed</td>";
        else
          echo "<td>Failed</td>";
        echo "</tr>";
      }
      ?>
      <!-- <tr>
        <th scope="row">1</th>
        <td>4.9</td>
        <td>Fail</td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>7.2</td>
        <td>Pass</td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td>8.1</td>
        <td>Pass</td>
      </tr> -->
    </tbody>
  </table>
</div>
<br>
<br>
<div class="container">
  <h3>Current Sem Courses</h3>
  <br>
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <!-- <th scope="col">#</th> -->
        <th scope="col">Sub Code</th>
        <th scope="col">Subject Name</th>
        <th scope="col">Course type</th>
        <th scope="col">Credits</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql =  mysqli_query($conn, "SELECT * FROM courses WHERE degree = '$stu_degree' AND branch = '$stu_branch' AND semester = $curr_sem");
      while ($row = mysqli_fetch_assoc($sql)) {
        echo "<tr>
                <th scope='row'>{$row['sub_code']}</th>
                <td>{$row['subject_name']}</td>
                <td>{$row['course_type']}</td>
                <td>{$row['credits']}</td>
            </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<br>
<br>
<br>
<div class="container">
  <a href="./students/transcript.php"><button type="button" class="btn btn-primary btn-lg btn-block">Get My Transcript</button></a>
</div>
<br>
<br>
<br>
<?php
require_once '../includes/footer.php';
?>