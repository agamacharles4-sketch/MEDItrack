<?php

require_once "../../config/session.php";
checkLogin();

require_once "../../app/Controllers/PurchaseController.php";
require_once "../../app/Controllers/SupplierController.php";
require_once "../../app/Controllers/DrugController.php";

$purchaseController = new PurchaseController();
$supplierController = new SupplierController();
$drugController = new DrugController();

$suppliers = $supplierController->index();
$drugs = $drugController->index();

$purchaseNumber = $purchaseController->generatePurchaseNumber();
if(isset($_POST['save_purchase']))
{

    $items = json_decode($_POST['items'], true);


    $total = 0;


    foreach($items as $item)
    {
        $total += $item['subtotal'];
    }



    $purchaseData = [

        "purchase_number" => $purchaseNumber,

        "supplier_id" => $_POST['supplier_id'],

        "user_id" => $_SESSION['user_id'],

        "invoice_number" => $_POST['invoice_number'],

        "total_amount" => $total,

        "payment_status" => $_POST['payment_status'],

        "remarks" => ""

    ];



    $purchaseController->save(
        $purchaseData,
        $items
    );


    $_SESSION['success'] =
    "Purchase completed successfully.";


    header("Location:index.php");

    exit;

}
include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";

?>

<div class="main-content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3 class="fw-bold">

<i class="fa fa-truck text-primary"></i>

New Purchase

</h3>

<span class="badge bg-primary">

<?= $purchaseNumber ?>

</span>

</div>

<form method="POST">

<div class="card shadow-sm mb-4">

<div class="card-header">

Supplier Information

</div>

<div class="card-body">

<div class="row">

<div class="col-md-4">

<label>Supplier</label>

<select class="form-select" id="supplier" name="supplier_id" required>

<option value="">Select Supplier</option>

<?php foreach($suppliers as $supplier): ?>

<option value="<?= $supplier['supplier_id']; ?>">

<?= htmlspecialchars($supplier['supplier_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-4">

<label>Invoice Number</label>

<input
type="text"
class="form-control"
name="invoice_number">

</div>

<div class="col-md-4">

<label>Payment Status</label>

<select
class="form-select"
name="payment_status">

<option>Pending</option>

<option>Paid</option>

<option>Partial</option>

</select>

</div>

</div>

</div>

</div>
<div class="card shadow-sm mb-4">

<div class="card-header">

Add Medicine

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3">

<select
id="drug"
class="form-select">

<option value="">Select Medicine</option>

<?php foreach($drugs as $drug): ?>

<option
value="<?= $drug['drug_id']; ?>"
data-name="<?= htmlspecialchars($drug['drug_name']); ?>">

<?= htmlspecialchars($drug['drug_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-1">

<input
type="number"
id="qty"
class="form-control"
placeholder="Qty">

</div>

<div class="col-md-2">

<input
type="number"
id="buying"
class="form-control"
placeholder="Buying">

</div>

<div class="col-md-2">

<input
type="number"
id="selling"
class="form-control"
placeholder="Selling">

</div>

<div class="col-md-2">

<input
type="date"
id="expiry"
class="form-control">
<input
type="date"
id="manufacture"
class="form-control"
placeholder="Manufacture Date">
</div>

<div class="col-md-2">

<button
type="button"
class="btn btn-success w-100"
id="addItem">

<i class="fa fa-plus"></i>

Add

</button>

</div>

</div>

</div>

</div>
<div class="card shadow-sm">

<div class="card-header">

Purchase Items

</div>

<div class="card-body">

<table class="table table-bordered" id="purchaseTable">

<thead>

<tr>

<th>Medicine</th>

<th>Qty</th>

<th>Buying</th>

<th>Selling</th>

<th>Expiry</th>

<th>Subtotal</th>

<th></th>

</tr>

</thead>

<tbody>

</tbody>

<tfoot>

<tr>

<th colspan="5">

Grand Total

</th>

<th id="grandTotal">

UGX 0

</th>

<th></th>

</tr>

</tfoot>

</table>

<input
type="hidden"
name="items"
id="itemsInput">

<button
class="btn btn-primary"
type="submit"
name="save_purchase">

<i class="fa fa-save"></i>

Save Purchase

</button>

</div>

</div>

</form>

</div>

</div>
<script>



console.log("Purchase JavaScript Loaded");

alert("Purchase JS Loaded");

let purchaseItems = [];

document.getElementById("addItem").addEventListener("click", function(){

    let drug = document.getElementById("drug");

    let qty = parseInt(document.getElementById("qty").value);

    let buying = parseFloat(document.getElementById("buying").value);

    let selling = parseFloat(document.getElementById("selling").value);

    let expiry = document.getElementById("expiry").value;
   let manufacture = document.getElementById("manufacture").value;
    if(
        drug.value=="" ||
        isNaN(qty) ||
        qty<=0 ||
        isNaN(buying) ||
        buying<=0 ||
        isNaN(selling) ||
        selling<=0 ||
        expiry==""
    ){

        alert("Please complete all fields.");

        return;

    }

    let option = drug.options[drug.selectedIndex];

    let subtotal = qty * buying;

    purchaseItems.push({

        drug_id: drug.value,

        drug_name: option.dataset.name,

        quantity: qty,

        buying_price: buying,

        selling_price: selling,

        expiry_date: expiry,
manufacture_date: manufacture,
        subtotal: subtotal

    });

    renderPurchase();

    document.getElementById("qty").value="";
    document.getElementById("buying").value="";
    document.getElementById("selling").value="";
    document.getElementById("expiry").value="";
    drug.selectedIndex=0;

});


function renderPurchase(){

    let tbody = document.querySelector("#purchaseTable tbody");

    tbody.innerHTML="";

    let grand=0;

    purchaseItems.forEach(function(item,index){

        grand += item.subtotal;

        tbody.innerHTML += `

        <tr>

        <td>${item.drug_name}</td>

        <td>${item.quantity}</td>

        <td>${item.buying_price.toLocaleString()}</td>

        <td>${item.selling_price.toLocaleString()}</td>

        <td>${item.expiry_date}</td>

        <td>${item.subtotal.toLocaleString()}</td>

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

    document.getElementById("grandTotal").innerHTML =
        "UGX " + grand.toLocaleString();

    document.getElementById("itemsInput").value =
        JSON.stringify(purchaseItems);

}

function removeItem(index){

    purchaseItems.splice(index,1);

    renderPurchase();

}

</script>
<?php include "../../resources/views/layouts/footer.php"; ?>