<?php

require_once __DIR__ . "/../Helpers/database_helper.php";
require_once __DIR__ . "/../Services/SaleProcessor.php";
class Sale
{
    private $conn;

    public function __construct()
    {
        $this->conn = db();
    }

    /**
     * Generate the next sale number
     * Example: SALE00001
     */
    public function generateSaleNumber()
    {
        $stmt = $this->conn->query("
            SELECT MAX(sale_id) AS last_id
            FROM sales
        ");

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $next = ($row['last_id'] ?? 0) + 1;

        return "SALE" . str_pad($next, 5, "0", STR_PAD_LEFT);
    }

    /**
     * Create sale header
     */
    public function createSale($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO sales
            (
                sale_number,
                user_id,
                customer_name,
                customer_phone,
                total_amount,
                payment_method,
                remarks
            )
            VALUES
            (?,?,?,?,?,?,?)
        ");

        $stmt->execute([

            $data['sale_number'],

            $data['user_id'],

            $data['customer_name'],

            $data['customer_phone'],

            $data['total_amount'],

            $data['payment_method'],

            $data['remarks']

        ]);

        return $this->conn->lastInsertId();
    }

    /**
     * Add item sold
     */
    public function addSaleItem($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO sale_items
            (
                sale_id,
                batch_id,
                quantity,
                selling_price,
                subtotal
            )
            VALUES
            (?,?,?,?,?)
        ");

        return $stmt->execute([

            $data['sale_id'],

            $data['batch_id'],

            $data['quantity'],

            $data['selling_price'],

            $data['subtotal']

        ]);
    }

    /**
     * Sales history
     */
    public function all()
    {
        $stmt = $this->conn->query("
            SELECT
                sales.*,
                users.first_name,
                users.last_name
            FROM sales
            INNER JOIN users
                ON users.user_id = sales.user_id
            ORDER BY sale_date DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find sale
     */
    public function find($id)
{

    $stmt = $this->conn->prepare("

        SELECT

            sales.*,

            users.first_name,

            users.last_name

        FROM sales

        INNER JOIN users

            ON sales.user_id = users.user_id

        WHERE sales.sale_id = ?

    ");

    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);

}

    /**
     * Today's sales total
     */
    public function todaySales()
    {
        $stmt = $this->conn->query("
            SELECT COALESCE(SUM(total_amount),0)
            FROM sales
            WHERE DATE(sale_date)=CURDATE()
        ");

        return $stmt->fetchColumn();
    }

    /**
     * Today's transactions
     */
    public function todayTransactions()
    {
        $stmt = $this->conn->query("
            SELECT COUNT(*)
            FROM sales
            WHERE DATE(sale_date)=CURDATE()
        ");

        return $stmt->fetchColumn();
    }

    /**
     * Monthly sales
     */
    public function monthlySales()
    {
        $stmt = $this->conn->query("
            SELECT COALESCE(SUM(total_amount),0)
            FROM sales
            WHERE MONTH(sale_date)=MONTH(CURDATE())
            AND YEAR(sale_date)=YEAR(CURDATE())
        ");

        return $stmt->fetchColumn();
    }
    public function saveSale($saleData, $items)
{

    try {

        $this->conn->beginTransaction();


        // Create sale header

        $sale_id = $this->createSale($saleData);



        foreach($items as $item)
        {


            $processor = new SaleProcessor();


            $allocation = $processor->allocateStock(

                $item['drug_id'],

                $item['quantity']

            );



            foreach($allocation as $batch)
            {


                $subtotal =
                $batch['quantity']
                *
                $batch['price'];



                $this->addSaleItem([


                    "sale_id"=>$sale_id,


                    "batch_id"=>$batch['batch_id'],


                    "quantity"=>$batch['quantity'],


                    "selling_price"=>$batch['price'],


                    "subtotal"=>$subtotal


                ]);


            }


        }



        $this->conn->commit();


        return $sale_id;


    }
    catch(Exception $e)
    {


        $this->conn->rollBack();


        throw $e;


    }


}
public function items($sale_id)
{

    $stmt = $this->conn->prepare("

        SELECT

            sale_items.*,

            batches.batch_number,

            drugs.drug_name,

            drugs.generic_name

        FROM sale_items

        INNER JOIN batches

            ON sale_items.batch_id = batches.batch_id

        INNER JOIN drugs

            ON batches.drug_id = drugs.drug_id

        WHERE sale_items.sale_id = ?

        ORDER BY drugs.drug_name ASC

    ");

    $stmt->execute([$sale_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
public function salesReport($from=null,$to=null,$payment=null)
{


$query = "

SELECT

sales.*,

users.first_name,

users.last_name

FROM sales

INNER JOIN users

ON sales.user_id = users.user_id

WHERE 1=1

";


$params=[];



if($from)
{

$query .= " AND DATE(sale_date) >= ? ";

$params[]=$from;

}



if($to)
{

$query .= " AND DATE(sale_date) <= ? ";

$params[]=$to;

}



if($payment)
{

$query .= " AND payment_method=? ";

$params[]=$payment;

}



$query .= "

ORDER BY sale_date DESC

";



$stmt=$this->conn->prepare($query);


$stmt->execute($params);



return $stmt->fetchAll(PDO::FETCH_ASSOC);


}
}