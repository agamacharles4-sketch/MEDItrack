<?php

require_once "../../config/session.php";

checkLogin();


require_once "../../app/Controllers/DrugController.php";

require_once "../../app/Models/Category.php";

require_once "../../app/Models/Unit.php";


$drugController = new DrugController();


$categoryModel = new Category();

$unitModel = new Unit();


$categories = $categoryModel->all();

$units = $unitModel->all();



$message="";


if(isset($_POST['save']))
{

    $data=[

        "category_id"=>$_POST['category_id'],

        "unit_id"=>$_POST['unit_id'],

        "drug_name"=>$_POST['drug_name'],

        "generic_name"=>$_POST['generic_name'],

        "dosage_form"=>$_POST['dosage_form'],

        "strength"=>$_POST['strength'],

        "manufacturer"=>$_POST['manufacturer'],

        "minimum_stock"=>$_POST['minimum_stock'],

        "description"=>$_POST['description'],

        "status"=>$_POST['status']

    ];


$result = $drugController->store($data);

if ($result === "exists") {

    $message = "
    <div class='alert alert-warning'>
        This drug already exists.
    </div>";

} elseif ($result) {

    $_SESSION['success'] = "Drug added successfully.";

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


<h3 class="fw-bold mb-4">

<i class="fa fa-plus text-primary"></i>

Add New Drug

</h3>



<div class="card">


<div class="card-body">

<?= $message ?? "" ?>
<form method="POST">


<div class="row">



<div class="col-md-6 mb-3">

<label>Category</label>


<select name="category_id" class="form-control" required>


<option value="">Select Category</option>


<?php foreach($categories as $category): ?>


<option value="<?= $category['category_id']; ?>">

<?= htmlspecialchars($category['category_name']); ?>

</option>


<?php endforeach; ?>


</select>


</div>




<div class="col-md-6 mb-3">

<label>Unit</label>


<select name="unit_id" class="form-control" required>


<option value="">Select Unit</option>


<?php foreach($units as $unit): ?>


<option value="<?= $unit['unit_id']; ?>">

<?= htmlspecialchars($unit['unit_name']); ?>

</option>


<?php endforeach; ?>


</select>


</div>




<div class="col-md-6 mb-3">

<label>Drug Name</label>

<input type="text"
name="drug_name"
class="form-control"
required>

</div>




<div class="col-md-6 mb-3">

<label>Generic Name</label>

<input type="text"
name="generic_name"
class="form-control">

</div>



<div class="col-md-4 mb-3">

<label>Dosage Form</label>

<input type="text"
name="dosage_form"
class="form-control">

</div>



<div class="col-md-4 mb-3">

<label>Strength</label>

<input type="text"
name="strength"
class="form-control">

</div>




<div class="col-md-4 mb-3">

<label>Manufacturer</label>

<input type="text"
name="manufacturer"
class="form-control">

</div>




<div class="col-md-6 mb-3">

<label>Minimum Stock</label>

<input type="number"
name="minimum_stock"
class="form-control"
value="10">

</div>




<div class="col-md-6 mb-3">

<label>Status</label>


<select name="status" class="form-control">


<option value="Active">

Active

</option>


<option value="Inactive">

Inactive

</option>


</select>


</div>




<div class="col-md-12 mb-3">

<label>Description</label>


<textarea name="description"
class="form-control"></textarea>


</div>



</div>


<button name="save"
class="btn btn-success">

<i class="fa fa-save"></i>

Save Drug

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