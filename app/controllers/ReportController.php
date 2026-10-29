<?php

require_once __DIR__ . "/../Models/Report.php";


class ReportController
{

    private $report;


    public function __construct()
    {
        $this->report = new Report();
    }



    public function sales()
    {
        return $this->report->salesSummary();
    }



    public function purchases()
    {
        return $this->report->purchaseSummary();
    }



    public function inventory()
    {
        return $this->report->inventoryValue();
    }



    public function expiry()
    {
        return $this->report->expiryReport();
    }
public function profit()
{
    return $this->report->profitReport();
}

}