<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/CategoryController.php";


$controller = new CategoryController();


$id = $_GET['id'] ?? null;


if(!$id)
{
    header("Location:index.php");
    exit();
}


$result = $controller->delete($id);


if($result['success'])
{
    $_SESSION['success'] = $result['message'];
}
else
{
    $_SESSION['error'] = $result['message'];
}


header("Location:index.php");
exit();