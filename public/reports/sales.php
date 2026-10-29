<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/SaleController.php";


$controller = new SaleController();


$from = $_GET['from'] ?? null;

$to = $_GET['to'] ?? null;

$payment = $_GET['payment'] ?? null;


$sales = $controller->salesReport(
    $from,
    $to,
    $payment
);



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-chart-line text-success"></i>

Sales Report

</h3>



<div class="card shadow-sm mb-4">

<div class="card-body">


<form method="GET">


<div class="row">


<div class="col-md-3">

<label>From</label>

<input 
type="date"
name="from"
class="form-control">

</div>


<div class="col-md-3">

<label>To</label>

<input 
type="date"
name="to"
class="form-control">

</div>


<div class="col-md-3">

<label>Payment Method</label>

<select name="payment" class="form-select">

<option value="">All</option>

<option>Cash</option>

<option>Mobile Money</option>

<option>Card</option>

</select>

</div>


<div class="col-md-3 mt-4">

<button class="btn btn-primary">

<i class="fa fa-search"></i>

Filter

</button>

</div>


</div>


</form>


</div>

</div>



<div class="card shadow-sm">

<div class="card-body">


<table class="table table-bordered table-hover">


<thead class="table-light">

<tr>

<th>#</th>

<th>Sale No.</th>

<th>Customer</th>

<th>Date</th>

<th>Amount</th>

<th>Payment</th>

<th>User</th>

</tr>

</thead>


<tbody>


<?php

$i=1;


foreach($sales as $sale):

?>


<tr>


<td><?= $i++; ?></td>


<td><?= $sale['sale_number']; ?></td>


<td><?= htmlspecialchars($sale['customer_name']); ?></td>


<td><?= date(
"d M Y",
strtotime($sale['sale_date'])
); ?></td>


<td>

UGX <?= number_format($sale['total_amount']); ?>

</td>


<td>

<?= $sale['payment_method']; ?>

</td>


<td>

<?= $sale['first_name']." ".$sale['last_name']; ?>

</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>


</div>

</div>


</div>

</div>


<?php include "../../resources/views/layouts/footer.php"; ?>