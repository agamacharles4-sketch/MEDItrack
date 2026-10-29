<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/PurchaseController.php";

$controller = new PurchaseController();

if(!isset($_GET['id']))
{
    die("Invalid Purchase");
}

$id = intval($_GET['id']);

$purchase = $controller->find($id);

if(!$purchase)
{
    die("Purchase not found");
}

$items = $controller->items($id);

include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between mb-4">

<h3 class="fw-bold">

<i class="fa fa-truck text-primary"></i>

Purchase Details

</h3>

<button
onclick="window.print()"
class="btn btn-success">

<i class="fa fa-print"></i>

Print

</button>

</div>

<div class="card shadow-sm mb-4">

<div class="card-body">

<p>

<strong>Purchase Number:</strong>

<?= $purchase['purchase_number']; ?>

</p>

<p>

<strong>Supplier:</strong>

<?= htmlspecialchars($purchase['supplier_name']); ?>

</p>

<p>

<strong>Invoice:</strong>

<?= htmlspecialchars($purchase['invoice_number']); ?>

</p>

<p>

<strong>Date:</strong>

<?= date("d M Y H:i",strtotime($purchase['purchase_date'])); ?>

</p>

<p>

<strong>Recorded By:</strong>

<?= htmlspecialchars($purchase['first_name']." ".$purchase['last_name']); ?>

</p>

<p>

<strong>Payment Status:</strong>

<?= $purchase['payment_status']; ?>

</p>

</div>

</div>

<div class="card shadow-sm">

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Medicine</th>

<th>Batch</th>

<th>Qty</th>

<th>Buying</th>

<th>Selling</th>

<th>Expiry</th>

<th>Subtotal</th>

</tr>

</thead>

<tbody>

<?php foreach($items as $item): ?>

<tr>

<td>

<?= htmlspecialchars($item['drug_name']); ?>

<br>

<small>

<?= htmlspecialchars($item['generic_name']); ?>

</small>

</td>

<td>

<?= $item['batch_number']; ?>

</td>

<td>

<?= $item['quantity']; ?>

</td>

<td>

UGX <?= number_format($item['buying_price']); ?>

</td>

<td>

UGX <?= number_format($item['selling_price']); ?>

</td>

<td>

<?= $item['expiry_date']; ?>

</td>

<td>

UGX <?= number_format($item['subtotal']); ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

<tfoot>

<tr>

<th colspan="6">

TOTAL

</th>

<th>

UGX <?= number_format($purchase['total_amount']); ?>

</th>

</tr>

</tfoot>

</table>

</div>

</div>

</div>

</div>

<?php include "../../resources/views/layouts/footer.php"; ?>