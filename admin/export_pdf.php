<?php

include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

require("../fpdf/fpdf.php");

$pdf=new FPDF();

$pdf->AddPage();

$pdf->SetFont("Arial","B",18);

$pdf->Cell(190,12,"Taste Haven Restaurant",0,1,"C");

$pdf->SetFont("Arial","",12);

$pdf->Cell(190,8,"Restaurant Orders Report",0,1,"C");

$pdf->Cell(190,8,"Generated: ".date("d M Y h:i A"),0,1,"C");

$pdf->Ln(8);

$total=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE order_status='Delivered'
"));

$pdf->SetFont("Arial","B",12);

$pdf->Cell(60,10,"Total Revenue");

$pdf->Cell(60,10,CURRENCY." ".number_format($total['total'],2));

$pdf->Ln(15);

$pdf->SetFont("Arial","B",10);

$pdf->Cell(15,10,"ID",1);

$pdf->Cell(45,10,"Customer",1);

$pdf->Cell(45,10,"Transaction",1);

$pdf->Cell(35,10,"Amount",1);

$pdf->Cell(50,10,"Status",1);

$pdf->Ln();

$pdf->SetFont("Arial","",9);

$query=mysqli_query($conn,"
SELECT *
FROM orders
ORDER BY created_at DESC
");

while($row=mysqli_fetch_assoc($query))
{

$pdf->Cell(15,9,$row['id'],1);

$pdf->Cell(45,9,substr($row['customer_name'],0,20),1);

$pdf->Cell(45,9,$row['transaction_id'],1);

$pdf->Cell(35,9,CURRENCY." ".number_format($row['total_amount'],2),1);

$pdf->Cell(50,9,$row['order_status'],1);

$pdf->Ln();

}

$pdf->Output("I","Taste_Haven_Report.pdf");

?>