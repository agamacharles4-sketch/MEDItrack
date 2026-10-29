<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/PurchaseController.php";


$controller = new PurchaseController();


$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;
$status = $_GET['status'] ?? null;


$summary = $controller->purchaseSummary();


$purchases = $controller->purchaseReport(
    $from,
    $to,
    $status
);


include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">

<h3 class="fw-bold">

<i class="fa fa-shopping-cart text-primary"></i>

Purchase Report

</h3>


<button
onclick="window.print()"
class="btn btn-success">

<i class="fa fa-print"></i>

Print Report

</button>

</div>



<div class="row mb-4">


<div class="col-md-4">

<div class="card shadow-sm border-0">

<div class="card-body text-center">

<h6 class="text-muted">

Total Purchases

</h6>


<h2 class="text-primary">

UGX <?= number_format($summary['totalPurchases']); ?>

</h2>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card shadow-sm border-0">

<div class="card-body text-center">

<h6 class="text-muted">

Transactions

</h6>


<h2 class="text-success">

<?= number_format($summary['transactions']); ?>

</h2>


</div>

</div>

</div>



<div class="col-md-4">

<div class="card shadow-sm border-0">

<div class="card-body text-center">

<h6 class="text-danger">

Pending Payments

</h6>


<h2>

UGX <?= number_format($summary['pending']); ?>

</h2>


</div>

</div>

</div>


</div>





<div class="card shadow-sm mb-4">

<div class="card-body">


<form method="GET">


<div class="row">


<div class="col-md-3">

<label>From Date</label>

<input
type="date"
name="from"
value="<?= $from ?>"
class="form-control">

</div>


<div class="col-md-3">

<label>To Date</label>

<input
type="date"
name="to"
value="<?= $to ?>"
class="form-control">

</div>



<div class="col-md-3">

<label>Status</label>

<select name="status" class="form-select">


<option value="">
All
</option>


<option value="Paid">
Paid
</option>


<option value="Pending">
Pending
</option>


<option value="Partial">
Partial
</option>


</select>

</div>



<div class="col-md-3 mt-4">


<button class="btn btn-primary w-100">

<i class="fa fa-search"></i>

Filter

</button>


</div>


</div>


</form>


</div>

</div>





<div class="card shadow-sm">


<div class="card-header">

<h5>
Purchase Transactions
</h5>

</div>


<div class="card-body">


<div class="table-responsive">


<table class="table table-bordered table-hover">


<thead class="table-light">


<tr>

<th>Purchase No.</th>

<th>Date</th>

<th>Supplier</th>

<th>Amount</th>

<th>Status</th>

<th>Processed By</th>

</tr>


</thead>


<tbody>


<?php foreach($purchases as $purchase): ?>


<tr>


<td>

<?= $purchase['purchase_number']; ?>

</td>


<td>

<?= date(
"d M Y H:i",
strtotime($purchase['purchase_date'])
); ?>

</td>


<td>

<?= htmlspecialchars(
$purchase['supplier_name']
); ?>

</td>


<td>

UGX <?= number_format(
$purchase['total_amount']
); ?>

</td>


<td>

<span class="badge bg-info">

<?= $purchase['payment_status']; ?>

</span>

</td>


<td>

<?= htmlspecialchars(
$purchase['first_name']." ".$purchase['last_name']
); ?>

</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>


</div>


</div>


</div>


</div>

</div>


<?php include "../../resources/views/layouts/footer.php"; ?>