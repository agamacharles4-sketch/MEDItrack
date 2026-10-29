<?php

require_once "../../app/Controllers/BatchController.php";

$controller = new BatchController();

echo "<pre>";

print_r($controller->index());

echo "</pre>";