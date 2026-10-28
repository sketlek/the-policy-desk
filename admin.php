<?php require 'config.php'; ?>
<!DOCTYPE html><html><head><title>Admin - The Policy Desk</title><style>body{font-family:Arial;padding:20px;background:#f6f7fb}table{width:100%;background:#fff;border-collapse:collapse;margin-bottom:30px}th,td{border:1px solid #dde3ef;padding:8px;font-size:12px;text-align:left}th{background:#0d2137;color:#fff}h2{color:#0d2137}</style></head><body>
<h2>📅 Appointments - 30 Min Each (No Double Booking)</h2>
<table><tr><th>Date</th><th>Time</th><th>Type</th><th>Name</th><th>Phone</th><th>Service</th></tr>
<?php $r=$conn->query("SELECT * FROM appointment ORDER BY date, time_slot"); while($row=$r->fetch_assoc()){ echo "<tr><td>{$row['date']}</td><td>{$row['time_slot']}</td><td>{$row['meeting_type']}</td><td>{$row['name']}</td><td>{$row['phone']}</td><td>{$row['service']}</td></tr>"; } ?>
</table>
<h2>🛒 Policy Purchases - Paybill 247247</h2>
<table><tr><th>Name</th><th>Phone</th><th>ID</th><th>Policy</th><th>Amount</th><th>M-Pesa Code</th><th>Date</th></tr>
<?php $r=$conn->query("SELECT * FROM policy_purchase ORDER BY created_at DESC"); while($row=$r->fetch_assoc()){ echo "<tr><td>{$row['name']}</td><td>{$row['phone']}</td><td>{$row['id_number']}</td><td>{$row['policy_type']}</td><td>{$row['amount']}</td><td>{$row['mpesa_code']}</td><td>{$row['created_at']}</td></tr>"; } ?>
</table>
</body></html>