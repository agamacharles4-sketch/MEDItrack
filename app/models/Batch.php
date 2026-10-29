<?php

require_once __DIR__ . "/../Helpers/database_helper.php";


class Batch
{

    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }



  public function all($filters = [])
{
    $sql = "

    SELECT

        batches.*,
        drugs.drug_name,
        suppliers.supplier_name

    FROM batches

    INNER JOIN drugs
        ON drugs.drug_id = batches.drug_id

    INNER JOIN suppliers
        ON suppliers.supplier_id = batches.supplier_id

    WHERE 1=1

    ";

    $params = [];

    if (!empty($filters['search'])) {
        $sql .= " AND (batch_number LIKE ? OR drugs.drug_name LIKE ?) ";
        $params[] = "%" . $filters['search'] . "%";
        $params[] = "%" . $filters['search'] . "%";
    }

    if (!empty($filters['drug_id'])) {
        $sql .= " AND batches.drug_id=? ";
        $params[] = $filters['drug_id'];
    }

    if (!empty($filters['supplier_id'])) {
        $sql .= " AND batches.supplier_id=? ";
        $params[] = $filters['supplier_id'];
    }

    if (!empty($filters['expiry_status'])) {
        $sql .= " AND batches.expiry_status=? ";
        $params[] = $filters['expiry_status'];
    }

    if (!empty($filters['stock_status'])) {
        $sql .= " AND batches.stock_status=? ";
        $params[] = $filters['stock_status'];
    }

    $sql .= " ORDER BY expiry_date ASC ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    public function find($id)
    {

        $stmt=$this->conn->prepare("

        SELECT *

        FROM batches

        WHERE batch_id=?

        ");


        $stmt->execute([$id]);


        return $stmt->fetch(PDO::FETCH_ASSOC);

    }



   public function create($data)
{

$stmt=$this->conn->prepare("

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

VALUES
(?,?,?,?,?,?,?,?,?,?,?,?)

");


return $stmt->execute([


$data['drug_id'],


$data['supplier_id'],


$data['batch_number'],


$data['manufacture_date'],


$data['expiry_date'],


$data['quantity_received'],


// initial stock equals received quantity

$data['quantity_received'],


$data['buying_price'],


$data['selling_price'],


$data['invoice_number'],


// automatically set stock status

"In Stock",


// automatically set expiry status

"Valid"


]);


}

private function calculateExpiryStatus($expiryDate)
{
    $today = date('Y-m-d');
    $soon = date('Y-m-d', strtotime('+30 days'));

    if ($expiryDate < $today) {
        return "Expired";
    }

    if ($expiryDate <= $soon) {
        return "Expiring Soon";
    }

    return "Valid";
}

private function calculateStockStatus($quantityRemaining)
{
    if ($quantityRemaining <= 0) {
        return "Out of Stock";
    }

    if ($quantityRemaining <= 10) {
        return "Low Stock";
    }

    return "In Stock";
}
public function update($id,$data)
{

    $quantityRemaining = $data['quantity_remaining'];

    $stockStatus = $this->calculateStockStatus($quantityRemaining);

    $expiryStatus = $this->calculateExpiryStatus($data['expiry_date']);


    $stmt = $this->conn->prepare("

    UPDATE batches SET

    drug_id=?,

    supplier_id=?,

    batch_number=?,

    manufacture_date=?,

    expiry_date=?,

    quantity_received=?,

    quantity_remaining=?,

    buying_price=?,

    selling_price=?,

    invoice_number=?,

    stock_status=?,

    expiry_status=?

    WHERE batch_id=?

    ");



    return $stmt->execute([

        $data['drug_id'],

        $data['supplier_id'],

        $data['batch_number'],

        $data['manufacture_date'],

        $data['expiry_date'],

        $data['quantity_received'],

        $quantityRemaining,

        $data['buying_price'],

        $data['selling_price'],

        $data['invoice_number'],

        $stockStatus,

        $expiryStatus,

        $id

    ]);

}
public function delete($id)
{

    // Check if batch was already used in sales

    $check = $this->conn->prepare("

    SELECT COUNT(*)

    FROM sale_items

    WHERE batch_id=?

    ");

    $check->execute([$id]);


    $used = $check->fetchColumn();



    if($used > 0)
    {

        return false;

    }



    $stmt=$this->conn->prepare("

    DELETE FROM batches

    WHERE batch_id=?

    ");


    return $stmt->execute([$id]);

}
public function totalBatches()
{
    return $this->conn->query("
        SELECT COUNT(*)
        FROM batches
    ")->fetchColumn();
}

public function expiredBatches()
{
    return $this->conn->query("
        SELECT COUNT(*)
        FROM batches
        WHERE expiry_status='Expired'
    ")->fetchColumn();
}

public function expiringSoonBatches()
{
    return $this->conn->query("
        SELECT COUNT(*)
        FROM batches
        WHERE expiry_status='Expiring Soon'
    ")->fetchColumn();
}

public function lowStockBatches()
{
    return $this->conn->query("
        SELECT COUNT(*)
        FROM batches
        WHERE stock_status='Low Stock'
    ")->fetchColumn();
}
public function getAvailableBatch($drugId)
{

    $stmt = $this->conn->prepare("

        SELECT *

        FROM batches

        WHERE drug_id=?

        AND quantity_remaining>0

        AND expiry_status<>'Expired'

        ORDER BY expiry_date ASC

        LIMIT 1

    ");

    $stmt->execute([$drugId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);

}
public function getFEFOBatches($drug_id)
{

    $stmt = $this->conn->prepare("

    SELECT *

    FROM batches

    WHERE drug_id=?

    AND quantity_remaining > 0

    AND expiry_status <> 'Expired'

    ORDER BY expiry_date ASC

    ");

    $stmt->execute([
        $drug_id
    ]);


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
public function reduceStock($batch_id,$quantity)
{

    $stmt=$this->conn->prepare("

    UPDATE batches

    SET quantity_remaining =
    quantity_remaining - ?

    WHERE batch_id=?

    ");

    return $stmt->execute([
        $quantity,
        $batch_id
    ]);

}
}