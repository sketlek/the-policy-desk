<?php
$conn = new mysqli("localhost","root","","policydesk");
if($conn->connect_error){ die("DB Error: ".$conn->connect_error); }
?>