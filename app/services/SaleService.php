<?php

require_once __DIR__ . "/../Helpers/database_helper.php";
require_once __DIR__ . "/../Models/ActivityLog.php";

class SaleService
{

    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }



    public function processSale($saleData, $cart)
    {

        $this->conn->beginTransaction();


        try {


            // CREATE SALE
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

            VALUES(?,?,?,?,?,?,?)

            ");


            $stmt->execute([

                $saleData['sale_number'],
                $saleData['user_id'],
                $saleData['customer_name'],
                $saleData['customer_phone'],
                $saleData['total_amount'],
                $saleData['payment_method'],
                $saleData['remarks']

            ]);



            $saleId = $this->conn->lastInsertId();



            // PROCESS CART ITEMS

            foreach($cart as $item)
            {


                $requiredQty = $item['qty'];



                // GET FEFO BATCHES

                $stmt = $this->conn->prepare("

                SELECT *

                FROM batches

                WHERE drug_id=?

                AND quantity_remaining > 0

                AND expiry_status IN('Valid','Expiring Soon')

                ORDER BY expiry_date ASC

                ");


                $stmt->execute([
                    $item['id']
                ]);


                $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);



                foreach($batches as $batch)
                {


                    if($requiredQty <= 0)
                    {
                        break;
                    }



                    $available = $batch['quantity_remaining'];



                    $deduct = min(
                        $requiredQty,
                        $available
                    );



                    // UPDATE STOCK

                    $newQty = $available - $deduct;


                    $update = $this->conn->prepare("

                    UPDATE batches

                    SET quantity_remaining=?

                    WHERE batch_id=?

                    ");


                    $update->execute([

                        $newQty,
                        $batch['batch_id']

                    ]);




                    // INSERT SALE ITEM

                    $subtotal = 
                    $deduct * $batch['selling_price'];



                    $insert = $this->conn->prepare("

                    INSERT INTO sale_items

                    (
                        sale_id,
                        batch_id,
                        quantity,
                        selling_price,
                        subtotal
                    )

                    VALUES(?,?,?,?,?)

                    ");



                    $insert->execute([

                        $saleId,
                        $batch['batch_id'],
                        $deduct,
                        $batch['selling_price'],
                        $subtotal

                    ]);



                    $requiredQty -= $deduct;



                }




                if($requiredQty > 0)
                {

                    throw new Exception(

                    "Insufficient stock for "
                    .$item['name']

                    );

                }


            }



          $this->conn->commit();



$log = new ActivityLog();


$log->create(

    $saleData['user_id'],

    "Completed sale ".$saleData['sale_number']

);



return true;



        }

        catch(Exception $e)

        {

            $this->conn->rollBack();

            throw $e;

        }


    }

public function all()
{

    $stmt = $this->conn->prepare("

    SELECT

    sales.*,

    users.first_name,

    users.last_name


    FROM sales


    INNER JOIN users

    ON sales.user_id = users.user_id


    ORDER BY sales.sale_id DESC


    ");


    $stmt->execute();


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
public function statistics()
{

    // Today's sales amount

    $stmt = $this->conn->prepare("

    SELECT

    COALESCE(SUM(total_amount),0)

    FROM sales

    WHERE DATE(sale_date)=CURDATE()

    ");

    $stmt->execute();

    $todaySales = $stmt->fetchColumn();



    // Today's number of transactions

    $stmt = $this->conn->prepare("

    SELECT

    COUNT(*)

    FROM sales

    WHERE DATE(sale_date)=CURDATE()

    ");

    $stmt->execute();

    $todayTransactions = $stmt->fetchColumn();



    // Current month sales

    $stmt = $this->conn->prepare("

    SELECT

    COALESCE(SUM(total_amount),0)

    FROM sales

    WHERE MONTH(sale_date)=MONTH(CURDATE())

    AND YEAR(sale_date)=YEAR(CURDATE())

    ");

    $stmt->execute();

    $monthlySales = $stmt->fetchColumn();



    return [

        "todaySales" => $todaySales,

        "todayTransactions" => $todayTransactions,

        "monthlySales" => $monthlySales

    ];

}
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


    WHERE sales.sale_id=?

    ");


    $stmt->execute([$id]);


    return $stmt->fetch(PDO::FETCH_ASSOC);

}




public function items($sale_id)
{

    $stmt = $this->conn->prepare("

    SELECT

    sale_items.*,

    drugs.drug_name,

    drugs.generic_name,


    batches.batch_number


    FROM sale_items


    INNER JOIN batches

    ON sale_items.batch_id=batches.batch_id


    INNER JOIN drugs

    ON batches.drug_id=drugs.drug_id


    WHERE sale_items.sale_id=?


    ");


    $stmt->execute([$sale_id]);


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
public function salesReport($from=null,$to=null,$payment=null)
{

    $sql = "

    SELECT

        sales.sale_number,

        sales.sale_date,

        sales.customer_name,

        sales.total_amount,

        sales.payment_method,

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
        $sql .= " AND DATE(sale_date)>=? ";
        $params[]=$from;
    }


    if($to)
    {
        $sql .= " AND DATE(sale_date)<=? ";
        $params[]=$to;
    }


    if($payment)
    {
        $sql .= " AND payment_method=? ";
        $params[]=$payment;
    }


    $sql .= "

    ORDER BY sale_date DESC

    ";


    $stmt=$this->conn->prepare($sql);

    $stmt->execute($params);


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}



public function salesSummary()
{

    $summary=[];


    $summary['totalSales']=$this->conn
    ->query("

    SELECT COALESCE(SUM(total_amount),0)

    FROM sales

    ")
    ->fetchColumn();



    $summary['transactions']=$this->conn
    ->query("

    SELECT COUNT(*)

    FROM sales

    ")
    ->fetchColumn();



    $summary['today']=$this->conn
    ->query("

    SELECT COALESCE(SUM(total_amount),0)

    FROM sales

    WHERE DATE(sale_date)=CURDATE()

    ")
    ->fetchColumn();



    return $summary;

}
public function profitReport($from=null,$to=null)
{

    $sql = "

    SELECT

        drugs.drug_name,

        SUM(sale_items.quantity) AS quantity_sold,

        SUM(
            sale_items.quantity * sale_items.selling_price
        ) AS revenue,


        SUM(
            sale_items.quantity * batches.buying_price
        ) AS cost,


        SUM(
            sale_items.quantity * 
            (sale_items.selling_price - batches.buying_price)
        ) AS profit


    FROM sale_items


    INNER JOIN batches

    ON sale_items.batch_id = batches.batch_id


    INNER JOIN drugs

    ON batches.drug_id = drugs.drug_id


    INNER JOIN sales

    ON sale_items.sale_id = sales.sale_id


    WHERE 1=1

    ";


    $params=[];



    if($from)
    {
        $sql .= " AND DATE(sales.sale_date)>=? ";
        $params[]=$from;
    }


    if($to)
    {
        $sql .= " AND DATE(sales.sale_date)<=? ";
        $params[]=$to;
    }



    $sql .= "

    GROUP BY drugs.drug_id

    ORDER BY profit DESC

    ";



    $stmt=$this->conn->prepare($sql);

    $stmt->execute($params);


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}




public function profitSummary()
{

    $data=[];


    $data['revenue']=$this->conn->query("

        SELECT COALESCE(SUM(total_amount),0)

        FROM sales

    ")->fetchColumn();



    $data['cost']=$this->conn->query("

        SELECT COALESCE(
        SUM(
        sale_items.quantity * batches.buying_price
        ),0)

        FROM sale_items


        INNER JOIN batches

        ON sale_items.batch_id=batches.batch_id


    ")->fetchColumn();



    $data['profit'] =
    $data['revenue'] - $data['cost'];



    return $data;

}
}