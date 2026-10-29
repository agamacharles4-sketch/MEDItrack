<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/BatchController.php";
require_once "../../app/Models/Drug.php";
require_once "../../app/Models/Supplier.php";


$batchController = new BatchController();


$drugModel = new Drug();

$supplierModel = new Supplier();


$drugs = $drugModel->all();

$suppliers = $supplierModel->all();



$message = "";


if(isset($_POST['save']))
{


$expiry_date = $_POST['expiry_date'];

$today = date("Y-m-d");

$expiry_status = "Valid";


if($expiry_date < $today)
{

    $expiry_status = "Expired";

}
else
{

    $days = (strtotime($expiry_date)-strtotime($today))/86400;


    if($days <= 30)
    {
        $expiry_status="Expiring Soon";
    }

}



$quantity = $_POST['quantity_received'];


$stock_status="In Stock";


if($quantity == 0)
{

    $stock_status="Out of Stock";

}
elseif($quantity <= 10)
{

    $stock_status="Low Stock";

}



$data = [

"drug_id"=>$_POST['drug_id'],

"supplier_id"=>$_POST['supplier_id'],

"batch_number"=>$_POST['batch_number'],

"manufacture_date"=>$_POST['manufacture_date'],

"expiry_date"=>$expiry_date,

"quantity_received"=>$quantity,

"buying_price"=>$_POST['buying_price'],

"selling_price"=>$_POST['selling_price'],

"invoice_number"=>$_POST['invoice_number'],

"expiry_status"=>$expiry_status,

"stock_status"=>$stock_status

];



$result = $batchController->store($data);



if($result)
{

$_SESSION['success']="Batch added successfully.";

header("Location:index.php");

exit();

}
else
{

$message="<div class='alert alert-danger'>
Failed to add batch.
</div>";

}


}



include "../../resources/views/layouts/header.php";

include "../../resources/views/layouts/sidebar.php";

include "../../resources/views/layouts/navbar.php";


?>


<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-box text-primary"></i>

Add New Batch

</h3>



<?= $message ?>


<div class="card">


<div class="card-body">


<form method="POST">


<div class="row">


<div class="col-md-6 mb-3">

<label>Drug</label>

<select name="drug_id" class="form-control" required>

<option value="">
Select Drug
</option>


<?php foreach($drugs as $drug): ?>


<option value="<?= $drug['drug_id']; ?>">

<?= htmlspecialchars($drug['drug_name']); ?>

</option>


<?php endforeach; ?>


</select>


</div>




<div class="col-md-6 mb-3">

<label>Supplier</label>


<select name="supplier_id" class="form-control" required>


<option value="">
Select Supplier
</option>


<?php foreach($suppliers as $supplier): ?>


<option value="<?= $supplier['supplier_id']; ?>">


<?= htmlspecialchars($supplier['supplier_name']); ?>


</option>


<?php endforeach; ?>


</select>


</div>




<div class="col-md-6 mb-3">

<label>Batch Number</label>


<input
type="text"
name="batch_number"
class="form-control"
required>


</div>




<div class="col-md-6 mb-3">

<label>Invoice Number</label>


<input
type="text"
name="invoice_number"
class="form-control">


</div>




<div class="col-md-6 mb-3">

<label>Manufacture Date</label>


<input
type="date"
name="manufacture_date"
class="form-control"
required>


</div>




<div class="col-md-6 mb-3">

<label>Expiry Date</label>


<input
type="date"
name="expiry_date"
class="form-control"
required>


</div>




<div class="col-md-4 mb-3">

<label>Quantity Received</label>


<input
type="number"
name="quantity_received"
class="form-control"
required>


</div>




<div class="col-md-4 mb-3">

<label>Buying Price</label>


<input
type="number"
step="0.01"
name="buying_price"
class="form-control">


</div>




<div class="col-md-4 mb-3">

<label>Selling Price</label>


<input
type="number"
step="0.01"
name="selling_price"
class="form-control">


</div>


</div>



<button name="save"
class="btn btn-success">

<i class="fa fa-save"></i>

Save Batch

</button>



<a href="index.php"
class="btn btn-secondary">

Cancel

</a>



</form>


</div>

</div>


</div>

</div>


<?php

include "../../resources/views/layouts/footer.php";

?>