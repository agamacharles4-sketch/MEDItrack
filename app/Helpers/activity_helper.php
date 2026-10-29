<?php

require_once __DIR__ . '/../models/ActivityLog.php';


function logActivity($activity)
{

    if(isset($_SESSION['user_id']))
    {

        $log = new ActivityLog();

        $log->create(
            $_SESSION['user_id'],
            $activity
        );

    }

}