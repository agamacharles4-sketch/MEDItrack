<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/ReportController.php";


$controller = new ReportController();


$profits = $controller->profit();



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-money-bill text-success"></i>

Profit Analysis

</h3>



<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">


<table class="table table-bordered table-hover">


<thead class="table-light">

<tr>

<th>#</th>

<th>Medicine</th>

<th>Quantity Sold</th>

<th>Total Cost</th>

<th>Total Sales</th>

<th>Profit</th>

</tr>

</thead>


<tbody>


<?php

$i=1;


$totalProfit=0;


foreach($profits as $row):

$totalProfit += $row['profit'];

?>


<tr>

<td><?= $i++; ?></td>


<td>

<?= htmlspecialchars($row['drug_name']); ?>

</td>


<td>

<?= number_format($row['quantity_sold']); ?>

</td>


<td>

UGX <?= number_format($row['total_cost']); ?>

</td>


<td>

UGX <?= number_format($row['total_sales']); ?>

</td>


<td class="text-success fw-bold">

UGX <?= number_format($row['profit']); ?>

</td>


</tr>


<?php endforeach; ?>


<tr class="table-success">

<th colspan="5">

Total Profit

</th>


<th>

UGX <?= number_format($totalProfit); ?>

</th>

</tr>


</tbody>


</table>


</div>


</div>


</div>



</div>

</div>


<?php include "../../resources/views/layouts/footer.php"; ?>