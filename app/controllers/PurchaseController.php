<?php

require_once __DIR__ . "/../Services/PurchaseService.php";


class PurchaseController
{

    private $service;


    public function __construct()
    {
        $this->service = new PurchaseService();
    }



    public function index()
    {
        return $this->service->all();
    }



    public function find($id)
    {
        return $this->service->find($id);
    }



    public function generatePurchaseNumber()
    {
        return $this->service->generatePurchaseNumber();
    }



    public function save($purchaseData,$items)
    {
        return $this->service->processPurchase(
            $purchaseData,
            $items
        );
    }
public function items($purchaseId)
{
    return $this->service->items($purchaseId);
}

public function statistics()
{
    return $this->service->statistics();
}
public function purchaseReport($from=null,$to=null,$status=null)
{
    return $this->service->purchaseReport(
        $from,
        $to,
        $status
    );
}


public function purchaseSummary()
{
    return $this->service->purchaseSummary();
}




}