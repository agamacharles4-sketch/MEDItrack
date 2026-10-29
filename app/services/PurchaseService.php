<?php

require_once __DIR__ . "/../Helpers/database_helper.php";

require_once __DIR__ . "/../Models/ActivityLog.php";
class PurchaseService
{

    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }



    public function processPurchase($purchaseData,$items)
    {

        $this->conn->beginTransaction();


        try{


            // 1. Save purchase

            $stmt = $this->conn->prepare("

            INSERT INTO purchases
            (
                purchase_number,
                supplier_id,
                user_id,
                invoice_number,
                total_amount,
                payment_status,
                remarks
            )

            VALUES(?,?,?,?,?,?,?)

            ");


            $stmt->execute([

                $purchaseData['purchase_number'],

                $purchaseData['supplier_id'],

                $purchaseData['user_id'],

                $purchaseData['invoice_number'],

                $purchaseData['total_amount'],

                $purchaseData['payment_status'],

                $purchaseData['remarks']

            ]);



            $purchaseId = $this->conn->lastInsertId();



            // 2. Save items and create batches

            foreach($items as $item)
            {


                $batchNumber = "BATCH-".date("YmdHis").rand(100,999);



                // save purchase item

                $stmt = $this->conn->prepare("

                INSERT INTO purchase_items
                (
                    purchase_id,
                    drug_id,
                    batch_number,
                    expiry_date,
                    quantity,
                    buying_price,
                    selling_price,
                    subtotal
                )

                VALUES(?,?,?,?,?,?,?,?)

                ");



                $stmt->execute([

                    $purchaseId,

                    $item['drug_id'],

                    $batchNumber,

                    $item['expiry_date'],

                    $item['quantity'],

                    $item['buying_price'],

                    $item['selling_price'],

                    $item['subtotal']

                ]);



// 3. Create stock batch

$stmt = $this->conn->prepare("

INSERT INTO batches
(
    drug_id,
    supplier_id,
    batch_number,
    manufacture_date,
    expiry_date,
    quantity_received,
    quantity_remaining,
    buying_price,
    selling_price,
    invoice_number,
    stock_status,
    expiry_status
)

VALUES(?,?,?,?,?,?,?,?,?,?,?,?)

");


$stmt->execute([

    $item['drug_id'],

    $purchaseData['supplier_id'],

    $batchNumber,

    $item['manufacture_date'] ?? date("Y-m-d"),

    $item['expiry_date'],

    $item['quantity'],

    $item['quantity'],

    $item['buying_price'],

    $item['selling_price'],

    $purchaseData['invoice_number'],

    "In Stock",

    "Valid"

]);



            }



           $this->conn->commit();



$log = new ActivityLog();


$log->create(

    $purchaseData['user_id'],

    "Created purchase ".$purchaseData['purchase_number']

);



return true;


            return true;



        }
        catch(Exception $e)
        {

            $this->conn->rollBack();

            throw $e;

        }


    }

public function generatePurchaseNumber()
{
    return "PUR-" . date("YmdHis");
}
public function all()
{
    $stmt = $this->conn->query("

    SELECT
        purchases.*,
        suppliers.supplier_name,
        users.first_name,
        users.last_name

    FROM purchases

    INNER JOIN suppliers
        ON purchases.supplier_id = suppliers.supplier_id

    INNER JOIN users
        ON purchases.user_id = users.user_id

    ORDER BY purchase_date DESC

    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function find($id)
{
    $stmt = $this->conn->prepare("

    SELECT
        purchases.*,
        suppliers.supplier_name,
        users.first_name,
        users.last_name

    FROM purchases

    INNER JOIN suppliers
        ON purchases.supplier_id = suppliers.supplier_id

    INNER JOIN users
        ON purchases.user_id = users.user_id

    WHERE purchase_id = ?

    ");

    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function items($purchaseId)
{
    $stmt = $this->conn->prepare("

    SELECT
        purchase_items.*,
        drugs.drug_name,
        drugs.generic_name

    FROM purchase_items

    INNER JOIN drugs
        ON purchase_items.drug_id = drugs.drug_id

    WHERE purchase_items.purchase_id = ?

    ORDER BY purchase_item_id ASC

    ");

    $stmt->execute([$purchaseId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function statistics()
{
    $stats = [];

    // Today's purchases
    $stmt = $this->conn->query("
        SELECT IFNULL(SUM(total_amount),0)
        FROM purchases
        WHERE DATE(purchase_date)=CURDATE()
    ");
    $stats['todayPurchases'] = $stmt->fetchColumn();

    // Today's transactions
    $stmt = $this->conn->query("
        SELECT COUNT(*)
        FROM purchases
        WHERE DATE(purchase_date)=CURDATE()
    ");
    $stats['todayTransactions'] = $stmt->fetchColumn();

    // Monthly purchases
    $stmt = $this->conn->query("
        SELECT IFNULL(SUM(total_amount),0)
        FROM purchases
        WHERE MONTH(purchase_date)=MONTH(CURDATE())
        AND YEAR(purchase_date)=YEAR(CURDATE())
    ");
    $stats['monthlyPurchases'] = $stmt->fetchColumn();

    return $stats;
}
public function purchaseReport($from=null,$to=null,$status=null)
{

    $sql = "

    SELECT

        purchases.purchase_number,

        purchases.purchase_date,

        purchases.total_amount,

        purchases.payment_status,

        suppliers.supplier_name,

        users.first_name,

        users.last_name


    FROM purchases


    INNER JOIN suppliers

    ON purchases.supplier_id = suppliers.supplier_id


    INNER JOIN users

    ON purchases.user_id = users.user_id


    WHERE 1=1

    ";


    $params=[];


    if($from)
    {
        $sql .= " AND DATE(purchase_date)>=? ";
        $params[]=$from;
    }


    if($to)
    {
        $sql .= " AND DATE(purchase_date)<=? ";
        $params[]=$to;
    }


    if($status)
    {
        $sql .= " AND payment_status=? ";
        $params[]=$status;
    }


    $sql .= "

    ORDER BY purchase_date DESC

    ";


    $stmt=$this->conn->prepare($sql);

    $stmt->execute($params);


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}





public function purchaseSummary()
{

    $summary=[];


    $summary['totalPurchases'] =
    $this->conn->query("

        SELECT COALESCE(SUM(total_amount),0)

        FROM purchases

    ")->fetchColumn();



    $summary['transactions'] =
    $this->conn->query("

        SELECT COUNT(*)

        FROM purchases

    ")->fetchColumn();



    $summary['pending'] =
    $this->conn->query("

        SELECT COALESCE(SUM(total_amount),0)

        FROM purchases

        WHERE payment_status='Pending'

    ")->fetchColumn();



    return $summary;

}
}