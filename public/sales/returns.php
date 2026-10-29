<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/SaleReturnController.php";


$controller = new SaleReturnController();


$returns = $controller->all();



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";


?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">


<h3 class="fw-bold">

<i class="fa fa-undo text-warning"></i>

Sales Returns

</h3>



<a href="index.php"
class="btn btn-primary">

<i class="fa fa-arrow-left"></i>

Back to Sales

</a>


</div>





<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">


<table class="table table-hover">


<thead>

<tr>

<th>
Return No.
</th>


<th>
Sale No.
</th>


<th>
Customer
</th>


<th>
Amount
</th>


<th>
Reason
</th>


<th>
Date
</th>


<th>
Processed By
</th>


</tr>


</thead>



<tbody>


<?php foreach($returns as $return): ?>


<tr>


<td>

<?= $return['return_number']; ?>

</td>



<td>

<?= $return['sale_number']; ?>

</td>



<td>

<?= htmlspecialchars(
$return['customer_name'] ?? "Walk-in Customer"
); ?>

</td>



<td>

UGX <?= number_format(
$return['total_amount']
); ?>

</td>



<td>

<?= htmlspecialchars(
$return['reason']
); ?>

</td>



<td>

<?= date(
"d M Y H:i",
strtotime($return['return_date'])
); ?>

</td>



<td>

<?= htmlspecialchars(
$return['first_name']." ".$return['last_name']
); ?>

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