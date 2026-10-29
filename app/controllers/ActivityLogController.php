<?php

require_once __DIR__ . "/../Models/ActivityLog.php";


class ActivityLogController
{

    private $log;


    public function __construct()
    {
        $this->log = new ActivityLog();
    }



    public function create($user_id,$activity)
    {
        return $this->log->create(
            $user_id,
            $activity
        );
    }



    public function index()
    {
        return $this->log->getAll();
    }


}