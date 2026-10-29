<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/UserController.php";


$controller = new UserController();


$user = $controller->profile(
    $_SESSION['user_id']
);



if(isset($_POST['update_profile']))
{

    $result = $controller->update(

        $_SESSION['user_id'],

        $_POST['first_name'],

        $_POST['last_name'],

        $_POST['username']

    );


    if($result)
    {

        header("Location:index.php");

        exit;

    }

}



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-edit text-primary"></i>

Edit Profile

</h3>



<div class="card shadow-sm">


<div class="card-body">


<form method="POST">



<div class="row">


<div class="col-md-6 mb-3">


<label>
First Name
</label>


<input 
type="text"
name="first_name"
class="form-control"
value="<?= htmlspecialchars($user['first_name']); ?>"
required>


</div>




<div class="col-md-6 mb-3">


<label>
Last Name
</label>


<input 
type="text"
name="last_name"
class="form-control"
value="<?= htmlspecialchars($user['last_name']); ?>"
required>


</div>





<div class="col-md-6 mb-3">


<label>
Username
</label>


<input 
type="text"
name="username"
class="form-control"
value="<?= htmlspecialchars($user['username']); ?>"
required>


</div>




<div class="col-md-6 mb-3">


<label>
Email
</label>


<input 
type="email"
class="form-control"
value="<?= htmlspecialchars($user['email']); ?>"
disabled>


</div>



<div class="col-md-6 mb-3">


<label>
Phone
</label>


<input 
type="text"
class="form-control"
value="<?= htmlspecialchars($user['phone']); ?>"
disabled>


</div>



</div>



<button 
type="submit"
name="update_profile"
class="btn btn-success">


<i class="fa fa-save"></i>

Save Changes


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