<?php

require_once __DIR__ . "/../Models/Dashboard.php";

class DashboardController
{
    private $dashboard;

    public function __construct()
    {
        $this->dashboard = new Dashboard();
    }

    public function statistics()
{
    return [

        "categories"=>$this->dashboard->totalCategories(),

        "drugs"=>$this->dashboard->totalDrugs(),

        "suppliers"=>$this->dashboard->totalSuppliers(),

        "batches"=>$this->dashboard->totalBatches(),

        "stock"=>$this->dashboard->totalStock(),

        "expiring"=>$this->dashboard->expiringSoon(),

        "expired"=>$this->dashboard->expired(),

        "sales"=>$this->dashboard->monthlySales(),

        "salesChart"=>$this->dashboard->monthlySalesChart(),

        "categoryChart"=>$this->dashboard->drugCategories(),

        "expiryList"=>$this->dashboard->expiringDrugs(),
        "notifications"=>$this->dashboard->notificationCount(),
    ];
}
}