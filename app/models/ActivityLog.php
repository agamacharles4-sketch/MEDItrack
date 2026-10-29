<?php

require_once __DIR__ . '/../Helpers/database_helper.php';


class ActivityLog
{

    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }


    public function create($user_id, $activity)
    {

        $stmt = $this->conn->prepare(
            "INSERT INTO activity_logs
            (user_id, activity)
            VALUES (?,?)"
        );


        return $stmt->execute([
            $user_id,
            $activity
        ]);

    }


    public function getAll()
    {

        $stmt = $this->conn->prepare(
            "SELECT 
                activity_logs.*,
                users.username

             FROM activity_logs

             INNER JOIN users

             ON activity_logs.user_id = users.user_id

             ORDER BY log_id DESC"
        );


        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}