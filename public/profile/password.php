<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/UserController.php";


$controller = new UserController();


$message = "";


if(isset($_POST['change_password']))
{


    $current = $_POST['current_password'];

    $new = $_POST['new_password'];

    $confirm = $_POST['confirm_password'];



    $oldPassword = $controller->getPassword(
        $_SESSION['user_id']
    );



    if(!password_verify($current,$oldPassword))
    {

        $message = "

        <div class='alert alert-danger'>
        Current password is incorrect.
        </div>

        ";

    }


    elseif($new != $confirm)
    {

        $message = "

        <div class='alert alert-danger'>
        New passwords do not match.
        </div>

        ";

    }


    elseif(strlen($new)<6)
    {

        $message = "

        <div class='alert alert-warning'>
        Password must contain at least 6 characters.
        </div>

        ";

    }


    else
    {


        $controller->updatePassword(

            $_SESSION['user_id'],

            $new

        );


        $message = "

        <div class='alert alert-success'>
        Password changed successfully.
        </div>

        ";


    }


}




include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">


<div class="container-fluid">



<h3 class="fw-bold mb-4">

<i class="fa fa-lock text-danger"></i>

Change Password

</h3>




<div class="card shadow-sm">


<div class="card-body">


<?= $message; ?>



<form method="POST">



<div class="mb-3">

<label>
Current Password
</label>


<input

type="password"

name="current_password"

class="form-control"

required

>

</div>




<div class="mb-3">

<label>
New Password
</label>


<input

type="password"

name="new_password"

class="form-control"

required

>

</div>




<div class="mb-3">

<label>
Confirm New Password
</label>


<input

type="password"

name="confirm_password"

class="form-control"

required

>

</div>



<button

class="btn btn-danger"

name="change_password"

type="submit"

>

<i class="fa fa-key"></i>

Change Password

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