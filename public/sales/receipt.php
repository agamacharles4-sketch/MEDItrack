<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/SaleController.php";


$controller = new SaleController();


if(!isset($_GET['id']))
{
    die("Invalid receipt");
}


$id = intval($_GET['id']);


$sale = $controller->find($id);


if(!$sale)
{
    die("Sale not found");
}


$items = $controller->items($id);


?>


<!DOCTYPE html>

<html>

<head>

<title>
MEDItrack Receipt
</title>


<style>

body{

font-family: Arial, sans-serif;

background:white;

color:#000;

}


.receipt{

width:800px;

margin:auto;

padding:30px;

}


.header{

text-align:center;

}


.header i{

font-size:40px;

color:green;

}


.header h1{

margin:5px;

}


hr{

border:1px solid #000;

}


.info{

margin-top:20px;

}


.info table{

width:100%;

}


.items{

width:100%;

border-collapse:collapse;

margin-top:20px;

}


.items th,
.items td{

border:1px solid #000;

padding:8px;

text-align:left;

}


.total{

text-align:right;

font-size:20px;

font-weight:bold;

margin-top:20px;

}


.footer{

text-align:center;

margin-top:40px;

}



@media print{


.no-print{

display:none;

}


body{

margin:0;

}


.receipt{

width:100%;

}

}


</style>


</head>


<body>


<div class="receipt">



<div class="header">


<div style="font-size:50px;color:green">

🩺

</div>


<h1>

MEDItrack Pharmacy

</h1>


<p>

Quality Healthcare Services

</p>


<p>

Tel: +256 760532673

</p>


<p>

Drug Expiry Tracking System

</p>


<hr>


</div>





<div class="info">


<table>


<tr>

<td>

<strong>
Receipt No:
</strong>

</td>

<td>

<?= $sale['sale_number']; ?>

</td>


<td>

<strong>
Date:
</strong>

</td>

<td>

<?= date(
"d M Y H:i",
strtotime($sale['sale_date'])
); ?>

</td>


</tr>



<tr>

<td>

<strong>
Customer:
</strong>

</td>

<td>

<?= htmlspecialchars(
$sale['customer_name'] ?? "Walk-in Customer"
); ?>

</td>


<td>

<strong>
Payment:
</strong>

</td>

<td>

<?= $sale['payment_method']; ?>

</td>


</tr>



<tr>

<td>

<strong>
Cashier:
</strong>

</td>

<td colspan="3">

<?= $sale['first_name']." ".$sale['last_name']; ?>

</td>


</tr>


</table>


</div>






<table class="items">


<thead>


<tr>

<th>
Medicine
</th>


<th>
Batch
</th>


<th>
Qty
</th>


<th>
Price
</th>


<th>
Subtotal
</th>


</tr>


</thead>



<tbody>


<?php foreach($items as $item): ?>


<tr>


<td>

<?= htmlspecialchars(
$item['drug_name']
); ?>


<br>

<small>

<?= htmlspecialchars(
$item['generic_name']
); ?>

</small>


</td>


<td>

<?= $item['batch_number']; ?>

</td>


<td>

<?= $item['quantity']; ?>

</td>


<td>

UGX <?= number_format(
$item['selling_price']
); ?>

</td>


<td>

UGX <?= number_format(
$item['subtotal']
); ?>

</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>





<div class="total">

TOTAL:

UGX <?= number_format(
$sale['total_amount']
); ?>


</div>





<div class="footer">


<hr>


<h3>

Thank you for choosing MEDItrack Pharmacy

</h3>


<p>

Goods sold are not returnable without approval.

</p>


<p>

Powered by MEDItrack v1.0

</p>


</div>




<div class="no-print"
style="text-align:center;margin-top:30px;">


<button onclick="window.print()">

Print / Save PDF

</button>


</div>



</div>


</body>


</html>