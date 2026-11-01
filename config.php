<?php
// Change this before putting the site online. Used to log in to admin.php (username: admin).
define('ADMIN_PASSWORD', 'change-me');

// 30-minute booking slots offered each working day.
$SLOTS = ["08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","14:00","14:30","15:00","15:30","16:00","16:30"];

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("localhost","root","","policydesk");
if($conn->connect_error){
  error_log("DB Error: ".$conn->connect_error);
  die(json_encode(['success'=>false,'message'=>'Database connection failed. Is MySQL running?']));
}
$conn->set_charset("utf8mb4");

// Returns the date if it's a valid Y-m-d string, otherwise false.
function valid_date($d){
  $dt = DateTime::createFromFormat('Y-m-d', $d);
  return ($dt && $dt->format('Y-m-d') === $d) ? $dt : false;
}
?>
