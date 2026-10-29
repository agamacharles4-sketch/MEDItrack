<?php

require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Helpers/activity_helper.php';
class CategoryController
{
    private Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function index()
    {
        return $this->category->getAll();
    }

    public function store($data)
    {
       if ($this->category->codeExists($data['category_code'])) {
    return [
        'success' => false,
        'message' => 'Category code already exists.'
    ];
    
}

if ($this->category->nameExists($data['category_name'])) {
    return [
        'success' => false,
        'message' => 'Category name already exists.'
    ];
}

$this->category->create(
    $data['category_code'],
    $data['category_name'],
    $data['description']
);
logActivity(
    "Created category ".$data['category_name']
);
return [
    'success' => true,
    'message' => 'Category added successfully.'
];
    }
    public function find($id)
{
    return $this->category->getById($id);
}
public function update($id, $data)
{
    // Check duplicate category code
    $stmt = db()->prepare(
        "SELECT COUNT(*) 
         FROM categories 
         WHERE category_id = ?
         AND category_id != ?"
    );

    $stmt->execute([
        $data['category_code'],
        $id
    ]);

    if($stmt->fetchColumn() > 0)
    {
        return [
            "success" => false,
            "message" => "Category code already exists."
        ];
    }


    // Check duplicate category name
    $stmt = db()->prepare(
        "SELECT COUNT(*) 
         FROM categories 
         WHERE category_name = ?
         AND category_id != ?"
    );

    $stmt->execute([
        $data['category_name'],
        $id
    ]);


    if($stmt->fetchColumn() > 0)
    {
        return [
            "success" => false,
            "message" => "Category name already exists."
        ];
    }


    $this->category->update(
        $id,
        $data['category_code'],
        $data['category_name'],
        $data['description']
    );
logActivity(
    "Updated category ".$data['category_name']
);

    return [
        "success" => true,
        "message" => "Category updated successfully."
    ];
}
public function delete($id)
{
   
$result = $this->category->delete($id);


    if(!$result)
    {
        return [
            "success"=>false,
            "message"=>"Cannot delete category. Drugs are assigned to this category."
        ];
    }


    return [
        "success"=>true,
        "message"=>"Category deleted successfully."
    ];
    $category = $this->category->getById($id);
}
}