<?php


require_once __DIR__."/../Models/User.php";
require_once __DIR__ . "/../Models/ActivityLog.php";

class UserController
{

    private $user;


    public function __construct()
    {
        $this->user=new User();
    }



    public function profile($id)
    {
        return $this->user->find($id);
    }




    public function update(
        $id,
        $first,
        $last,
        $username
    )
    {
        $result = $this->user->updateProfile(
    $id,
    $first,
    $last,
    $username
);


if($result)
{

    $log = new ActivityLog();


    $log->create(

        $id,

        "Updated profile information"

    );

}


return $result;
    }




    public function password(
        $id,
        $password
    )
    {
        return $this->user->changePassword(
            $id,
            $password
        );
    }

public function getPassword($id)
{
    return $this->user->getPassword($id);
}



public function updatePassword($id,$password)
{

    $result = $this->user->updatePassword(
        $id,
        $password
    );


    if($result)
    {

        $log = new ActivityLog();


        $log->create(

            $id,

            "Changed account password"

        );

    }


    return $result;

}
}