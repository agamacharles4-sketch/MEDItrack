<?php

require_once "app/Controllers/DrugController.php";


$controller = new DrugController();


$drugs = $controller->index();


print_r($drugs);

?>