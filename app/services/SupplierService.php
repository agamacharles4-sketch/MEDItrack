<?php

require_once __DIR__ . "/../Models/Supplier.php";

class SupplierService
{
    private $supplier;

    public function __construct()
    {
        $this->supplier = new Supplier();
    }

    public function all()
    {
        return $this->supplier->all();
    }

    public function find($id)
    {
        return $this->supplier->find($id);
    }
}