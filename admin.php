<?php
require 'config.php';
header('Content-Type: text/html; charset=UTF-8');

// Simple password protection (username: admin, password: ADMIN_PASSWORD in config.php).
if(($_SERVER['PHP_AUTH_USER'] ?? '') !== 'admin' || !hash_equals(ADMIN_PASSWORD, $_SERVER['PHP_AUTH_PW'] ?? '')){
  header('WWW-Authenticate: Basic realm="The Policy Desk Admin"');
  http_response_code(401);
  exit('Login required');
}

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html><html><head><title>Admin - The Policy Desk</title><style>body{font-family:Arial;padding:20px;background:#f6f7fb}table{width:100%;background:#fff;border-collapse:collapse;margin-bottom:30px}th,td{border:1px solid #dde3ef;padding:8px;font-size:12px;text-align:left}th{background:#0d2137;color:#fff}h2{color:#0d2137}</style></head><body>
<h2>📅 Appointments - 30 Min Each (No Double Booking)</h2>
<table><tr><th>Date</th><th>Time</th><th>Type</th><th>Name</th><th>Phone</th><th>Email</th><th>Service</th><th>Message</th></tr>
<?php $r=$conn->query("SELECT * FROM appointment ORDER BY date, time_slot"); if($r) while($row=$r->fetch_assoc()){ echo "<tr><td>".e($row['date'])."</td><td>".e($row['time_slot'])."</td><td>".e($row['meeting_type'])."</td><td>".e($row['name'])."</td><td>".e($row['phone'])."</td><td>".e($row['email'])."</td><td>".e($row['service'])."</td><td>".e($row['message'])."</td></tr>"; } ?>
</table>
<h2>🛒 Policy Purchases - Paybill 247247</h2>
<table><tr><th>Name</th><th>Phone</th><th>ID</th><th>Policy</th><th>Amount</th><th>M-Pesa Code</th><th>Date</th></tr>
<?php $r=$conn->query("SELECT * FROM policy_purchase ORDER BY created_at DESC"); if($r) while($row=$r->fetch_assoc()){ echo "<tr><td>".e($row['name'])."</td><td>".e($row['phone'])."</td><td>".e($row['id_number'])."</td><td>".e($row['policy_type'])."</td><td>".e($row['amount'])."</td><td>".e($row['mpesa_code'])."</td><td>".e($row['created_at'])."</td></tr>"; } ?>
</table>
</body></html>
