<?php
session_name('MEDITRACK_SESSION');
session_start();

if(isset($_SESSION['user_id']))
{
    header("Location: dashboard/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>MEDItrack | Drug Expiry Tracking System</title>

<link rel="stylesheet" href="/MEDItrack/public/assets/vendor/bootstrap/css/bootstrap.min.css">

<link rel="stylesheet"<link rel="stylesheet" href="/MEDItrack/public/assets/vendor/fontawesome/css/all.min.css">
<style>

body{
background:#f8fafc;
font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;
}

.hero {

    position: relative;

    background-image:
        linear-gradient(
            rgba(3, 55, 80, 0.72),
            rgba(0, 105, 85, 0.60)
        ),
        url('/MEDItrack/public/assets/images/pharmacy-background.png');

    background-size: cover;

    background-position: center;

    background-repeat: no-repeat;

    color: white;

    padding: 120px 0;

    min-height: 620px;

    display: flex;

    align-items: center;
}
.hero .container {
    position: relative;
    z-index: 2;
}

.hero-content {
    background: rgba(255, 255, 255, 0.92);
    color: #12344d;
    padding: 45px;
    border-radius: 22px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
    backdrop-filter: blur(8px);
    max-width: 720px;
}
.hero-info-card {

    background: rgba(255,255,255,0.18);

    border: 1px solid rgba(255,255,255,0.35);

    backdrop-filter: blur(10px);

    -webkit-backdrop-filter: blur(10px);

    border-radius: 22px;

    padding: 40px;

    color: white;

    box-shadow: 0 15px 40px rgba(0,0,0,0.20);

}

.hero-info-card i {

    font-size: 75px;

    margin-bottom: 20px;

    color: #ffffff;

}

.hero-info-card h3 {

    font-weight: 600;

    margin-bottom: 15px;

}

.hero-info-card p {

    color: rgba(255,255,255,0.92);

    line-height: 1.7;

}
.hero-content h1 {
    color: #0757a0;
}

.hero-content p {
    color: #40566b;
    line-height: 1.8;
}
.feature-card{

transition:.3s;

border:none;

}

.feature-card:hover{

transform:translateY(-8px);

box-shadow:0 15px 35px rgba(0,0,0,.15);

}

.icon{

font-size:55px;

color:#198754;

margin-bottom:15px;

}

footer{

background:#198754;

color:white;

padding:25px;

margin-top:80px;

}

.btn-login{

padding:14px 40px;

font-size:18px;

border-radius:50px;
background:#198754;

}
.navbar-logo {
    width: 82px;
    height: 82px;

    object-fit: contain;

    background: white;

    border-radius: 50%;

    padding: 3px;

    border: 2px solid rgba(255,255,255,0.8);

    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}
.navbar {
    min-height: 82px;
     background: white;
}
</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success">

<div class="container">

<a class="navbar-brand fw-bold d-flex align-items-center" href="#">

    <img
        src="/MEDItrack/public/assets/images/meditrack-logo-cropped.png"
        alt="MEDItrack Logo"
        class="navbar-logo"
    >

</a>



</div>

</nav>

<section class="hero">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6 hero-content">

<h1 class="display-4 fw-bold">

Drug Expiry Tracking System

</h1>

<p class="lead mt-4">

MEDItrack helps pharmacists monitor medicine expiry dates,
manage inventory, track purchases and sales,
receive automatic expiry alerts,
and generate comprehensive pharmacy reports.

</p>

<a href="login.php"
class="btn btn-light btn-lg btn-login mt-3">

<i class="fa fa-lock"></i>

Login to System

</a>

</div>

<div class="col-lg-6 text-center">

    <div class="hero-info-card">

        <i class="fa-solid fa-shield-heart"></i>

        <h3>Safer Pharmacy Management</h3>

        <p>
            Monitor medicines, batches, expiry dates,
            stock levels and sales from one central system.
        </p>

    </div>

</div>



</div>

</div>

</div>

</section>

<div class="container mt-5">

<div class="text-center mb-5">

<h2 class="fw-bold">

System Features

</h2>

<p class="text-muted">

Everything required for efficient pharmacy inventory management.

</p>

</div>

<div class="row g-4">

<div class="col-md-4">

<div class="card feature-card h-100">

<div class="card-body text-center">

<div class="icon">

<i class="fa fa-pills"></i>

</div>

<h5>

Drug Management

</h5>

<p>

Manage medicines, categories, units and suppliers efficiently.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card feature-card h-100">

<div class="card-body text-center">

<div class="icon">

<i class="fa fa-boxes-stacked"></i>

</div>

<h5>

Inventory

</h5>

<p>

Track batches, stock quantities and expiry dates in real time.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card feature-card h-100">

<div class="card-body text-center">

<div class="icon">

<i class="fa fa-bell"></i>

</div>

<h5>

Notifications

</h5>

<p>

Receive automatic alerts for expiring and low-stock medicines.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card feature-card h-100">

<div class="card-body text-center">

<div class="icon">

<i class="fa fa-cart-shopping"></i>

</div>

<h5>

Sales Management

</h5>

<p>

Process sales quickly using FEFO batch allocation.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card feature-card h-100">

<div class="card-body text-center">

<div class="icon">

<i class="fa fa-truck"></i>

</div>

<h5>

Purchase Management

</h5>

<p>

Record purchases and monitor supplier transactions.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card feature-card h-100">

<div class="card-body text-center">

<div class="icon">

<i class="fa fa-chart-column"></i>

</div>

<h5>

Reports

</h5>

<p>

Generate sales, inventory, expiry and purchase reports instantly.

</p>

</div>

</div>

</div>

</div>

</div>

<section class="container mt-5">

<div class="row">

<div class="col-lg-6">

<h2 class="fw-bold">

Why MEDItrack?

</h2>

<ul class="list-group list-group-flush">

<li class="list-group-item">✔ Prevent dispensing expired medicines</li>

<li class="list-group-item">✔ Reduce stock losses</li>

<li class="list-group-item">✔ Automatic expiry notifications</li>

<li class="list-group-item">✔ Inventory monitoring</li>

<li class="list-group-item">✔ Secure pharmacist authentication</li>

<li class="list-group-item">✔ Fast reporting</li>

</ul>

</div>

<div class="col-lg-6 text-center">

<i class="fa-solid fa-hospital"
style="font-size:180px;color:#198754;"></i>

</div>

</div>

</section>

<footer>

<div class="container text-center">

<h5>

MEDItrack

</h5>

<p>

Drug Expiry Tracking & Pharmacy Inventory Management System

</p>

<hr>

<p>

Developed by <strong>Charles Agama</strong><br>

Bachelor of Computer Science<br>

Kumi University &copy; 2026

</p>

</div>

</footer>

<script src="/MEDItrack/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>