<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/ReportController.php";


$controller = new ReportController();


$sales = $controller->sales();

$purchases = $controller->purchases();

$inventory = $controller->inventory();

$expiry = $controller->expiry();



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<a href="profit.php" class="btn btn-warning">

<i class="fa fa-money-bill"></i>

Profit Analysis

</a>

<div class="main-content">

<div class="container-fluid">



<div class="d-flex justify-content-between align-items-center mb-4">


<h3 class="fw-bold">

<i class="fa fa-chart-line text-primary"></i>

Reports & Analytics

</h3>


<a href="print.php?<?= $_SERVER['QUERY_STRING']; ?>"
class="btn btn-success"
target="_blank">

<i class="fa fa-print"></i>

Print Report

</a>


</div>




<div class="row mb-4">



<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body text-center">


<h6 class="text-muted">

Total Sales

</h6>


<h3 class="text-success">

UGX <?= number_format($sales['totalSales']); ?>

</h3>


<p>

<?= $sales['transactions']; ?> Transactions

</p>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body text-center">


<h6 class="text-muted">

Total Purchases

</h6>


<h3 class="text-primary">

UGX <?= number_format($purchases['totalPurchases']); ?>

</h3>


<p>

<?= $purchases['transactions']; ?> Purchases

</p>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body text-center">


<h6 class="text-muted">

Inventory Value

</h6>


<h3 class="text-warning">

UGX <?= number_format($inventory); ?>

</h3>


<p>

Current Stock

</p>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body text-center">


<h6 class="text-muted">

Expiry Alerts

</h6>


<h3 class="text-danger">

<?= count($expiry); ?>

</h3>


<p>

Within 90 Days

</p>


</div>


</div>


</div>



</div>






<div class="card shadow-sm">


<div class="card-header">

<h5 class="mb-0">

Expiry Report

</h5>

</div>


<div class="card-body">


<div class="table-responsive">


<table class="table table-bordered table-hover">


<thead class="table-light">

<tr>

<th>#</th>

<th>Medicine</th>

<th>Batch</th>

<th>Expiry Date</th>

<th>Quantity Remaining</th>

</tr>

</thead>



<tbody>


<?php

$i=1;


foreach($expiry as $drug):

?>


<tr>


<td>

<?= $i++; ?>

</td>


<td>

<?= htmlspecialchars($drug['drug_name']); ?>

</td>


<td>

<?= htmlspecialchars($drug['batch_number']); ?>

</td>


<td>

<span class="badge bg-danger">

<?= $drug['expiry_date']; ?>

</span>

</td>


<td>

<?= number_format($drug['quantity_remaining']); ?>

</td>


</tr>


<?php endforeach; ?>



<?php if(count($expiry)==0): ?>


<tr>

<td colspan="5" class="text-center text-success">

No expiry alerts

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


</div>


</div>


</div>



</div>


</div>



<?php include "../../resources/views/layouts/footer.php"; ?>