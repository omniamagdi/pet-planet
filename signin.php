<?php

use App\Database\Models\User;
use App\Http\Requests\Validation;

$title = "Signin";

include "layouts/header.php";
include "layouts/navbar.php";

 $validation = new Validation;

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST){

    $validation-> setInputValue($_POST['email'])-> setInputValueName('email')-> required()-> regex('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/',"wrong email or password")-> exists('users','email');
    
    $validation->setInputValue($_POST['password'])-> setInputValueName('password')-> required()-> regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/',"wrong email or password");
    
    if(empty($validation->getErrors())){

      $user = new User;
      
      $databaseResult = $user-> setEmail($_POST['email'])-> getUserInfo();
      
      if($databaseResult-> num_rows == 1){

        $databaseUser = $databaseResult-> fetch_object();

        if( password_verify($_POST['password'],$databaseUser->password)){

            if(is_null($databaseUser-> email_verified_at)){

              $_SESSION['verication_email'] = $_POST['email'];
              header('location:verification-code.php');die;
            }else{
              $_SESSION['user'] = $databaseUser;
              header('location:index.php');die;
            }
        }else{
          $error = "<p class='text-danger font-weight-bold'>wrong email or password</p>";
        }

      }
      else{
        $error = "<p class='text-danger font-weight-bold'>wrong email or password</p>";
      }
    }
}
 ?>

<div class="login-page">
  <div class="form">
    <div class="login">
      <div class="login-header">
        <h3 style="text-align:center;">Sign In</h3>
      </div>
    </div>

    <?=  $error ?? "" ?>
    <form class="login-form" action="#" method="post">

      <label for="email">Email*</label>
      <input id="email" type="text" placeholder="Enter your email..." name="email" />
      <?= $validation->getMessage('email') ?>

      <label for="password">Password*</label>
      <input id="password" type="password" placeholder="Enter your password..." name="password" />
      <?= $validation->getMessage('password') ?>

     <!-- <div class="button-box">
                      <div class="login-toggle-btn">
                        <input type="checkbox" />
                        <label>Remember me</label>
                        <a href="#">Forgot Password?</a>
                      </div>
      </div>-->

      <button>Sign in</button>
      <p style="text-align:center ;" class="message">Need an account? <a href="signup.php">Sign up</a></p>
    </form>
  </div>
</div>
<div class="images">
  <img class="image" src="assets/img/logo/Screenshot 2023-02-23 111004.jpg" alt="">
</div>