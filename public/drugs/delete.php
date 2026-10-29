<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/DrugController.php";

$controller = new DrugController();


if(!isset($_GET['id']))
{
    die("Invalid Drug.");
}


$id = intval($_GET['id']);


if(!$controller->canDelete($id))
{

    $_SESSION['error'] = 
    "Cannot delete this drug because it has batch records.";

    header("Location:index.php");

    exit();

}


if($controller->delete($id))
{

    $_SESSION['success'] =
    "Drug deleted successfully.";

}
else
{

    $_SESSION['error'] =
    "Failed to delete drug.";

}


header("Location:index.php");

exit();

?>