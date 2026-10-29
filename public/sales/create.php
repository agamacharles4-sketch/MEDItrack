<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/DrugController.php";
require_once "../../app/Controllers/SaleController.php";


$drugController = new DrugController();
$saleController = new SaleController();


$drugs = $drugController->index();


$saleNumber = $saleController->generateSaleNumber();



if(isset($_POST['complete_sale']))
{


    $cart = json_decode($_POST['cart'], true);



    $saleData = [

        "sale_number" => $_POST['sale_number'],

        "user_id" => $_SESSION['user_id'],

        "customer_name" => 
!empty($_POST['customer_name']) 
? $_POST['customer_name'] 
: "Walk-in Customer",

        "customer_phone" => $_POST['customer_phone'],

        "total_amount" => $_POST['total_amount'],

        "payment_method" => $_POST['payment_method'],

        "remarks" => $_POST['remarks']

    ];



    try{


        $saleController->save(
            $saleData,
            $cart
        );


        $_SESSION['success'] =
        "Sale completed successfully.";


        header("Location:index.php");

        exit;


    }
    catch(Exception $e)
    {

        $error = $e->getMessage();

    }


}



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>


<div class="main-content">

<div class="container-fluid">


<h3 class="fw-bold mb-4">

<i class="fa fa-shopping-cart text-success"></i>

New Sale

<span class="badge bg-primary float-end">

<?= $saleNumber ?>

</span>

</h3>



<?php if(isset($error)): ?>

<div class="alert alert-danger">

<?= $error ?>

</div>

<?php endif; ?>



<form method="POST" id="saleForm">


<input type="hidden"
name="sale_number"
value="<?= $saleNumber ?>">



<input type="hidden"
name="cart"
id="cartInput">



<input type="hidden"
name="total_amount"
id="totalAmount">





<div class="card shadow-sm mb-4">


<div class="card-header">

<h5>
Customer Information
</h5>

</div>



<div class="card-body">


<div class="row">


<div class="col-md-6">

<label>
Customer Name
</label>


<input
type="text"
name="customer_name"
class="form-control"
placeholder="Walk-in Customer">


</div>



<div class="col-md-6">


<label>
Phone
</label>


<input
type="text"
name="customer_phone"
class="form-control">


</div>


<div class="col-md-6 mt-3">


<label>
Payment Method
</label>


<select
name="payment_method"
class="form-select">


<option>
Cash
</option>


<option>
Mobile Money
</option>


<option>
Bank
</option>


<option>
Credit
</option>


</select>


</div>



<div class="col-md-12 mt-3">


<label>
Remarks
</label>


<textarea
name="remarks"
class="form-control"></textarea>


</div>


</div>


</div>


</div>






<div class="card shadow-sm mb-4">


<div class="card-header">

<h5>
Add Medicine
</h5>

</div>


<div class="card-body">


<div class="row">


<div class="col-md-6">


<select
id="drug"
class="form-select">


<option value="">
Select Drug
</option>

<pre>
<?php print_r($drugs); ?>
</pre>
<?php foreach($drugs as $drug): ?>


<option

value="<?= $drug['drug_id']; ?>"

data-name="<?= htmlspecialchars($drug['drug_name']); ?>"

data-price="<?= $drug['selling_price']; ?>"

data-stock="<?= $drug['stock']; ?>">


<?= htmlspecialchars($drug['drug_name']); ?>

(Stock:
<?= $drug['stock']; ?>
)


</option>


<?php endforeach; ?>


</select>


</div>



<div class="col-md-3">


<input

type="number"

id="qty"

class="form-control"

placeholder="Quantity">


</div>



<div class="col-md-3">


<button

type="button"

id="addCart"

class="btn btn-success w-100">


<i class="fa fa-plus"></i>

Add


</button>


</div>



</div>


</div>


</div>






<div class="card shadow-sm">


<div class="card-header">

<h5>
Shopping Cart
</h5>

</div>



<div class="card-body">


<table class="table table-bordered"

id="cartTable">


<thead>

<tr>

<th>
Drug
</th>


<th>
Qty
</th>


<th>
Price
</th>


<th>
Subtotal
</th>


<th>
Action
</th>


</tr>


</thead>


<tbody>


</tbody>



<tfoot>


<tr>


<th colspan="3">

Grand Total

</th>


<th id="grandTotal">

UGX 0

</th>


<th></th>


</tr>


</tfoot>


</table>



<button

type="submit"

name="complete_sale"

class="btn btn-primary">


<i class="fa fa-check"></i>

Complete Sale


</button>


</div>


</div>



</form>


</div>


</div>




<script>


let cart=[];



document
.getElementById("addCart")
.addEventListener("click",function(){



let option =
document.getElementById("drug")
.selectedOptions[0];



let qty =
parseInt(
document.getElementById("qty").value
);



if(!option.value || qty<=0)
{

alert("Select drug and enter quantity");

return;

}



let stock =
parseInt(option.dataset.stock);



if(qty > stock)
{

alert("Not enough stock");

return;

}



let item =
{

id: option.value,

name: option.dataset.name,

price:
parseFloat(option.dataset.price),

qty:qty

};



cart.push(item);



renderCart();



});




function renderCart()
{


let tbody =
document.querySelector("#cartTable tbody");


tbody.innerHTML="";


let total=0;



cart.forEach((item,index)=>{


let subtotal =
item.qty * item.price;



total += subtotal;



tbody.innerHTML += `


<tr>


<td>${item.name}</td>


<td>${item.qty}</td>


<td>${item.price.toLocaleString()}</td>


<td>${subtotal.toLocaleString()}</td>


<td>


<button

type="button"

class="btn btn-danger btn-sm"

onclick="removeItem(${index})">


<i class="fa fa-trash"></i>


</button>


</td>


</tr>


`;


});



document
.getElementById("grandTotal")
.innerHTML =
"UGX "
+
total.toLocaleString();



document
.getElementById("totalAmount")
.value =
total;



document
.getElementById("cartInput")
.value =
JSON.stringify(cart);



}



function removeItem(index)
{

cart.splice(index,1);

renderCart();

}



</script>



<?php

include "../../resources/views/layouts/footer.php";

?>