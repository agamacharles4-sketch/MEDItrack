<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/BatchController.php";
require_once "../../app/Models/Drug.php";
require_once "../../app/Models/Supplier.php";


$controller = new BatchController();


if(!isset($_GET['id']))
{
    die("Invalid Batch.");
}


$id=intval($_GET['id']);


$batch=$controller->find($id);


if(!$batch)
{
    die("Batch not found.");
}



$drugModel=new Drug();

$supplierModel=new Supplier();


$drugs=$drugModel->all();

$suppliers=$supplierModel->all();



if(isset($_POST['update']))
{


$data=[

"drug_id"=>$_POST['drug_id'],

"supplier_id"=>$_POST['supplier_id'],

"batch_number"=>$_POST['batch_number'],

"manufacture_date"=>$_POST['manufacture_date'],

"expiry_date"=>$_POST['expiry_date'],

"quantity_received"=>$_POST['quantity_received'],

"quantity_remaining"=>$_POST['quantity_remaining'],

"buying_price"=>$_POST['buying_price'],

"selling_price"=>$_POST['selling_price'],

"invoice_number"=>$_POST['invoice_number']

];



if($controller->update($id,$data))
{

$_SESSION['success']="Batch updated successfully.";

header("Location:index.php");

exit();

}



}



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>
<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3 class="fw-bold">

<i class="fa fa-edit text-warning"></i>

Edit Batch

</h3>

<a href="index.php" class="btn btn-secondary">

<i class="fa fa-arrow-left"></i>

Back

</a>

</div>


<div class="card">

<div class="card-body">

<form method="POST">

<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label">Drug</label>

<select name="drug_id" class="form-control" required>

<?php foreach($drugs as $drug): ?>

<option
value="<?= $drug['drug_id']; ?>"
<?= $drug['drug_id']==$batch['drug_id']?'selected':''; ?>>

<?= htmlspecialchars($drug['drug_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">Supplier</label>

<select name="supplier_id" class="form-control" required>

<?php foreach($suppliers as $supplier): ?>

<option
value="<?= $supplier['supplier_id']; ?>"
<?= $supplier['supplier_id']==$batch['supplier_id']?'selected':''; ?>>

<?= htmlspecialchars($supplier['supplier_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">Batch Number</label>

<input
type="text"
name="batch_number"
class="form-control"
value="<?= htmlspecialchars($batch['batch_number']); ?>"
required>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">Invoice Number</label>

<input
type="text"
name="invoice_number"
class="form-control"
value="<?= htmlspecialchars($batch['invoice_number']); ?>">

</div>



<div class="col-md-6 mb-3">

<label class="form-label">Manufacture Date</label>

<input
type="date"
name="manufacture_date"
class="form-control"
value="<?= $batch['manufacture_date']; ?>"
required>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">Expiry Date</label>

<input
type="date"
name="expiry_date"
class="form-control"
value="<?= $batch['expiry_date']; ?>"
required>

</div>



<div class="col-md-4 mb-3">

<label class="form-label">Quantity Received</label>

<input
type="number"
name="quantity_received"
class="form-control"
value="<?= $batch['quantity_received']; ?>"
required>

</div>



<div class="col-md-4 mb-3">

<label class="form-label">Quantity Remaining</label>

<input
type="number"
name="quantity_remaining"
class="form-control"
value="<?= $batch['quantity_remaining']; ?>"
required>

</div>



<div class="col-md-4 mb-3">

<label class="form-label">Buying Price</label>

<input
type="number"
step="0.01"
name="buying_price"
class="form-control"
value="<?= $batch['buying_price']; ?>">

</div>



<div class="col-md-4 mb-3">

<label class="form-label">Selling Price</label>

<input
type="number"
step="0.01"
name="selling_price"
class="form-control"
value="<?= $batch['selling_price']; ?>">

</div>



<div class="col-md-8 mb-3">

<label class="form-label">Current Status</label>

<div class="form-control bg-light">

<strong>Stock:</strong>

<span class="badge bg-primary">

<?= htmlspecialchars($batch['stock_status']); ?>

</span>

&nbsp;&nbsp;

<strong>Expiry:</strong>

<span class="badge bg-success">

<?= htmlspecialchars($batch['expiry_status']); ?>

</span>

</div>

</div>


</div>


<button type="submit" name="update" class="btn btn-success">

<i class="fa fa-save"></i>

Update Batch

</button>

<a href="index.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</div>

<?php include "../../resources/views/layouts/footer.php"; ?>