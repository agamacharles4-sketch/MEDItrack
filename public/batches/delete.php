<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/BatchController.php";

if (!isset($_GET['id'])) {
    die("Invalid Batch.");
}

$id = intval($_GET['id']);

$controller = new BatchController();

if ($controller->delete($id)) {

    $_SESSION['success'] =
    "Batch deleted successfully.";

} else {

    $_SESSION['error'] =
    "Cannot delete this batch because it has sales history.";

}

header("Location:index.php");
exit();