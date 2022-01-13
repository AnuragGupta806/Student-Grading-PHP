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
?>
<?php
for ($i = 1; $i <= $curr_sem; $i++) {
  if ($i == $curr_sem && ($grade_entry || $course_entry))
    continue;
?>
  <br>
  <br>
  <div class="container">
    <div class="row justify-content-center">
      <h3>Semester : <?php echo $i ?></h3>
    </div>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Subject Code</th>
          <th scope="col">Subject Name</th>
          <th scope="col">Credits</th>
          <th scope="col">Grade</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql =  mysqli_query($conn, "SELECT * FROM courses WHERE degree = '$stu_degree' AND branch = '$stu_branch' AND semester = $i");
        while ($row = mysqli_fetch_assoc($sql)) {
          $sub_id = $row['course_id'];
          echo "<tr>
                <th scope='row'>{$row['sub_code']}</th>
                <td>{$row['subject_name']}</td>
                <td>{$row['credits']}</td>";
          $res1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT marks FROM students_marks WHERE student_id =$id AND course_id = $sub_id"));
          if ($res1['marks'] >= 85)
            echo "<td>A+</td>";
          else if ($res1['marks'] >= 80)
            echo "<td>A</td>";
          else if ($res1['marks'] >= 75)
            echo "<td>B+</td>";
          else if ($res1['marks'] >= 70)
            echo "<td>B</td>";
          else if ($res1['marks'] >= 60)
            echo "<td>C</td>";
          else if ($res1['marks'] >= 50)
            echo "<td>D</td>";
          else if ($res1['marks'] >= 40)
            echo "<td>E</td>";
          else if ($res1['marks'] >= 30)
            echo "<td>F</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
    <div class="row justify-content-left">
      <?php
      $res1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT spi FROM student_spi WHERE student_id =$id AND semester = $i"));
      echo "<h3>SPI :" . $res1['spi'] . "</h3>";
      ?>
    </div>
  </div>
<?php
}
?>

<?php
require_once '../includes/footer.php';
?>