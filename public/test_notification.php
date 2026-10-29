<?php

require_once "../app/Controllers/NotificationController.php";


$controller=new NotificationController();


echo "<pre>";

print_r(
$controller->index()
);

echo "</pre>";