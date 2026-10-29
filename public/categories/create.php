<?php

require_once "../../config/session.php";

checkLogin();


require_once "../../app/Controllers/CategoryController.php";


require_once "../../app/Models/Category.php";


$message="";


if(isset($_POST['save']))
{

    $category = new Category();


    $name = trim($_POST['category_name']);

    $description = trim($_POST['description']);



    if($category->create($name,$description))
    {

        header("Location: index.php");

        exit();

    }
    else
    {

        $message="Failed to save category";

    }

}



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>



<div class="main-content">

<div class="container-fluid">


<h3 class="mb-4">

<i class="fa fa-folder-plus text-primary"></i>

Add Category

</h3>



<div class="card shadow-sm">

<div class="card-body">


<?php if($message): ?>

<div class="alert alert-danger">

<?= $message; ?>

</div>

<?php endif; ?>



<form method="POST">


<div class="mb-3">

<label class="form-label">

Category Name

</label>


<input

type="text"

name="category_name"

class="form-control"

required>

</div>




<div class="mb-3">

<label class="form-label">

Description

</label>


<textarea

name="description"

class="form-control"

rows="4"></textarea>


</div>



<button

class="btn btn-success"

name="save">


<i class="fa fa-save"></i>

Save Category

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



<?php include "../../resources/views/layouts/footer.php"; ?>