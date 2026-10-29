<?php

require_once __DIR__ . "/../Helpers/database_helper.php";


class Drug
{

    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }

public function all($search="", $category="", $status="")
{

    $query = "
SELECT

drugs.*,

categories.category_name,

units.unit_name,

COALESCE(SUM(batches.quantity_remaining),0) AS stock,

(
SELECT b.selling_price
FROM batches b
WHERE b.drug_id = drugs.drug_id
AND b.quantity_remaining > 0
AND b.expiry_status <> 'Expired'
ORDER BY b.expiry_date ASC
LIMIT 1
) AS selling_price

    FROM drugs

    INNER JOIN categories
    ON drugs.category_id = categories.category_id

    INNER JOIN units
    ON drugs.unit_id = units.unit_id
LEFT JOIN batches

ON drugs.drug_id = batches.drug_id
    WHERE 1=1

    ";


    $params = [];


    if($search != "")
    {

        $query .= "

        AND (
        drugs.drug_name LIKE ?
        OR drugs.generic_name LIKE ?
        OR drugs.manufacturer LIKE ?
        )

        ";

        $keyword="%".$search."%";

        $params[]=$keyword;
        $params[]=$keyword;
        $params[]=$keyword;

    }


    if($category != "")
    {

        $query .= " AND drugs.category_id=? ";

        $params[]=$category;

    }


    if($status != "")
    {

        $query .= " AND drugs.status=? ";

        $params[]=$status;

    }


    $query .= "
GROUP BY drugs.drug_id
    ORDER BY drugs.drug_name ASC

    ";


    $stmt=$this->conn->prepare($query);


    $stmt->execute($params);


    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}



    public function find($id)
    {

        $stmt = $this->conn->prepare("

        SELECT *

        FROM drugs

        WHERE drug_id=?

        ");


        $stmt->execute([$id]);


        return $stmt->fetch(PDO::FETCH_ASSOC);

    }





    public function create($data)
    {

        $stmt = $this->conn->prepare("

        INSERT INTO drugs

        (
        category_id,
        unit_id,
        drug_name,
        generic_name,
        dosage_form,
        strength,
        manufacturer,
        minimum_stock,
        description,
        status
        )

        VALUES
        (?,?,?,?,?,?,?,?,?,?)

        ");



        return $stmt->execute([

            $data['category_id'],

            $data['unit_id'],

            $data['drug_name'],

            $data['generic_name'],

            $data['dosage_form'],

            $data['strength'],

            $data['manufacturer'],

            $data['minimum_stock'],

            $data['description'],

            $data['status']

        ]);

    }





    public function update($id,$data)
    {

        $stmt=$this->conn->prepare("

        UPDATE drugs SET

        category_id=?,

        unit_id=?,

        drug_name=?,

        generic_name=?,

        dosage_form=?,

        strength=?,

        manufacturer=?,

        minimum_stock=?,

        description=?,

        status=?

        WHERE drug_id=?

        ");



        return $stmt->execute([

            $data['category_id'],

            $data['unit_id'],

            $data['drug_name'],

            $data['generic_name'],

            $data['dosage_form'],

            $data['strength'],

            $data['manufacturer'],

            $data['minimum_stock'],

            $data['description'],

            $data['status'],

            $id

        ]);

    }





    public function delete($id)
    {

        $stmt=$this->conn->prepare("

        DELETE FROM drugs

        WHERE drug_id=?

        ");


        return $stmt->execute([$id]);

    }

public function exists($drugName, $excludeId = null)
{
    if ($excludeId !== null) {

        $stmt = $this->conn->prepare("
            SELECT COUNT(*)
            FROM drugs
            WHERE drug_name = ?
            AND drug_id != ?
        ");

        $stmt->execute([$drugName, $excludeId]);

    } else {

        $stmt = $this->conn->prepare("
            SELECT COUNT(*)
            FROM drugs
            WHERE drug_name = ?
        ");

        $stmt->execute([$drugName]);
    }

    return $stmt->fetchColumn() > 0;
}
public function canDelete($id)
{
    $stmt = $this->conn->prepare("
        SELECT COUNT(*)
        FROM batches
        WHERE drug_id=?
    ");

    $stmt->execute([$id]);

    return $stmt->fetchColumn() == 0;
}
public function details($id)
{

    $stmt = $this->conn->prepare("

    SELECT

    drugs.*,

    categories.category_name,

    units.unit_name,


    COALESCE(
        SUM(batches.quantity_remaining),
        0
    ) AS total_stock,


    COUNT(batches.batch_id)
    AS total_batches


    FROM drugs


    INNER JOIN categories

    ON drugs.category_id =
    categories.category_id


    INNER JOIN units

    ON drugs.unit_id =
    units.unit_id


    LEFT JOIN batches

    ON drugs.drug_id =
    batches.drug_id


    WHERE drugs.drug_id=?


    GROUP BY drugs.drug_id

    ");


    $stmt->execute([$id]);


    return $stmt->fetch(PDO::FETCH_ASSOC);

}
}