<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/SaleController.php";

$controller = new SaleController();

$sales = $controller->index();

$stats = $controller->statistics();

include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3 class="fw-bold">

<i class="fa fa-cash-register text-success"></i>

Sales Management

</h3>

<a href="create.php" class="btn btn-primary">

<i class="fa fa-plus"></i>

New Sale

</a>
<a href="returns.php" class="btn btn-warning ms-2">

<i class="fa fa-undo"></i>

Returns

</a>
</div>
<div class="row mb-4">

<div class="col-md-4">

<div class="card shadow-sm border-0">

<div class="card-body">

<h6 class="text-muted">

Today's Sales

</h6>

<h2 class="text-success">

UGX <?= number_format($stats['todaySales']); ?>

</h2>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card shadow-sm border-0">

<div class="card-body">

<h6 class="text-muted">

Today's Transactions

</h6>

<h2 class="text-primary">

<?= $stats['todayTransactions']; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card shadow-sm border-0">

<div class="card-body">

<h6 class="text-muted">

Monthly Sales

</h6>

<h2 class="text-warning">

UGX <?= number_format($stats['monthlySales']); ?>

</h2>

</div>

</div>

</div>

</div>
<div class="card shadow-sm">

<div class="card-header">

<h5>

Recent Sales

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover">

<thead>

<tr>

<th>Sale No.</th>

<th>Date</th>

<th>Customer</th>

<th>Total</th>

<th>Payment</th>

<th>Cashier</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php foreach($sales as $sale): ?>


<tr>


<td>

<?= $sale['sale_number']; ?>

</td>



<td>

<?= date(
"d M Y H:i",
strtotime($sale['sale_date'])
); ?>

</td>



<td>

<?= htmlspecialchars(
$sale['customer_name'] ?? "Walk-in Customer"
); ?>

</td>



<td>

UGX <?= number_format(
$sale['total_amount']
); ?>

</td>



<td>

<span class="badge bg-success">

<?= $sale['payment_method']; ?>

</span>

</td>



<td>

<?= htmlspecialchars(
$sale['first_name']." ".$sale['last_name']
); ?>

</td>



<td>


<div class="btn-group btn-group-sm">


<a href="show.php?id=<?= $sale['sale_id']; ?>"
class="btn btn-info"
title="View Sale">

<i class="fa fa-eye"></i>

</a>


<a href="/MEDItrack/public/sales/return.php?id=<?= $sale['sale_id']; ?>"
class="btn btn-warning"
title="Return Sale">

<i class="fa fa-undo"></i>

</a>


</div>


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