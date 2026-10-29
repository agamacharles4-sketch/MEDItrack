<?php

require_once __DIR__."/../Models/Batch.php";


class SaleProcessor
{

private $batch;


public function __construct()
{

$this->batch=new Batch();

}



public function allocateStock($drug_id,$quantity)
{


$batches=$this->batch
->getFEFOBatches($drug_id);



$remaining=$quantity;


$allocation=[];



foreach($batches as $batch)
{


if($remaining<=0)
{
break;
}



$available=$batch['quantity_remaining'];



if($available >= $remaining)
{

$used=$remaining;

}
else
{

$used=$available;

}



$allocation[]=[

"batch_id"=>$batch['batch_id'],

"quantity"=>$used,

"price"=>$batch['selling_price']

];



$this->batch->reduceStock(

$batch['batch_id'],

$used

);



$remaining -= $used;



}



if($remaining>0)
{

throw new Exception(
"Insufficient stock available"
);

}


return $allocation;


}


}