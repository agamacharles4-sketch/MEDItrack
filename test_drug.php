<?php

require_once "app/Models/Drug.php";

$drug = new Drug();

print_r($drug->all());

?>