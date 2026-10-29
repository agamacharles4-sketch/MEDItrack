<?php

require_once "../../config/session.php";

checkLogin();


require_once "../../app/Controllers/BatchController.php";


$controller = new BatchController();


$search = $_GET['search'] ?? "";

$batches = $controller->index($search);
$stats = $controller->statistics();
$filters = [

    "search" => $_GET['search'] ?? "",

    "drug_id" => $_GET['drug_id'] ?? "",

    "supplier_id" => $_GET['supplier_id'] ?? "",

    "expiry_status" => $_GET['expiry_status'] ?? "",

    "stock_status" => $_GET['stock_status'] ?? ""

];

$batches = $controller->index($filters);
$stats = $controller->statistics();
require_once "../../app/Models/Drug.php";
require_once "../../app/Models/Supplier.php";

$drugModel = new Drug();
$supplierModel = new Supplier();

$drugs = $drugModel->all();
$suppliers = $supplierModel->all();

include "../../resources/views/layouts/header.php";

include "../../resources/views/layouts/sidebar.php";

include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">


<h3 class="fw-bold">

<i class="fa fa-box text-primary"></i>

Batch Management

</h3>


<a href="create.php" class="btn btn-primary">

<i class="fa fa-plus"></i>

Add Batch

</a>


</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body">

<h6 class="text-muted">Total Batches</h6>

<h2 class="fw-bold text-primary">

<?= $stats['total']; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body">

<h6 class="text-muted">

Expired

</h6>

<h2 class="fw-bold text-danger">

<?= $stats['expired']; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body">

<h6 class="text-muted">

Expiring Soon

</h6>

<h2 class="fw-bold text-warning">

<?= $stats['expiring']; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm">

<div class="card-body">

<h6 class="text-muted">

Low Stock

</h6>

<h2 class="fw-bold text-success">

<?= $stats['lowstock']; ?>

</h2>

</div>

</div>

</div>

</div></td>
<form method="GET" class="row g-3 mb-4">

<div class="col-md-3">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Batch..."
value="<?= htmlspecialchars($filters['search']); ?>">

</div>

<div class="col-md-2">

<select name="drug_id" class="form-select">

<option value="">All Drugs</option>

<?php foreach($drugs as $drug): ?>

<option
value="<?= $drug['drug_id']; ?>"
<?= $filters['drug_id']==$drug['drug_id'] ? "selected" : ""; ?>>

<?= htmlspecialchars($drug['drug_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-2">

<select name="supplier_id" class="form-select">

<option value="">All Suppliers</option>

<?php foreach($suppliers as $supplier): ?>

<option
value="<?= $supplier['supplier_id']; ?>"
<?= $filters['supplier_id']==$supplier['supplier_id'] ? "selected" : ""; ?>>

<?= htmlspecialchars($supplier['supplier_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-2">

<select name="expiry_status" class="form-select">

<option value="">Expiry Status</option>

<option value="Valid">Valid</option>

<option value="Expiring Soon">Expiring Soon</option>

<option value="Expired">Expired</option>

</select>

</div>

<div class="col-md-2">

<select name="stock_status" class="form-select">

<option value="">Stock Status</option>

<option value="In Stock">In Stock</option>

<option value="Low Stock">Low Stock</option>

<option value="Out of Stock">Out of Stock</option>

</select>

</div>

<div class="col-md-1 d-grid">

<button class="btn btn-primary">

<i class="fa fa-search"></i>

</button>

</div>

</form>
<div class="card">


<div class="card-body">


<div class="table-responsive">


<table class="table table-hover">


<thead>

<tr class="<?php

if($batch['expiry_status']=="Expired")
    echo "table-danger";

elseif($batch['expiry_status']=="Expiring Soon")
    echo "table-warning";

?>">

<th>#</th>

<th>Drug</th>

<th>Supplier</th>

<th>Batch Number</th>

<th>Expiry Date</th>

<th>Quantity</th>

<th>Stock Status</th>

<th>Expiry Status</th>

<th>Action</th>

</tr>

</thead>



<tbody>


<?php foreach($batches as $batch): ?>


<tr class="<?php

if($batch['expiry_status']=="Expired")
    echo "table-danger";

elseif($batch['expiry_status']=="Expiring Soon")
    echo "table-warning";

?>">


<td>

<?= $batch['batch_id']; ?>

</td>


<td>

<strong>

<?= htmlspecialchars($batch['drug_name']); ?>

</strong>

</td>



<td>

<?= htmlspecialchars($batch['supplier_name']); ?>

</td>



<td>

<?= htmlspecialchars($batch['batch_number']); ?>

</td>



<td>

<?= $batch['expiry_date']; ?>

</td>



<td>

<?php if($batch['stock_status']=="Low Stock"): ?>

<span class="text-danger fw-bold">

<?= $batch['quantity_remaining']; ?>

</span>

<?php else: ?>

<?= $batch['quantity_remaining']; ?>

<?php endif; ?>

</td>



<td>


<?php if($batch['stock_status']=="In Stock"): ?>


<span class="badge bg-success">

In Stock

</span>


<?php elseif($batch['stock_status']=="Low Stock"): ?>


<span class="badge bg-warning">

Low Stock

</span>


<?php else: ?>


<span class="badge bg-danger">

Out of Stock

</span>


<?php endif; ?>


</td>




<td>


<?php if($batch['expiry_status']=="Valid"): ?>


<span class="badge bg-success">

Valid

</span>


<?php elseif($batch['expiry_status']=="Expiring Soon"): ?>


<span class="badge bg-warning">

Expiring Soon

</span>


<?php else: ?>


<span class="badge bg-danger">

Expired

</span>


<?php endif; ?>


</td>



<td>


<a href="edit.php?id=<?= $batch['batch_id']; ?>"
class="btn btn-sm btn-warning">

<i class="fa fa-edit"></i>

</a>


<a onclick="return confirm('Delete this batch?')"
href="delete.php?id=<?= $batch['batch_id']; ?>"
class="btn btn-sm btn-danger">

<i class="fa fa-trash"></i>

</a>


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



<?php

include "../../resources/views/layouts/footer.php";

?>