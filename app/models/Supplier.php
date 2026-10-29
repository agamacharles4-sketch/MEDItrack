<?php

require_once __DIR__ . "/../Helpers/database_helper.php";

class Supplier
{
    private $conn;

    public function __construct()
    {
        $this->conn = db();
    }

    public function all()
    {
        $stmt = $this->conn->query("

        SELECT *

        FROM suppliers

        WHERE status='Active'

        ORDER BY supplier_name ASC

        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("

        SELECT *

        FROM suppliers

        WHERE supplier_id=?

        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}