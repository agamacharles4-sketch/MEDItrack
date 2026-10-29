<?php


require_once __DIR__ . "/../Models/Drug.php";


class DrugController
{

    private $drug;


    public function __construct()
    {

        $this->drug = new Drug();

    }



 public function index()
{

    $search = $_GET['search'] ?? "";

    $category = $_GET['category'] ?? "";

    $status = $_GET['status'] ?? "";


    return $this->drug->all(
        $search,
        $category,
        $status
    );

}




    public function store($data)
{
    if ($this->drug->exists($data['drug_name'])) {
        return "exists";
    }

    return $this->drug->create($data);
}




    public function find($id)
    {

        return $this->drug->find($id);

    }





    public function update($id, $data)
{
    if ($this->drug->exists($data['drug_name'], $id)) {
        return "exists";
    }

    return $this->drug->update($id, $data);
}





    public function delete($id)
    {

        return $this->drug->delete($id);

    }

public function canDelete($id)
{
    return $this->drug->canDelete($id);
}
public function details($id)
{
    return $this->drug->details($id);
}
}