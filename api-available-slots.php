<?php
header('Content-Type: application/json');
require 'config.php';
date_default_timezone_set('Africa/Nairobi');

$date = $_GET['date'] ?? date('Y-m-d');
$dt = valid_date($date);
// Invalid, past, or Sunday dates have no free slots.
if(!$dt || $date < date('Y-m-d') || $dt->format('N') == 7){ echo json_encode([]); exit; }

$booked = [];
$q = $conn->prepare("SELECT time_slot FROM appointment WHERE date=?");
$q->bind_param("s",$date);
$q->execute();
$res = $q->get_result();
while($r=$res->fetch_assoc()){ $booked[]=$r['time_slot']; }

$free = [];
foreach($SLOTS as $t){
  // Skip slots that have already passed today.
  if($date == date('Y-m-d') && $t <= date('H:i')) continue;
  if(!in_array($t,$booked)){ $free[] = $t." (30 min)"; }
}
echo json_encode($free);
?>
