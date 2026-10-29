<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../../config/session.php";
checkLogin();


require_once "../../app/Controllers/SaleController.php";


$controller = new SaleController();



if(!isset($_GET['id']))
{
    die("Invalid Sale");
}


$id = intval($_GET['id']);



$sale = $controller->find($id);


if(!$sale)
{
    die("Sale not found");
}



$items = $controller->items($id);



include "../../resources/views/layouts/header.php";
include "../../resources/views/layouts/sidebar.php";
include "../../resources/views/layouts/navbar.php";


?>
<style>

@media print {

    body * {
        visibility: hidden;
    }

    #receipt,
    #receipt * {
        visibility: visible;
    }

    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
        background: white;
    }

    .btn,
    .sidebar,
    .topbar,
    .navbar,
    footer {
        display: none !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid #000;
        padding: 6px;
    }

    h2,
    h3,
    h4,
    h5,
    p {
        margin: 5px 0;
    }

}

</style>

<div class="main-content">

<div class="container-fluid receipt-container">
<div id="receipt">
   <div class="receipt-header">

<h2>
MEDItrack Pharmacy
</h2>

<p>
Quality Healthcare Services
</p>

<p>
Tel: +256 760532673
</p>

<hr>

</div>
</div>




<div class="d-flex justify-content-between mb-4">


<h3 class="fw-bold">

<i class="fa fa-receipt text-primary"></i>

Sale Details

</h3>


<a href="receipt.php?id=<?= $sale['sale_id']; ?>"
class="btn btn-success"
target="_blank">

<i class="fa fa-print"></i>

Print Receipt

</a>


</div>




<div class="card shadow-sm mb-4 receipt-info">


<div class="card-body">


<h5>

Sale Number:

<strong>
<?= $sale['sale_number']; ?>
</strong>

</h5>


<p>

Date:

<?= date(
"d M Y H:i",
strtotime($sale['sale_date'])
); ?>

</p>


<p>

Customer:

<?= htmlspecialchars(
$sale['customer_name'] ?? "Walk-in Customer"
); ?>

</p>



<p>

Payment:

<?= $sale['payment_method']; ?>

</p>



<p>

Cashier:

<?= $sale['first_name']." ".$sale['last_name']; ?>

</p>



</div>


</div>





<div class="card shadow-sm">


<div class="card-body">


<table class="table table-bordered">


<thead>

<tr>

<th>
Medicine
</th>


<th>
Batch
</th>


<th>
Quantity
</th>


<th>
Price
</th>


<th>
Subtotal
</th>


</tr>


</thead>



<tbody>


<?php foreach($items as $item): ?>


<tr>


<td>

<?= htmlspecialchars(
$item['drug_name']
); ?>


<br>

<small>

<?= htmlspecialchars(
$item['generic_name']
); ?>

</small>


</td>


<td>

<?= $item['batch_number']; ?>

</td>



<td>

<?= $item['quantity']; ?>

</td>



<td>

UGX <?= number_format(
$item['selling_price']
); ?>

</td>



<td>

UGX <?= number_format(
$item['subtotal']
); ?>

</td>



</tr>


<?php endforeach; ?>



</tbody>


<tfoot>


<tr>

<th colspan="4">

TOTAL

</th>


<th>

UGX <?= number_format(
$sale['total_amount']
); ?>


</th>


</tr>


</tfoot>


</table>


</div>


</div>

<div class="text-center mt-4">

<hr>

<h5>
Thank you for choosing MEDItrack Pharmacy
</h5>

<p>
Goods sold are not returnable without approval.
</p>

</div>
</div>


</div>


<?php

include "../../resources/views/layouts/footer.php";

?>