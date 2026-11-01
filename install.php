<?php
// One-time setup: creates the database and tables. DELETE THIS FILE after running it.
$c = new mysqli("localhost","root","","");
if($c->connect_error) die("Start MySQL in XAMPP");
$c->query("CREATE DATABASE IF NOT EXISTS policydesk CHARACTER SET utf8mb4");
$c->select_db("policydesk");
$c->query("CREATE TABLE IF NOT EXISTS appointment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE, time_slot VARCHAR(20), meeting_type VARCHAR(30),
  name VARCHAR(100), phone VARCHAR(30), email VARCHAR(100),
  service VARCHAR(100), message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(date, time_slot)
)");
$c->query("CREATE TABLE IF NOT EXISTS policy_purchase (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100), phone VARCHAR(30), id_number VARCHAR(30),
  policy_type VARCHAR(100), amount VARCHAR(50), mpesa_code VARCHAR(30),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
if($c->error) die("Setup error: ".htmlspecialchars($c->error));

echo "✅ Database ready (appointment + policy_purchase tables).<br><br>";
echo "<b>Now delete install.php from the server.</b><br><br>";
echo "<a href='book-appointment.html'>Go to Booking</a>";
?>
