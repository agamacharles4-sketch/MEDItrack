<?php

require_once __DIR__ . "/../Models/Inventory.php";

class InventoryController
{
    private $inventory;

    public function __construct()
    {
        $this->inventory = new Inventory();
    }

    public function index()
    {
        return $this->inventory->all();
    }

    public function report()
    {
        return $this->inventory->report();
    }

    public function summary()
    {
        return $this->inventory->summary();
    }
}