<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
require 'config.php';
$data=json_decode(file_get_contents('php://input'), true);
if(!$data) exit(json_encode(['success'=>false,'message'=>'Invalid']));

$name=trim($data['name'] ?? '');
$phone=trim($data['phone'] ?? '');
$id_number=$data['id_number'] ?? '';
$policy_type=$data['policy_type'] ?? '';
$amount=$data['amount'] ?? '';
$mpesa_code=$data['mpesa_code'] ?? '';
if($name==='' || $phone==='') exit(json_encode(['success'=>false,'message'=>'Name and phone are required']));

$stmt=$conn->prepare("INSERT INTO policy_purchase (name,phone,id_number,policy_type,amount,mpesa_code) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss",$name,$phone,$id_number,$policy_type,$amount,$mpesa_code);

if($stmt->execute()){
 echo json_encode(['success'=>true,'message'=>'✅ Policy request received! Paybill 247247 Acc 011331. Code '.$mpesa_code.' will be verified in 1 hour. Onesmus will call you.']);
}else{
 error_log('Policy purchase error: '.$conn->error);
 echo json_encode(['success'=>false,'message'=>'Could not save your request. Please try again.']);
}
?>
