<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
if(!$data || empty($data['date']) || empty($data['time'])){
  echo json_encode(['success'=>false,'message'=>'Missing date or time']);
  exit;
}

$time = str_replace(" (30 min)","",$data['time']);
$date = $data['date'];

$chk = $conn->prepare("SELECT id FROM appointment WHERE date=? AND time_slot=?");
$chk->bind_param("ss",$date,$time);
$chk->execute();
$chk->store_result();
if($chk->num_rows>0){
 echo json_encode(['success'=>false,'message'=>'❌ '.$time.' on '.$date.' already booked!']);
 exit;
}

$stmt = $conn->prepare("INSERT INTO appointment (date,time_slot,meeting_type,name,phone,email,service,message) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss",$date,$time,$data['meetingType'],$data['name'],$data['phone'],$data['email'],$data['service'],$data['message']);

if($stmt->execute()){
 echo json_encode(['success'=>true,'message'=>'✅ Booked! '.$time.' on '.$date.' for '.$data['name']]);
}else{
 echo json_encode(['success'=>false,'message'=>'SQL Error: '.$conn->error]);
}
?>