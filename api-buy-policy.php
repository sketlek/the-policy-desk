<?php
header('Content-Type: application/json');
require 'config.php';
$data=json_decode(file_get_contents('php://input'), true);
if(!$data) exit(json_encode(['success'=>false,'message'=>'Invalid']));

$stmt=$conn->prepare("INSERT INTO policy_purchase (name,phone,id_number,policy_type,amount,mpesa_code) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss",$data['name'],$data['phone'],$data['id_number'],$data['policy_type'],$data['amount'],$data['mpesa_code']);

if($stmt->execute()){
 echo json_encode(['success'=>true,'message'=>'✅ Policy request received! Paybill 247247 Acc 011331. Code '.$data['mpesa_code'].' will be verified in 1 hour. Onesmus will call you.']);
}else{
 echo json_encode(['success'=>false,'message'=>'Error: '.$conn->error]);
}
?>