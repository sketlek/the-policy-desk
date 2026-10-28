<?php
header('Content-Type: application/json');
require 'config.php';
$date = $_GET['date'] ?? date('Y-m-d');
$booked = [];
$q = $conn->query("SELECT time_slot FROM appointment WHERE date='$date'");
if($q){ while($r=$q->fetch_assoc()){ $booked[]=$r['time_slot']; } }
$all = ["08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","14:00","14:30","15:00","15:30","16:00","16:30"];
$free = [];
foreach($all as $t){ if(!in_array($t,$booked)){ $free[] = $t." (30 min)"; } }
echo json_encode($free);
?>