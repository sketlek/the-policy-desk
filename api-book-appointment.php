<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
require 'config.php';
date_default_timezone_set('Africa/Nairobi');

$data = json_decode(file_get_contents('php://input'), true);
if(!$data || empty($data['date']) || empty($data['time'])){
  echo json_encode(['success'=>false,'message'=>'Missing date or time']);
  exit;
}

$time = str_replace(" (30 min)","",$data['time']);
$date = $data['date'];
$name = trim($data['name'] ?? '');
$phone = trim($data['phone'] ?? '');
$meetingType = $data['meetingType'] ?? '';
$email = $data['email'] ?? '';
$service = $data['service'] ?? '';
$message = $data['message'] ?? '';

$dt = valid_date($date);
if(!$dt || $date < date('Y-m-d') || $dt->format('N') == 7){
  echo json_encode(['success'=>false,'message'=>'Please pick a valid date (Mon-Sat, today or later)']);
  exit;
}
if(!in_array($time,$SLOTS,true) || ($date == date('Y-m-d') && $time <= date('H:i'))){
  echo json_encode(['success'=>false,'message'=>'Please pick an available time slot']);
  exit;
}
if($name === '' || $phone === ''){
  echo json_encode(['success'=>false,'message'=>'Name and phone are required']);
  exit;
}

$chk = $conn->prepare("SELECT id FROM appointment WHERE date=? AND time_slot=?");
$chk->bind_param("ss",$date,$time);
$chk->execute();
$chk->store_result();
if($chk->num_rows>0){
 echo json_encode(['success'=>false,'message'=>'❌ '.$time.' on '.$date.' already booked!']);
 exit;
}

$stmt = $conn->prepare("INSERT INTO appointment (date,time_slot,meeting_type,name,phone,email,service,message) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss",$date,$time,$meetingType,$name,$phone,$email,$service,$message);

if($stmt->execute()){
 echo json_encode(['success'=>true,'message'=>'✅ Booked! '.$time.' on '.$date.' for '.$name]);
}elseif($conn->errno == 1062){
 // Someone else took the slot between the check and the insert.
 echo json_encode(['success'=>false,'message'=>'❌ '.$time.' on '.$date.' was just booked by someone else. Please pick another slot.']);
}else{
 error_log('Booking error: '.$conn->error);
 echo json_encode(['success'=>false,'message'=>'Could not save booking. Please try again.']);
}
?>
