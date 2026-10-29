<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/UserController.php";


$controller = new UserController();


$user = $controller->profile(
    $_SESSION['user_id']
);



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-user-circle text-primary"></i>

My Profile

</h3>



<div class="card shadow-sm">


<div class="card-body">


<div class="row">


<div class="col-md-4 text-center">


<?php if(!empty($user['photo'])): ?>

<img 
src="/MEDItrack/uploads/users/<?= htmlspecialchars($user['photo']); ?>"
class="rounded-circle mb-3"
width="120"
height="120">

<?php else: ?>

<i class="fa fa-user-circle fa-7x text-primary mb-3"></i>

<?php endif; ?>


<h4>

<?= htmlspecialchars(
$user['first_name']." ".$user['last_name']
); ?>

</h4>


<span class="badge bg-primary">

Pharmacist Administrator

</span>


</div>



<div class="col-md-8">


<table class="table table-bordered">


<tr>

<th>Username</th>

<td>
<?= htmlspecialchars($user['username']); ?>
</td>

</tr>



<tr>

<th>Email</th>

<td>
<?= htmlspecialchars($user['email'] ?? 'Not set'); ?>
</td>

</tr>



<tr>

<th>Phone</th>

<td>
<?= htmlspecialchars($user['phone'] ?? 'Not set'); ?>
</td>

</tr>



<tr>

<th>Status</th>

<td>

<span class="badge bg-success">

<?= htmlspecialchars($user['status']); ?>

</span>

</td>

</tr>



<tr>

<th>Created</th>

<td>

<?= date(
"d M Y",
strtotime($user['created_at'])
); ?>

</td>

</tr>


</table>

<a href="password.php"
class="btn btn-danger">

<i class="fa fa-key"></i>

Change Password

</a>
<a href="edit.php" class="btn btn-primary">

<i class="fa fa-edit"></i>

Edit Profile

</a>


</div>


</div>


</div>


</div>



</div>

</div>


<?php include "../../resources/views/layouts/footer.php"; ?>