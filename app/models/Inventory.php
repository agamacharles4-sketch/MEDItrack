<?php

require_once __DIR__ . "/../Helpers/database_helper.php";

class Inventory
{
    private $conn;

    public function __construct()
    {
        $this->conn = db();
    }

    public function all()
    {
        $stmt = $this->conn->query("

        SELECT

            batches.batch_id,

            drugs.drug_name,

            categories.category_name,

            suppliers.supplier_name,

            batches.batch_number,

            batches.quantity_remaining,

            batches.buying_price,

            batches.selling_price,

            batches.expiry_date,

            batches.stock_status,

            batches.expiry_status

        FROM batches

        INNER JOIN drugs

            ON drugs.drug_id=batches.drug_id

        INNER JOIN categories

            ON categories.category_id=drugs.category_id

        INNER JOIN suppliers

            ON suppliers.supplier_id=batches.supplier_id

        ORDER BY drugs.drug_name

        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function report()
{
    $stmt = $this->conn->query("

    SELECT

        drugs.drug_id,

        drugs.drug_name,

        drugs.generic_name,

        categories.category_name,

        drugs.minimum_stock,

        COALESCE(SUM(batches.quantity_remaining),0) AS current_stock,

        MIN(batches.buying_price) AS buying_price,

        MIN(batches.selling_price) AS selling_price,

        COALESCE(
            SUM(
                batches.quantity_remaining * batches.buying_price
            ),0
        ) AS stock_value

    FROM drugs

    INNER JOIN categories
        ON drugs.category_id = categories.category_id

    LEFT JOIN batches
        ON drugs.drug_id = batches.drug_id
        AND batches.expiry_status <> 'Expired'

    GROUP BY drugs.drug_id

    ORDER BY drugs.drug_name

    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function summary()
{

    $summary = [];


    $summary['totalMedicines'] =
    $this->conn->query("
        SELECT COUNT(*)
        FROM drugs
    ")->fetchColumn();


    $summary['totalStock'] =
    $this->conn->query("
        SELECT IFNULL(SUM(quantity_remaining),0)
        FROM batches
        WHERE expiry_status<>'Expired'
    ")->fetchColumn();


    $summary['inventoryValue'] =
    $this->conn->query("
        SELECT IFNULL(
            SUM(quantity_remaining*buying_price),
            0
        )
        FROM batches
        WHERE expiry_status<>'Expired'
    ")->fetchColumn();


    $summary['lowStock'] =
    $this->conn->query("
        SELECT COUNT(*)
        FROM
        (
            SELECT
                drugs.drug_id,
                drugs.minimum_stock,
                IFNULL(
                    SUM(batches.quantity_remaining),
                    0
                ) AS stock

            FROM drugs

            LEFT JOIN batches
                ON drugs.drug_id=batches.drug_id
                AND batches.expiry_status<>'Expired'

            GROUP BY drugs.drug_id

            HAVING stock<=minimum_stock

        ) x
    ")->fetchColumn();


    return $summary;

}
}