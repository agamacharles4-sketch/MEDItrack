<?php

require_once __DIR__ . '/../Helpers/database_helper.php';

class Category
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = db();
    }

    public function getAll()
    {
        $sql = "SELECT c.*,
                       COUNT(d.drug_id) AS total_drugs
                FROM categories c
                LEFT JOIN drugs d
                    ON c.category_id = d.category_id
                GROUP BY c.category_id
                ORDER BY c.category_name ASC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function codeExists($code)
{
    $stmt = $this->conn->prepare(
        "SELECT COUNT(*) FROM categories WHERE category_id = ?"
    );
    $stmt->execute([$code]);

    return $stmt->fetchColumn() > 0;
}

public function nameExists($name)
{
    $stmt = $this->conn->prepare(
        "SELECT COUNT(*) FROM categories WHERE category_name = ?"
    );
    $stmt->execute([$name]);

    return $stmt->fetchColumn() > 0;
}

    public function create($name,$description)
{

    $stmt = $this->conn->prepare("

    INSERT INTO categories
    (
        category_name,
        description
    )

    VALUES(?,?)

    ");


    return $stmt->execute([

        $name,
        $description

    ]);

}

    public function getById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT *
             FROM categories
             WHERE category_id=?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $description)
    {
        $stmt = $this->conn->prepare(
            "UPDATE categories
             SET category_id=?,
                 category_name=?,
                 description=?
             WHERE category_id=?"
        );

        return $stmt->execute([
            $code,
            $name,
            $description,
            $id
        ]);
    }

    public function delete($id)
    {
        // Don't allow deletion if drugs exist
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*)
             FROM drugs
             WHERE category_id=?"
        );

        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "DELETE FROM categories
             WHERE category_id=?"
        );

        return $stmt->execute([$id]);
    }
public function all()
{
    $stmt = $this->conn->query("
        SELECT *
        FROM categories
        ORDER BY category_name ASC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function find($id)
{

    $stmt = $this->conn->prepare("

    SELECT *

    FROM categories

    WHERE category_id=?

    ");


    $stmt->execute([$id]);


    return $stmt->fetch(PDO::FETCH_ASSOC);

}
}