<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/BatchController.php";

header('Content-Type: application/json');

if(!isset($_GET['drug_id']))
{
    echo json_encode(null);
    exit;
}

$controller = new BatchController();

echo json_encode(
    $controller->availableBatch($_GET['drug_id'])
);