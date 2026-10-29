<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


require_once "../../config/session.php";

checkLogin();


require_once "../../app/Controllers/InventoryController.php";


$controller = new InventoryController();


$inventory = $controller->index();



include "../../resources/views/layouts/header.php";

include "../../resources/views/layouts/sidebar.php";

include "../../resources/views/layouts/navbar.php";


?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">


<h3 class="fw-bold">

<i class="fa fa-warehouse text-warning"></i>

Inventory

</h3>


</div>




<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">


<table class="table table-hover table-bordered">


<thead class="table-light">


<tr>

<th>#</th>

<th>Medicine</th>

<th>Category</th>

<th>Supplier</th>

<th>Batch Number</th>

<th>Quantity</th>

<th>Buying Price</th>

<th>Selling Price</th>

<th>Expiry Date</th>

<th>Status</th>

</tr>


</thead>



<tbody>


<?php

$i=1;


foreach($inventory as $item):

?>


<tr>


<td>

<?= $i++; ?>

</td>



<td>

<strong>

<?= htmlspecialchars($item['drug_name']); ?>

</strong>

</td>



<td>

<?= htmlspecialchars($item['category_name']); ?>

</td>



<td>

<?= htmlspecialchars($item['supplier_name']); ?>

</td>



<td>

<?= htmlspecialchars($item['batch_number']); ?>

</td>



<td>

<?= number_format($item['quantity_remaining']); ?>

</td>



<td>

UGX <?= number_format($item['buying_price'] ?? 0); ?>

</td>



<td>

UGX <?= number_format($item['selling_price'] ?? 0); ?>

</td>



<td>

<?= htmlspecialchars($item['expiry_date']); ?>

</td>



<td>


<?php if($item['stock_status']=="In Stock"): ?>


<span class="badge bg-success">

In Stock

</span>


<?php else: ?>


<span class="badge bg-danger">

<?= htmlspecialchars($item['stock_status']); ?>

</span>


<?php endif; ?>


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