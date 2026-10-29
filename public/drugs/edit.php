<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if (!isset($_GET['id'])) {
    die("Invalid Drug.");
}

$id = intval($_GET['id']);

$drug = $drugController->find($id);

if (!$drug) {
    die("Drug not found.");
}

$message = "";

if (isset($_POST['update'])) {

    $data = [

        "category_id"    => $_POST['category_id'],
        "unit_id"        => $_POST['unit_id'],
        "drug_name"      => trim($_POST['drug_name']),
        "generic_name"   => trim($_POST['generic_name']),
        "dosage_form"    => trim($_POST['dosage_form']),
        "strength"       => trim($_POST['strength']),
        "manufacturer"   => trim($_POST['manufacturer']),
        "minimum_stock"  => $_POST['minimum_stock'],
        "description"    => trim($_POST['description']),
        "status"         => $_POST['status']

    ];

    $result = $drugController->update($id, $data);

    if ($result === "exists") {

        $message = "<div class='alert alert-warning'>
                        Another drug with this name already exists.
                    </div>";

    } elseif ($result) {

        $_SESSION['success'] = "Drug updated successfully.";

        header("Location:index.php");

        exit();

    } else {

        $message = "<div class='alert alert-danger'>
                        Failed to update drug.
                    </div>";

    }

    $drug = $drugController->find($id);
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

Edit Drug

</h3>

<a href="index.php" class="btn btn-secondary">

<i class="fa fa-arrow-left"></i>

Back

</a>

</div>

<?= $message ?>

<div class="card">

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">Category</label>

<select name="category_id" class="form-control" required>

<?php foreach($categories as $category): ?>

<option
value="<?= $category['category_id']; ?>"
<?= ($drug['category_id']==$category['category_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($category['category_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Unit</label>

<select name="unit_id" class="form-control" required>

<?php foreach($units as $unit): ?>

<option
value="<?= $unit['unit_id']; ?>"
<?= ($drug['unit_id']==$unit['unit_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($unit['unit_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Drug Name</label>

<input
type="text"
name="drug_name"
class="form-control"
value="<?= htmlspecialchars($drug['drug_name']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Generic Name</label>

<input
type="text"
name="generic_name"
class="form-control"
<?= htmlspecialchars($drug['generic_name'] ?? ''); ?>

<div class="col-md-4 mb-3">

<label class="form-label">Dosage Form</label>

<input
type="text"
name="dosage_form"
class="form-control"
value="<?= htmlspecialchars($drug['dosage_form']); ?>">

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Strength</label>

<input
type="text"
name="strength"
class="form-control"
value="<?= htmlspecialchars($drug['strength']); ?>">

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Manufacturer</label>

<input
type="text"
name="manufacturer"
class="form-control"
value="<?= htmlspecialchars($drug['manufacturer']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Minimum Stock</label>

<input
type="number"
name="minimum_stock"
class="form-control"
value="<?= $drug['minimum_stock']; ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Status</label>

<select
name="status"
class="form-control">

<option
value="Active"
<?= ($drug['status']=="Active") ? 'selected' : ''; ?>>

Active

</option>

<option
value="Inactive"
<?= ($drug['status']=="Inactive") ? 'selected' : ''; ?>>

Inactive

</option>

</select>

</div>

<div class="col-md-12 mb-3">

<label class="form-label">Description</label>

<textarea
name="description"
class="form-control"
rows="4"><?= htmlspecialchars($drug['description']); ?></textarea>

</div>

</div>

<button
type="submit"
name="update"
class="btn btn-success">

<i class="fa fa-save"></i>

Update Drug

</button>

<a
href="index.php"
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