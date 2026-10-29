<?php

require_once "../../config/session.php";

checkLogin();


require_once "../../app/Models/Category.php";


if(!isset($_GET['id']))
{
    header("Location: index.php");
    exit();
}


$id = intval($_GET['id']);


$category = new Category();


$data = $category->find($id);



if(!$data)
{
    die("Category not found");
}



if(isset($_POST['update']))
{

    $name = trim($_POST['category_name']);

    $description = trim($_POST['description']);



    $category->update(
        $id,
        $name,
        $description
    );


    header("Location:index.php");

    exit();

}



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-edit text-warning"></i>

Edit Category

</h3>



<div class="card shadow-sm">


<div class="card-body">


<form method="POST">



<div class="mb-3">

<label class="form-label">
Category Name
</label>


<input

type="text"

name="category_name"

class="form-control"

value="<?= htmlspecialchars($data['category_name'] ?? ''); ?>"

required>

</div>



<div class="mb-3">

<label class="form-label">

Description

</label>


<textarea

name="description"

class="form-control"

rows="4"><?= htmlspecialchars($data['description'] ?? ''); ?></textarea>


</div>



<button

type="submit"

name="update"

class="btn btn-success">

<i class="fa fa-save"></i>

Update Category

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