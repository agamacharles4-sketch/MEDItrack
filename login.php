<?php

require_once "app/Models/User.php";

session_name('MEDITRACK_SESSION');
session_start();


$message = "";


if(isset($_POST['login']))
{

    $username = trim($_POST['username']);

    $password = $_POST['password'];


    $userModel = new User();


    $user = $userModel->login($username);



    if($user && password_verify($password, $user['password']))
    {

        session_regenerate_id(true);


        $_SESSION['user_id'] = $user['user_id'];

        $_SESSION['username'] = $user['username'];

        $_SESSION['role'] = $user['role'];



        require_once __DIR__ . "/app/Models/ActivityLog.php";


        $activity = new ActivityLog();


        $activity->create(

            $user['user_id'],

            "Logged into MEDItrack system"

        );


        if(isset($_SESSION['redirect_after_login']))
{

    $redirect = $_SESSION['redirect_after_login'];

    unset($_SESSION['redirect_after_login']);

    header("Location: ".$redirect);

}
else
{

   if(isset($_SESSION['redirect_after_login']))
{

    $redirect = $_SESSION['redirect_after_login'];

    unset($_SESSION['redirect_after_login']);

    header("Location: ".$redirect);

}
else
{

    header("Location: dashboard/");

}

exit();

}

exit();


    }
    else
    {

        $message = "Invalid username or password";

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
MEDItrack | Login
</title>


<link rel="stylesheet" href="/MEDItrack/public/assets/vendor/bootstrap/css/bootstrap.min.css">


<link rel="stylesheet" href="/MEDItrack/public/assets/vendor/fontawesome/css/all.min.css">


<style>

* {
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;

    background-image:
        linear-gradient(
            rgba(3, 55, 80, 0.72),
            rgba(0, 105, 85, 0.60)
        ),
        url('/MEDItrack/public/assets/images/pharmacy-background.png');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    font-family: 'Segoe UI', sans-serif;
    padding: 30px;
}


/* Main Login Card */

.login-card {
    width: 100%;
    max-width: 460px;

    background: rgba(255, 255, 255, 0.94);

    border: 1px solid rgba(255, 255, 255, 0.45);

    border-radius: 24px;

    padding: 42px;

    box-shadow:
        0 25px 60px rgba(0, 0, 0, 0.30);

    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);

    animation: loginCardAppear 0.6s ease;
}


/* Login Card Animation */

@keyframes loginCardAppear {

    from {
        opacity: 0;
        transform: translateY(25px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }

}


/* Logo */

.logo {
    text-align: center;
    margin-bottom: 30px;
}

.logo {
    text-align: center;
    margin-bottom: 30px;
}

.meditrack-logo {
    width: 230px;
    height: 230px;

    object-fit: contain;

    display: block;
    margin: 0 auto;

    background: white;

    border-radius: 50%;

    padding: 12px;

    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.15);

    border: 4px solid rgba(25, 135, 84, 0.15);
}


.logo i {
    display: inline-flex;

    width: 78px;
    height: 78px;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: linear-gradient(
        135deg,
        #0757a0,
        #198754
    );

    color: white;

    font-size: 38px;

    margin-bottom: 15px;

    box-shadow:
        0 10px 25px rgba(7, 87, 160, 0.25);
}


.logo h2 {
    margin-bottom: 5px;

    font-weight: 700;

    color: #0757a0;

    letter-spacing: -0.5px;
}


.logo span {
    color: #198754;
}


.logo p {
    margin-bottom: 0;

    color: #6c7a89 !important;

    font-size: 14px;
}


/* Form */

.form-label {
    color: #29465b;

    font-weight: 600;

    margin-bottom: 8px;
}


.form-control {
    height: 50px;

    border-radius: 0 10px 10px 0;

    border: 1px solid #dce4ea;

    box-shadow: none;

    font-size: 14px;
}


.form-control:focus {
    border-color: #198754;

    box-shadow:
        0 0 0 3px rgba(25, 135, 84, 0.12);
}


.input-group-text {
    min-width: 48px;

    justify-content: center;

    background: #f4f8fa;

    border: 1px solid #dce4ea;

    border-right: none;

    border-radius: 10px 0 0 10px;

    color: #198754;
}


.input-group .btn {
    border: 1px solid #dce4ea;

    border-left: none;

    border-radius: 0 10px 10px 0;

    color: #6c757d;
}


.input-group .btn:hover {
    color: #0757a0;
}


/* Login Button */

.btn-login {
    height: 52px;

    border: none;

    border-radius: 11px;

    font-weight: 600;

    font-size: 16px;

    background: linear-gradient(
        135deg,
        #0757a0,
        #198754
    );

    box-shadow:
        0 8px 20px rgba(7, 87, 160, 0.22);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}


.btn-login:hover {
    transform: translateY(-2px);

    box-shadow:
        0 12px 25px rgba(7, 87, 160, 0.30);
}


/* Error Message */

.alert {
    border-radius: 10px;

    font-size: 14px;
}


/* Footer */

.footer-text {
    text-align: center;

    margin-top: 25px;

    color: #7b8790;

    font-size: 13px;

    line-height: 1.7;
}


/* Mobile */

@media (max-width: 576px) {

    body {
        padding: 18px;
    }

    .login-card {
        padding: 30px 22px;

        border-radius: 20px;
    }

}
.home-button {
    margin-top: 15px;
}

.home-button .btn {
    height: 45px;

    border-radius: 10px;

    font-weight: 600;

    color: #198754;

    border-width: 2px;

    transition: all 0.2s ease;
}

.home-button .btn:hover {
    background: #198754;

    color: white;

    transform: translateY(-1px);
}
</style>

</head>



<body>



<div class="login-card">



<div class="logo">

    <img
        src="/MEDItrack/public/assets/images/meditrack-logo-cropped.png"
        alt="MEDItrack Logo"
        class="meditrack-logo"
    >

</div>





<?php if($message!=""): ?>


<div class="alert alert-danger text-center">

<i class="fa fa-circle-exclamation"></i>

<?= htmlspecialchars($message); ?>

</div>


<?php endif; ?>






<form method="POST">





<div class="mb-3">


<label class="form-label">

Username

</label>


<div class="input-group">


<span class="input-group-text">

<i class="fa fa-user"></i>

</span>


<input

type="text"

name="username"

class="form-control"

placeholder="Enter username"

required

>


</div>


</div>






<div class="mb-4">


<label class="form-label">

Password

</label>


<div class="input-group">


<span class="input-group-text">

<i class="fa fa-lock"></i>

</span>



<input

type="password"

name="password"

id="password"

class="form-control"

placeholder="Enter password"

required

>



<button

type="button"

class="btn btn-light"

onclick="togglePassword()">

<i class="fa fa-eye"></i>

</button>



</div>


</div>







<button

class="btn btn-success w-100 btn-login"

name="login">

<i class="fa fa-sign-in-alt"></i>

Login

</button>



</form>

<div class="home-button">

    <a href="/MEDItrack/index.php" class="btn btn-outline-success w-100">

        <i class="fa fa-home"></i>

        Back to Home

    </a>

</div>


<div class="footer-text">

© <?= date("Y"); ?> MEDItrack<br>

Pharmacy Inventory & Sales Management

</div>



</div>





<script>


function togglePassword()

{

    let password =
    document.getElementById("password");


    if(password.type==="password")
    {

        password.type="text";

    }

    else
    {

        password.type="password";

    }

}


</script>



</body>

</html>