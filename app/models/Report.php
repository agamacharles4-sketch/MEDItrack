<?php

require_once __DIR__ . "/../Helpers/database_helper.php";


class Report
{

    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }



    // SALES SUMMARY

    public function salesSummary()
    {

        $stmt = $this->conn->query("

        SELECT

        COALESCE(SUM(total_amount),0) totalSales,

        COUNT(*) transactions


        FROM sales

        ");

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }





    // PURCHASE SUMMARY

    public function purchaseSummary()
    {

        $stmt = $this->conn->query("

        SELECT

        COALESCE(SUM(total_amount),0) totalPurchases,

        COUNT(*) transactions


        FROM purchases

        ");


        return $stmt->fetch(PDO::FETCH_ASSOC);

    }





    // INVENTORY VALUE

    public function inventoryValue()
    {

        $stmt = $this->conn->query("

        SELECT

        COALESCE(
        SUM(quantity_remaining * buying_price),
        0
        ) value


        FROM batches


        ");


        return $stmt->fetchColumn();

    }





    // EXPIRING DRUGS

    public function expiryReport()
    {

        $stmt = $this->conn->query("

        SELECT

        drugs.drug_name,

        batches.batch_number,

        batches.expiry_date,

        batches.quantity_remaining


        FROM batches


        INNER JOIN drugs

        ON batches.drug_id = drugs.drug_id


        WHERE expiry_date <= DATE_ADD(CURDATE(),INTERVAL 90 DAY)


        ORDER BY expiry_date ASC


        ");


        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
public function profitReport()
{

    $stmt = $this->conn->query("

    SELECT

        drugs.drug_name,

        SUM(sale_items.quantity) AS quantity_sold,

        SUM(
            sale_items.quantity * batches.buying_price
        ) AS total_cost,


        SUM(sale_items.subtotal) AS total_sales,


        SUM(sale_items.subtotal -
            (sale_items.quantity * batches.buying_price)
        ) AS profit



    FROM sale_items


    INNER JOIN batches

    ON sale_items.batch_id = batches.batch_id


    INNER JOIN drugs

    ON batches.drug_id = drugs.drug_id


    GROUP BY drugs.drug_id


    ORDER BY profit DESC


    ");


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}


}