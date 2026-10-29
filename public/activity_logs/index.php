<?php

require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/ActivityLogController.php";


$controller = new ActivityLogController();


$logs = $controller->index();



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">


<h3 class="fw-bold">

<i class="fa fa-history text-primary"></i>

Activity Logs

</h3>


</div>



<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">


<table class="table table-bordered table-hover">


<thead class="table-light">


<tr>

<th>#</th>

<th>User</th>

<th>Activity</th>

<th>Date</th>

</tr>


</thead>


<tbody>


<?php

$i = 1;


foreach($logs as $log):

?>


<tr>


<td>

<?= $i++; ?>

</td>



<td>

<?= htmlspecialchars($log['username']); ?>

</td>



<td>

<i class="fa fa-check-circle text-success"></i>

<?= htmlspecialchars($log['activity']); ?>

</td>



<td>

<?= date(
"d M Y H:i",
strtotime($log['created_at'])
); ?>

</td>



</tr>


<?php endforeach; ?>



<?php if(count($logs)==0): ?>


<tr>

<td colspan="4" class="text-center">

No activity records found

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


</div>


</div>


</div>


</div>


</div>


<?php include "../../resources/views/layouts/footer.php"; ?>