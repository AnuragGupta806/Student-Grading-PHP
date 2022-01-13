<?php
/*
This file contains database configuration assuming you are running mysql using user "root" and password ""
*/

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'academics');

$curr_sem = 1;
$grade_entry = 0;
$course_entry = 0;
// Try connecting to the Database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//Check the connection
if($conn == false){
    echo "not connected";
    die('Error: Cannot connect');

}

$sql = "SELECT * FROM admin WHERE id = 1";
$result = mysqli_query($conn, $sql) or die("Bad query : $sql");
$row_count = mysqli_num_rows($result);
$curr_sem = 0;
$course_entry = 0;
$grade_entry = 0;
if ($row_count > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $curr_sem = $row['curr_sem'];
        $course_entry = $row['course_entry'];
        $grade_entry = $row['grade_entry'];
    }
} else {
    echo "No results found";
}
?>