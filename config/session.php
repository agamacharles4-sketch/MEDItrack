<?php

session_name('MEDITRACK_SESSION');

session_start();


function checkLogin()
{

    if(!isset($_SESSION['user_id']))
    {

        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

        header("Location: /MEDItrack/login.php");

        exit();

    }

}

?>