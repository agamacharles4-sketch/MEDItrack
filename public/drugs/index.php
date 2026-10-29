<?php

require_once "../../config/session.php";

checkLogin();


require_once "../../app/Controllers/DrugController.php";


$controller = new DrugController();


$drugs = $controller->index();



include "../../resources/views/layouts/header.php";

include "../../resources/views/layouts/sidebar.php";

include "../../resources/views/layouts/navbar.php";


?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">


<h3 class="fw-bold">

<i class="fa fa-pills text-primary"></i>

Drug Management

</h3>


<a href="create.php" class="btn btn-primary">

<i class="fa fa-plus"></i>

Add Drug

</a>


</div>



<div class="card">


<div class="card-body">
<form method="GET" class="mb-3">

<div class="input-group">

<input 
type="text"
name="search"
class="form-control"
placeholder="Search drug name, generic name or manufacturer..."
value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
>

<button class="btn btn-primary">

<i class="fa fa-search"></i>

Search

</button>

</div>

</form>
<form method="GET" class="row mb-3">


<div class="col-md-5">

<select name="category" class="form-control">

<option value="">
All Categories
</option>


<?php

require_once "../../app/Models/Category.php";

$catModel=new Category();

$cats=$catModel->all();


foreach($cats as $cat):

?>

<option 
value="<?= $cat['category_id']; ?>"
<?= 
(isset($_GET['category']) &&
$_GET['category']==$cat['category_id'])
?'selected':''
?>
>

<?= htmlspecialchars($cat['category_name']); ?>

</option>


<?php endforeach; ?>


</select>

</div>


<div class="col-md-5">


<select name="status" class="form-control">


<option value="">
All Status
</option>


<option value="Active"
<?= 
($_GET['status']??'')=="Active"
?'selected':''
?>
>
Active
</option>


<option value="Inactive"
<?= 
($_GET['status']??'')=="Inactive"
?'selected':''
?>
>
Inactive
</option>


</select>


</div>


<div class="col-md-2">

<button class="btn btn-success w-100">

Filter

</button>

</div>


</form>
<div class="table-responsive">


<table class="table table-hover">


<thead>

<tr>

<th>#</th>

<th>Drug Name</th>

<th>Category</th>

<th>Unit</th>

<th>Strength</th>

<th>Manufacturer</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>



<tbody>


<?php foreach($drugs as $drug): ?>


<tr>


<td>

<?= $drug['drug_id']; ?>

</td>


<td>

<strong>

<?= htmlspecialchars($drug['drug_name']); ?>

</strong>

<br>

<small class="text-muted">

<?= htmlspecialchars($drug['generic_name']); ?>

</small>

</td>



<td>

<?= htmlspecialchars($drug['category_name']); ?>

</td>



<td>

<?= htmlspecialchars($drug['unit_name']); ?>

</td>



<td>

<?= htmlspecialchars($drug['strength']); ?>

</td>



<td>

<?= htmlspecialchars($drug['manufacturer']); ?>

</td>



<td>


<?php if($drug['status']=="Active"): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-secondary">

Inactive

</span>

<?php endif; ?>


</td>



<td>
<a href="view.php?id=<?= $drug['drug_id']; ?>"
class="btn btn-sm btn-info">

<i class="fa fa-eye"></i>

</a>

<a href="edit.php?id=<?= $drug['drug_id']; ?>" 
class="btn btn-sm btn-warning">

<i class="fa fa-edit"></i>

</a>



<a onclick="return confirm('Delete this drug?')"
href="delete.php?id=<?= $drug['drug_id']; ?>"
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