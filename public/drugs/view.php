<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/DrugController.php";

$controller = new DrugController();


if(!isset($_GET['id']))
{
    die("Invalid Drug.");
}


$id = intval($_GET['id']);


$drug = $controller->details($id);


if(!$drug)
{
    die("Drug not found.");
}



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">


<h3 class="fw-bold">

<i class="fa fa-pills text-primary"></i>

Drug Details

</h3>


<a href="index.php" class="btn btn-secondary">

<i class="fa fa-arrow-left"></i>

Back

</a>


</div>




<div class="row">



<div class="col-md-6">


<div class="card mb-4 details-card">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="fa fa-capsules"></i>

Basic Information

</h5>

</div>



<div class="card-body">


<p>
<strong>Drug Name:</strong><br>

<?= htmlspecialchars($drug['drug_name']); ?>

</p>



<p>
<strong>Generic Name:</strong><br>

<?= htmlspecialchars($drug['generic_name']); ?>

</p>



<p>
<strong>Category:</strong><br>

<?= htmlspecialchars($drug['category_name']); ?>

</p>



<p>
<strong>Unit:</strong><br>

<?= htmlspecialchars($drug['unit_name']); ?>

</p>



<p>
<strong>Dosage Form:</strong><br>

<?= htmlspecialchars($drug['dosage_form']); ?>

</p>



<p>
<strong>Strength:</strong><br>

<?= htmlspecialchars($drug['strength']); ?>

</p>


<p>
<strong>Manufacturer:</strong><br>

<?= htmlspecialchars($drug['manufacturer']); ?>

</p>


<p>
<strong>Status:</strong><br>


<?php if($drug['status']=="Active"): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-secondary">

Inactive

</span>

<?php endif; ?>


</p>



</div>

</div>


</div>






<div class="col-md-6">


<div class="card mb-4 details-card">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="fa fa-chart-line"></i>

Stock Information

</h5>

</div>



<div class="card-body">


<div class="row">


<div class="col-md-6 mb-3">


<div class="card bg-light">

<div class="card-body text-center">


<h6>
Current Stock
</h6>


<h2 class="text-success">

<?= $drug['total_stock']; ?>

</h2>


<?= htmlspecialchars($drug['unit_name']); ?>


</div>

</div>


</div>





<div class="col-md-6 mb-3">


<div class="card bg-light">

<div class="card-body text-center">


<h6>

Total Batches

</h6>


<h2 class="text-primary">

<?= $drug['total_batches']; ?>

</h2>


</div>

</div>


</div>



</div>



<hr>



<p>

<strong>Minimum Stock Level:</strong>

<?= $drug['minimum_stock']; ?>

</p>



<p>

<strong>Description:</strong><br>

<?= nl2br(htmlspecialchars($drug['description'])); ?>

</p>


</div>


</div>


</div>


</div>


</div>


</div>


<?php

include "../../resources/views/layouts/footer.php";

?>