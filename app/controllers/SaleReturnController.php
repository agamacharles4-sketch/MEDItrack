<?php


require_once __DIR__ . "/../Services/SaleReturnService.php";


class SaleReturnController
{

    private $service;


    public function __construct()
    {

        $this->service = new SaleReturnService();

    }



    public function sale($id)
    {

        return $this->service->getSale($id);

    }




    public function items($id)
    {

        return $this->service->getItems($id);

    }




    public function process($sale_id,$user_id,$items,$reason)
    {

        return $this->service->processReturn(
            $sale_id,
            $user_id,
            $items,
            $reason
        );

    }
public function all()
{

    return $this->service->allReturns();

}

}