<?php

require_once __DIR__ . "/../Helpers/database_helper.php";


class SaleReturnService
{

    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }



    // Get sale information

    public function getSale($sale_id)
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


        $stmt->execute([$sale_id]);


        return $stmt->fetch(PDO::FETCH_ASSOC);

    }





    // Get medicines in a sale

    public function getItems($sale_id)
    {

        $stmt = $this->conn->prepare("

        SELECT


        sale_items.*,


        drugs.drug_name,


        batches.batch_number,


        batches.batch_id



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






    // Process return

    public function processReturn($sale_id,$user_id,$items,$reason)
    {

        $this->conn->beginTransaction();


        try{


            $total = 0;



            foreach($items as $item)
            {

                $total += $item['amount'];

            }



            // Save return header

            $stmt=$this->conn->prepare("

            INSERT INTO sale_returns
(
return_number,
sale_id,
user_id,
reason,
total_amount
)
VALUES(?,?,?,?,?)

            ");

$stmt->execute([

"RET-".date("YmdHis"),

$sale_id,

$user_id,

$reason,

$total

]);



            $return_id=$this->conn->lastInsertId();





            foreach($items as $item)
            {


                if($item['quantity']<=0)
                {
                    continue;
                }




                // Save return items

                $stmt=$this->conn->prepare("

                INSERT INTO sale_return_items

                (

                return_id,

                sale_item_id,

                quantity,

                amount

                )

                VALUES(?,?,?,?)

                ");



                $stmt->execute([

                    $return_id,

                    $item['sale_item_id'],

                    $item['quantity'],

                    $item['amount']

                ]);






                // Restore stock

                $stmt=$this->conn->prepare("

                UPDATE batches

                SET quantity_remaining =
                quantity_remaining + ?

                WHERE batch_id=?

                ");


                $stmt->execute([

                    $item['quantity'],

                    $item['batch_id']

                ]);



            }



            $this->conn->commit();


            return true;



        }
        catch(Exception $e)
        {

            $this->conn->rollBack();


            throw $e;

        }


    }
public function allReturns()
{

    $stmt = $this->conn->prepare("

    SELECT

    sale_returns.*,

    sales.sale_number,

    sales.customer_name,

    users.first_name,

    users.last_name


    FROM sale_returns


    INNER JOIN sales

    ON sale_returns.sale_id = sales.sale_id


    INNER JOIN users

    ON sale_returns.user_id = users.user_id


    ORDER BY sale_returns.return_date DESC


    ");


    $stmt->execute();


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

}