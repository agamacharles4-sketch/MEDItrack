<?php

require_once __DIR__ . "/../Helpers/database_helper.php";

class Dashboard
{
    private $conn;

    public function __construct()
    {
        $this->conn = db();
    }

    private function getValue($sql)
    {
        return $this->conn->query($sql)->fetchColumn();
    }

    public function totalCategories()
    {
        return $this->getValue("SELECT COUNT(*) FROM categories");
    }

    public function totalDrugs()
    {
        return $this->getValue("SELECT COUNT(*) FROM drugs");
    }

    public function totalSuppliers()
    {
        return $this->getValue("SELECT COUNT(*) FROM suppliers");
    }

    public function totalBatches()
    {
        return $this->getValue("SELECT COUNT(*) FROM batches");
    }

    public function totalStock()
    {
        return $this->getValue("
            SELECT COALESCE(SUM(quantity_remaining),0)
            FROM batches
        ");
    }

    public function expiringSoon()
    {
        return $this->getValue("
            SELECT COUNT(*)
            FROM batches
            WHERE expiry_date BETWEEN CURDATE()
            AND DATE_ADD(CURDATE(),INTERVAL 30 DAY)
            AND quantity_remaining>0
        ");
    }

    public function expired()
    {
        return $this->getValue("
            SELECT COUNT(*)
            FROM batches
            WHERE expiry_date<CURDATE()
            AND quantity_remaining>0
        ");
    }

    public function monthlySales()
    {
        return $this->getValue("
            SELECT COALESCE(SUM(total_amount),0)
            FROM sales
            WHERE MONTH(sale_date)=MONTH(CURDATE())
            AND YEAR(sale_date)=YEAR(CURDATE())
        ");
    }
    public function monthlySalesChart()
{
    $stmt = $this->conn->query("
        SELECT 
            MONTHNAME(sale_date) AS month,
            SUM(total_amount) AS total
        FROM sales
        WHERE YEAR(sale_date)=YEAR(CURDATE())
        GROUP BY MONTH(sale_date)
        ORDER BY MONTH(sale_date)
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function drugCategories()
{
    $stmt = $this->conn->query("
        SELECT 
            categories.category_name,
            COUNT(drugs.drug_id) AS total
        FROM drugs
        JOIN categories
        ON drugs.category_id = categories.category_id
        GROUP BY categories.category_name
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function expiringDrugs()
{
    $stmt = $this->conn->query("
        SELECT 
            drugs.drug_name,
            batches.batch_number,
            batches.expiry_date,
            batches.quantity_remaining
        FROM batches

        JOIN drugs
        ON batches.drug_id = drugs.drug_id

        WHERE batches.expiry_date 
        BETWEEN CURDATE()
        AND DATE_ADD(CURDATE(),INTERVAL 30 DAY)

        ORDER BY batches.expiry_date ASC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function notificationCount()
{
    return $this->getValue("
        SELECT COUNT(*)
        FROM batches
        WHERE 
        expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        AND quantity_remaining > 0
    ");
}
}