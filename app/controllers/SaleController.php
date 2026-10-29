<?php

require_once __DIR__ . "/../Models/Sale.php";

class SaleController
{
    private $sale;

    public function __construct()
    {
        $this->sale = new Sale();
    }

    public function generateSaleNumber()
    {
        return $this->sale->generateSaleNumber();
    }

    public function save($saleData, $cart)
    {
        return $this->sale->saveSale($saleData, $cart);
    }

    public function index()
    {
        return $this->sale->all();
    }

    public function statistics()
    {
        return [
            "todaySales" => $this->sale->todaySales(),
            "todayTransactions" => $this->sale->todayTransactions(),
            "monthlySales" => $this->sale->monthlySales()
        ];
    }

    public function find($id)
    {
        return $this->sale->find($id);
    }

    public function items($sale_id)
    {
        return $this->sale->items($sale_id);
    }
    public function salesReport($from=null,$to=null,$payment=null)
{

    require_once __DIR__ . "/../Models/Sale.php";


    $saleModel = new Sale();


    return $saleModel->salesReport(
        $from,
        $to,
        $payment
    );

}
}