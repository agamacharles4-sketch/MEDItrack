<?php

require_once __DIR__ . "/../Helpers/database_helper.php";


class User
{


    private $conn;


    public function __construct()
    {
        $this->conn = db();
    }



    public function login($username)
    {

        $query="
        SELECT *
        FROM users
        WHERE username=?
        AND status='Active'
        ";


        $stmt=$this->conn->prepare($query);


        $stmt->execute([
            $username
        ]);


        return $stmt->fetch(PDO::FETCH_ASSOC);

    }




    public function find($id)
    {

        $stmt=$this->conn->prepare("

        SELECT *

        FROM users

        WHERE user_id=?

        ");


        $stmt->execute([
            $id
        ]);


        return $stmt->fetch(PDO::FETCH_ASSOC);

    }




    public function updateProfile(
        $id,
        $first_name,
        $last_name,
        $username
    )
    {


        $stmt=$this->conn->prepare("

        UPDATE users

        SET

        first_name=?,
        last_name=?,
        username=?

        WHERE user_id=?

        ");


        return $stmt->execute([

            $first_name,
            $last_name,
            $username,
            $id

        ]);


    }





    public function changePassword(
        $id,
        $password
    )
    {


        $hashed=password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $stmt=$this->conn->prepare("

        UPDATE users

        SET password=?

        WHERE user_id=?

        ");


        return $stmt->execute([

            $hashed,
            $id

        ]);


    }


public function getPassword($id)
{

    $stmt = $this->conn->prepare("

    SELECT password

    FROM users

    WHERE user_id=?

    ");

    $stmt->execute([$id]);

    return $stmt->fetchColumn();

}



public function updatePassword($id,$password)
{

    $hashed = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    $stmt = $this->conn->prepare("

    UPDATE users

    SET password=?

    WHERE user_id=?

    ");


    return $stmt->execute([

        $hashed,
        $id

    ]);

}
}

?>