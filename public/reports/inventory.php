<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/InventoryController.php";

$controller = new InventoryController();

$summary = $controller->summary();
$report = $controller->report();

include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3 class="fw-bold">

<i class="fa fa-boxes text-warning"></i>

Inventory Report

</h3>

<button
class="btn btn-success"
onclick="window.print()">

<i class="fa fa-print"></i>

Print Report

</button>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body text-center">

<h6 class="text-muted">

Total Medicines

</h6>

<h2 class="text-primary">

<?= number_format($summary['totalMedicines']); ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body text-center">

<h6 class="text-muted">

Total Stock

</h6>

<h2 class="text-success">

<?= number_format($summary['totalStock']); ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body text-center">

<h6 class="text-muted">

Inventory Value

</h6>

<h2 class="text-warning">

UGX <?= number_format($summary['inventoryValue']); ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body text-center">

<h6 class="text-muted">

Low Stock Items

</h6>

<h2 class="text-danger">

<?= number_format($summary['lowStock']); ?>

</h2>

</div>

</div>

</div>

</div>

<div class="card shadow-sm">

<div class="card-header">

<h5>

Current Inventory

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover table-bordered">

<thead class="table-light">

<tr>

<th>#</th>

<th>Medicine</th>

<th>Category</th>

<th>Current Stock</th>

<th>Minimum</th>

<th>Buying Price</th>

<th>Selling Price</th>

<th>Stock Value</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php
$i = 1;

foreach($report as $row):

$status = "In Stock";
$badge = "success";

if($row['current_stock'] <= 0){

    $status = "Out of Stock";
    $badge = "danger";

}
elseif($row['current_stock'] <= $row['minimum_stock']){

    $status = "Low Stock";
    $badge = "warning";

}
?>

<tr>

<td><?= $i++; ?></td>

<td>

<strong>

<?= htmlspecialchars($row['drug_name']); ?>

</strong>

<br>

<small class="text-muted">

<?= htmlspecialchars($row['generic_name']); ?>

</small>

</td>

<td>

<?= htmlspecialchars($row['category_name']); ?>

</td>

<td>

<?= number_format($row['current_stock']); ?>

</td>

<td>

<?= number_format($row['minimum_stock']); ?>

</td>

<td>

UGX <?= number_format($row['buying_price'] ?? 0); ?>

</td>

<td>

UGX <?= number_format($row['selling_price'] ?? 0); ?>

</td>

<td>

UGX <?= number_format($row['stock_value'] ?? 0); ?>

</td>

<td>

<span class="badge bg-<?= $badge; ?>">

<?= $status; ?>

</span>

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