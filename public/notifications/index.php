<?php

require_once "../../config/session.php";
checkLogin();

// Mark notifications as viewed


require_once "../../app/Controllers/NotificationController.php";

$controller = new NotificationController();
$controller->markAllRead();
$notifications = $controller->index();

include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

    <h3 class="fw-bold">
        <i class="fa fa-bell text-danger"></i>
        Notifications
    </h3>

    <span class="badge bg-primary fs-6">
        <?= count($notifications); ?> Notification<?= count($notifications)!=1 ? "s" : ""; ?>
    </span>

</div>

<div class="card shadow-sm border-0">

<div class="card-header bg-white">

<h5 class="mb-0">

<i class="fa fa-list"></i>

Expiry & Stock Alerts

</h5>

</div>

<div class="card-body">

<?php if(count($notifications)>0): ?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-light">

<tr>

<th width="60">#</th>

<th width="180">Alert Type</th>

<th>Notification</th>

<th width="120">Status</th>

</tr>

</thead>

<tbody>

<?php $i=1; foreach($notifications as $notification): ?>

<tr>

<td><?= $i++; ?></td>

<td>

<?php

$class = "secondary";

switch($notification['notification_type'])
{
    case "Expired":
        $class = "danger";
        break;

    case "Expiring Soon":
        $class = "warning";
        break;

    case "Low Stock":
        $class = "warning";
        break;

    case "Out of Stock":
        $class = "danger";
        break;
}

?>

<span class="badge bg-<?= $class; ?>">

<?= htmlspecialchars($notification['notification_type']); ?>

</span>

</td>

<td>

<?= htmlspecialchars($notification['message']); ?>

</td>

<td>

<span class="badge bg-success">

Viewed

</span>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php else: ?>

<div class="alert alert-success mb-0">

<i class="fa fa-check-circle"></i>

There are currently no notifications.

</div>

<?php endif; ?>

</div>

</div>

</div>

</div>

<?php include "../../resources/views/layouts/footer.php"; ?>