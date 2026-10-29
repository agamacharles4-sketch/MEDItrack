<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/SaleReturnController.php";


$controller = new SaleReturnController();



if(!isset($_GET['id']))
{
    die("Invalid Sale");
}


$sale_id = intval($_GET['id']);



$sale = $controller->sale($sale_id);


if(!$sale)
{
    die("Sale not found");
}



$items = $controller->items($sale_id);




if(isset($_POST['return_sale']))
{


    $returnItems = [];


    foreach($_POST['quantity'] as $key=>$qty)
    {


        if($qty > 0)
        {

            $returnItems[] = [

                "sale_item_id"=>$key,

                "batch_id"=>$_POST['batch_id'][$key],

                "quantity"=>$qty,

                "amount"=>$_POST['amount'][$key] * $qty

            ];

        }


    }




    if(count($returnItems)>0)
    {


        $controller->process(

            $sale_id,

            $_SESSION['user_id'],

            $returnItems,

            $_POST['reason']

        );


        $_SESSION['success']="Return processed successfully";


        header("Location:returns.php");

        exit;

    }

}




include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-undo text-warning"></i>

Return Sale

</h3>



<div class="card shadow-sm">


<div class="card-body">


<h5>

Sale Number:

<?= $sale['sale_number']; ?>

</h5>



<form method="POST">


<table class="table table-bordered">


<thead>

<tr>

<th>Medicine</th>

<th>Sold Qty</th>

<th>Return Qty</th>

<th>Price</th>

</tr>

</thead>



<tbody>


<?php foreach($items as $item): ?>


<tr>


<td>

<?= htmlspecialchars($item['drug_name']); ?>

<br>

<small>

Batch:
<?= $item['batch_number']; ?>

</small>

</td>



<td>

<?= $item['quantity']; ?>

</td>



<td>


<input type="number"

class="form-control"

name="quantity[<?= $item['sale_item_id']; ?>]"

value="0"

min="0"

max="<?= $item['quantity']; ?>">



<input type="hidden"

name="batch_id[<?= $item['sale_item_id']; ?>]"

value="<?= $item['batch_id']; ?>">



<input type="hidden"

name="amount[<?= $item['sale_item_id']; ?>]"

value="<?= $item['selling_price']; ?>">



</td>



<td>

UGX <?= number_format($item['selling_price']); ?>

</td>



</tr>


<?php endforeach; ?>


</tbody>


</table>



<label>
Reason
</label>


<textarea

name="reason"

class="form-control mb-3"

required></textarea>



<button

type="submit"

name="return_sale"

class="btn btn-danger">

<i class="fa fa-undo"></i>

Process Return

</button>


</form>


</div>

</div>


</div>

</div>


<?php

include "../../resources/views/layouts/footer.php";

?>