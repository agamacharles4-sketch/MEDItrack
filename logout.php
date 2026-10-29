<?php

require_once "config/session.php";

require_once "app/Models/ActivityLog.php";


// Save logout activity before destroying session

if(isset($_SESSION['user_id']))
{

    $activity = new ActivityLog();


    $activity->create(

        $_SESSION['user_id'],

        "Logged out of MEDItrack system"

    );

}

unset($_SESSION['notifications_viewed']);
// Destroy session

session_destroy();


// Return to login page



session_name('MEDITRACK_SESSION');

session_start();


require_once __DIR__ . "/app/Models/ActivityLog.php";



if(isset($_SESSION['user_id']))
{

    $activity = new ActivityLog();


    $activity->create(

        $_SESSION['user_id'],

        "Logged out of MEDItrack system"

    );

}



session_unset();

session_destroy();



header("Location: /MEDItrack/login.php");

exit();

?>

exit;

?>