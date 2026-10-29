<?php

require_once __DIR__ . "/../../../app/Controllers/NotificationController.php";


$notificationController = new NotificationController();

$notificationController->generate();
$notifications = $notificationController->index();

$notificationCount = $notificationController->unreadCount();

?>
<div class="topbar">

<div>

<h4 class="mb-0">

<i class="fa-solid fa-stethoscope text-success"></i>

Drug Expiry Tracking System

</h4>

</div>

<div class="d-flex align-items-center gap-4">

<div>
<div class="dropdown">

<a href="#" 
class="text-dark position-relative"
data-bs-toggle="dropdown">

<i class="fa fa-bell fa-lg"></i>


<?php if($notificationCount > 0): ?>

<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

<?= $notificationCount; ?>

</span>

<?php endif; ?>


</a>


<div class="dropdown-menu dropdown-menu-end p-3 notification-menu">


<h6 class="fw-bold">

System Notifications

</h6>


<hr>


<?php if(count($notifications)>0): ?>


<?php foreach($notifications as $notification): ?>


<?php

$class = "info";

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

    default:
        $class = "info";
}

?>

<div class="alert alert-<?= $class; ?>">


<strong>

<?= htmlspecialchars($notification['notification_type']); ?>

</strong>


<br>


<?= htmlspecialchars($notification['message']); ?>


</div>


<?php endforeach; ?>


<?php else: ?>


<p class="text-success">

No notifications

</p>


<?php endif; ?>


<a href="/MEDItrack/public/notifications/"
class="btn btn-primary btn-sm w-100">

View All Notifications

</a>


</div>


</div>

</div>

<div>

<i class="fa fa-user-circle fa-lg text-primary"></i>

<strong>

<?= htmlspecialchars($_SESSION['username'] ?? 'Technician') ?>

</strong>

</div>

</div>

</div>
<li>
<a href="/MEDItrack/public/profile/">
<i class="fa fa-user"></i>
Profile
</a>
</li>
<li><a href="/MEDItrack/logout.php">
<i class="fa fa-sign-out-alt"></i> Logout
</a></li>