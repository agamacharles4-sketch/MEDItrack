<?php

require_once __DIR__ . "/../Helpers/database_helper.php";

class Unit
{
    private $conn;

    public function __construct()
    {
        $this->conn = db();
    }

    // Get all units
    public function all()
    {
        $stmt = $this->conn->query("
            SELECT *
            FROM units
            ORDER BY unit_name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find one unit
    public function find($id)
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM units
            WHERE unit_id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}