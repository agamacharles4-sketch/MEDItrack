<?php

require_once "../../config/session.php";

checkLogin();


require_once "../../app/Controllers/CategoryController.php";


$controller=new CategoryController();


$categories=$controller->index();



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between mb-4">


<h3>

<i class="fa fa-folder text-primary"></i>

Categories

</h3>


<a href="create.php" class="btn btn-primary">

<i class="fa fa-plus"></i>

Add Category

</a>


</div>



<div class="card shadow-sm">

<div class="card-body">


<table class="table table-hover">


<tr>

<th>#</th>

<th>Name</th>

<th>Description</th>

<th>Action</th>

</tr>



<?php $i=1; foreach($categories as $cat): ?>


<tr>

<td><?= $i++; ?></td>


<td>
<?= htmlspecialchars($cat['category_name']); ?>
</td>


<td>
<?= htmlspecialchars($cat['description'] ?? ''); ?>
</td>


<td>

<a href="edit.php?id=<?= $cat['category_id']; ?>"
class="btn btn-sm btn-warning">

<i class="fa fa-edit"></i>

Edit

</a>


</td>


</tr>


<?php endforeach; ?>


</table>


</div>

</div>


</div>

</div>


<?php include "../../resources/views/layouts/footer.php"; ?>