<?php

require_once __DIR__ .
"/../../config/database.php";


function db()
{

    $database = new Database();

    return $database->connect();

}

?>