<?php

require_once __DIR__ . "/../Services/SupplierService.php";

class SupplierController
{
    private $service;

    public function __construct()
    {
        $this->service = new SupplierService();
    }

    public function index()
    {
        return $this->service->all();
    }

    public function find($id)
    {
        return $this->service->find($id);
    }
}