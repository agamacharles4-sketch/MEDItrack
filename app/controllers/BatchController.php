<?php

require_once __DIR__ . "/../Models/Batch.php";


class BatchController
{

    private $batch;


    public function __construct()
    {
        $this->batch = new Batch();
    }



  public function index($filters = [])
{
    return $this->batch->all($filters);
}



    public function find($id)
    {
        return $this->batch->find($id);
    }



    public function store($data)
    {
        return $this->batch->create($data);
    }
public function update($id,$data)
{
    return $this->batch->update($id,$data);
}
public function delete($id)
{

    if(!$this->batch->delete($id))
    {

        throw new Exception(
        "Cannot delete this batch because it has sales history."
        );

    }


    return true;

}
public function statistics()
{
    return [

        "total" => $this->batch->totalBatches(),

        "expired" => $this->batch->expiredBatches(),

        "expiring" => $this->batch->expiringSoonBatches(),

        "lowstock" => $this->batch->lowStockBatches()

    ];
}
public function availableBatch($drugId)
{
    return $this->batch->getAvailableBatch($drugId);
}
public function fefo($drug_id)
{
    return $this->batch->getFEFOBatches($drug_id);
}
}