<?php
// 1. Create DB
$c = new mysqli("localhost","root","","");
if($c->connect_error) die("Start MySQL in XAMPP");
$c->query("CREATE DATABASE IF NOT EXISTS policydesk");
$c->select_db("policydesk");
$c->query("CREATE TABLE IF NOT EXISTS appointment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE, time_slot VARCHAR(20), meeting_type VARCHAR(30),
  name VARCHAR(100), phone VARCHAR(30), email VARCHAR(100),
  service VARCHAR(100), message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(date, time_slot)
)");

// 2. Fix config.php - NO space before <?php
file_put_contents("config.php",'<?php
$conn = new mysqli("localhost","root","","policydesk");
if($conn->connect_error){ die(json_encode(["success"=>false,"message"=>"DB Error: ".$conn->connect_error])); }
');

// 3. Fix api-available-slots.php
file_put_contents("api-available-slots.php",'<?php
header("Content-Type: application/json");
require "config.php";
$date = $_GET["date"] ?? date("Y-m-d");
$booked = [];
$q = $conn->query("SELECT time_slot FROM appointment WHERE date=\"$date\"");
if($q){ while($r=$q->fetch_assoc()){ $booked[]=$r["time_slot"]; } }
$all = ["08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","14:00","14:30","15:00","15:30","16:00","16:30"];
$free = [];
foreach($all as $t){ if(!in_array($t,$booked)){ $free[] = $t." (30 min)"; } }
echo json_encode($free);
');

// 4. Fix api-book-appointment.php
file_put_contents("api-book-appointment.php",'<?php
header("Content-Type: application/json");
require "config.php";
$data = json_decode(file_get_contents("php://input"), true);
$time = str_replace(" (30 min)","", $data["time"] ?? "");
$date = $data["date"] ?? "";
if(!$date || !$time){ echo json_encode(["success"=>false,"message"=>"Pick date and time"]); exit; }
$check = $conn->query("SELECT id FROM appointment WHERE date=\"$date\" AND time_slot=\"$time\"");
if($check && $check->num_rows>0){ echo json_encode(["success"=>false,"message"=>"❌ $time already booked!"]); exit; }
$sql = "INSERT INTO appointment (date,time_slot,meeting_type,name,phone,email,service,message) VALUES (\"$date\",\"$time\",\"{$data["meetingType"]}\",\"{$data["name"]}\",\"{$data["phone"]}\",\"{$data["email"]}\",\"{$data["service"]}\",\"{$data["message"]}\")";
if($conn->query($sql)){ echo json_encode(["success"=>true,"message"=>"✅ BOOKED! $time on $date"]); } else { echo json_encode(["success"=>false,"message"=>"DB Error: ".$conn->error]); }
');

echo "✅ ALL FIXED! Now config + apis are new.<br><br>";
echo "<a href='api-available-slots.php?date=2026-10-29'>TEST 1: Click to test slots (should show [\"08:00...\"] not <?php)</a><br><br>";
echo "<a href='book-appointment.html'>TEST 2: Go to Booking (Ctrl+F5)</a>";
?>