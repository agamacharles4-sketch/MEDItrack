<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/PurchaseController.php";

$controller = new PurchaseController();

$purchases = $controller->index();

include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3 class="fw-bold">

<i class="fa fa-truck text-primary"></i>

Purchases

</h3>

<a href="create.php" class="btn btn-primary">

<i class="fa fa-plus"></i>

New Purchase

</a>

</div>

<div class="card shadow-sm">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover">

<thead>

<tr>

<th>Purchase No.</th>

<th>Supplier</th>

<th>Date</th>

<th>Total</th>

<th>Status</th>

<th>User</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($purchases as $purchase): ?>

<tr>

<td>

<?= $purchase['purchase_number']; ?>

</td>

<td>

<?= htmlspecialchars($purchase['supplier_name']); ?>

</td>

<td>

<?= date("d M Y", strtotime($purchase['purchase_date'])); ?>

</td>

<td>

UGX <?= number_format($purchase['total_amount']); ?>

</td>

<td>

<span class="badge bg-info">

<?= $purchase['payment_status']; ?>

</span>

</td>

<td>

<?= htmlspecialchars($purchase['first_name']." ".$purchase['last_name']); ?>

</td>

<td>

<div class="btn-group btn-group-sm">

<a href="show.php?id=<?= $purchase['purchase_id']; ?>"
class="btn btn-info"
title="View Purchase">

<i class="fa fa-eye"></i>

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