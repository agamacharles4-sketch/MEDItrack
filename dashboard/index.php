<?php

require_once "../config/session.php";
require_once "../app/Controllers/DashboardController.php";

checkLogin();

$controller = new DashboardController();
$stats = $controller->statistics();

include "../resources/views/layouts/header.php";
include "../resources/views/layouts/sidebar.php";
include "../resources/views/layouts/navbar.php";

?>



<div class="main-content">

<div class="container-fluid">


<!-- Quick Actions -->

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h3 class="fw-bold">
Dashboard Overview
</h3>

<p class="text-muted">
Welcome back, <?= htmlspecialchars($_SESSION['username']); ?>
</p>

</div>



</div>




<!-- Statistics Cards -->

<div class="row g-4">


<div class="col-md-3">

<div class="card dashboard-card blue">

<div class="card-body">


<div class="d-flex justify-content-between">


<div>

<h6>
Total Drugs
</h6>


<h2>
<?= $stats['drugs']; ?>
</h2>


<p class="text-muted mb-0">

Registered Medicines

</p>


</div>


<div class="icon-box">

<i class="fa fa-pills"></i>

</div>


</div>


</div>

</div>

</div>





<div class="col-md-3">


<div class="card dashboard-card green">


<div class="card-body">


<div class="d-flex justify-content-between">


<div>

<h6>
Drug Batches
</h6>


<h2>
<?= $stats['batches']; ?>
</h2>


<p class="text-muted mb-0">

Available batches

</p>


</div>


<div class="icon-box green">

<i class="fa fa-box"></i>

</div>


</div>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card dashboard-card red">


<div class="card-body">


<div class="d-flex justify-content-between">


<div>


<h6>
Expiring Soon
</h6>

<h2>
<?= $stats['expiring']; ?>
</h2>

<p class="text-muted mb-0">

Within 30 days

</p>


</div>


<div class="icon-box red">

<i class="fa fa-triangle-exclamation"></i>

</div>


</div>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card dashboard-card purple">


<div class="card-body">


<div class="d-flex justify-content-between">


<div>


<h6>
Monthly Sales
</h6>


<h2>

UGX <?= number_format($stats['sales']); ?>

</h2>


<p class="text-muted mb-0">

Current Month

</p>


</div>


<div class="icon-box purple">

<i class="fa fa-money-bill"></i>

</div>


</div>


</div>


</div>


</div>



</div>






<!-- Charts Section -->


<div class="row mt-5 g-4">


<div class="col-md-8">


<div class="card">


<div class="card-header">

Monthly Sales

</div>


<div class="card-body">


<div class="card-body">

<div class="chart-container">

<canvas id="salesChart"></canvas>

</div>

</div>


</div>


</div>


</div>




<div class="col-md-4">


<div class="card">


<div class="card-header">

Drug Categories

</div>


<div class="card-body">


<div class="card-body">

<div class="chart-container">

<canvas id="categoryChart"></canvas>

</div>

</div>


</div>


</div>


</div>


</div>






<!-- Bottom Section -->


<div class="row mt-4 g-4">


<div class="col-md-6">


<div class="card">


</div>


</div>










</div>


</div>



</div>



</div>


</div>


<?php

include "../resources/views/layouts/footer.php";

?>
<script>
console.log("Dashboard chart script loaded");
const salesData = <?= json_encode($stats['salesChart']); ?>;
console.log("Sales Data:", salesData);

const salesLabels = salesData.map(
    item => item.month
);


const salesValues = salesData.map(
    item => item.total
);


new Chart(
document.getElementById('salesChart'),
{

type:'bar',

data:{

labels:salesLabels,

datasets:[{

label:'Monthly Sales (UGX)',

data:salesValues,

borderWidth:2

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

scales:{

y:{

beginAtZero:true

}

}

}

});



const categoryData = <?= json_encode($stats['categoryChart']); ?>;

console.log("Category Data:", categoryData);

const categoryLabels = categoryData.map(
    item => item.category_name
);



const categoryValues = categoryData.map(
    item => item.total
);



new Chart(
document.getElementById('categoryChart'),
{

type:'doughnut',

data:{

labels:categoryLabels,

datasets:[{

data:categoryValues,

borderWidth:2

}]

},

options:{

responsive:true,

maintainAspectRatio:false

}

});


</script>