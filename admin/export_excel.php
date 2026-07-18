<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

/* ==========================
EXPORT TO EXCEL
========================== */

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Taste_Haven_Orders_Report_".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "
<table border='1'>

<tr style='background:#ffc107;font-weight:bold;'>

<th>ID</th>

<th>Transaction ID</th>

<th>Customer</th>

<th>Email</th>

<th>Phone</th>

<th>Payment Method</th>

<th>Payment Phone</th>

<th>Total Amount</th>

<th>Payment Status</th>

<th>Order Status</th>

<th>Date</th>

</tr>
";

$query=mysqli_query($conn,"
SELECT *
FROM orders
ORDER BY created_at DESC
");

while($row=mysqli_fetch_assoc($query))
{

echo "

<tr>

<td>".$row['id']."</td>

<td>".$row['transaction_id']."</td>

<td>".$row['customer_name']."</td>

<td>".$row['email']."</td>

<td>".$row['phone']."</td>

<td>".$row['payment_method']."</td>

<td>".$row['payment_phone']."</td>

<td>".CURRENCY." ".number_format($row['total_amount'],2)."</td>

<td>".$row['payment_status']."</td>

<td>".$row['order_status']."</td>

<td>".date("d M Y h:i A",strtotime($row['created_at']))."</td>

</tr>

";

}

echo "</table>";

?>