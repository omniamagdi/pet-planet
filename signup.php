<?php

use App\Http\Requests\Validation;
use App\Database\Models\User;
use App\Mail\VerificationCodeMail;

$title = "Signup";

include "layouts/header.php";
include "layouts/navbar.php";

$validation = new Validation;

  if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST){

    $validation-> setOldValues($_POST);

    $validation-> setInputValue($_POST['user'] ?? "")-> setInputValueName('user')-> required()->in(['Customer','Service provider']);

    $validation-> setInputValue($_POST['first_name'] ?? "")-> setInputValueName('first name')-> required()-> between(2,32);

    $validation-> setInputValue($_POST['last_name'] ?? "")-> setInputValueName('last name')-> required()-> between(2,32);

    $validation-> setInputValue($_POST['email'] ?? "")-> setInputValueName('email')-> required()-> regex('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/')-> unique('users','email');
    
    $validation-> setInputValue($_POST['phone'] ?? "")-> setInputValueName('phone')-> required()-> regex('/^01[0125][0-9]{8}$/')-> unique('users','phone');
    
    $validation-> setInputValue($_POST['password'] ?? "")-> setInputValueName('password')-> required()-> regex("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/","Minimum 8 and maximum 32 characters, at least one uppercase letter, one lowercase letter, one number and one special character:")-> confirmed($_POST['password_confirmation']);
    
    $validation-> setInputValue($_POST['password_confirmation'] ?? "")-> setInputValueName('password confirmation')-> required();
    
    $validation-> setInputValue($_POST['gender'] ?? "")-> setInputValueName('gender')-> required()->in(['m','f']);

    if(empty($validation->getErrors())){

        $verification_code = rand(100000,999999);

        $user = new User;
        
        $user->setFirst_name($_POST['first_name'])
        ->setLast_name($_POST['last_name'])
        ->setEmail($_POST['email'])
        ->setPassword($_POST['password'])
        ->setGender($_POST['gender'])
        ->setPhone($_POST['phone'])
        ->setVerification_code($verification_code)
        ->SetAdmin_status(0);

        //user type
        if($_POST['user'] == 'Customer'){
         $user ->setService_provider_status(0);
        }else{
          $user ->setService_provider_status(1);
        }
  
        if($user->create()){

            $subject = "Verification Mail";
            $body = "<p> Hello {$_POST['first_name']} {$_POST['last_name']}.</p>
            <p> Your Verification Code:<b style='color:blue;'>{$verification_code}</b></p>
            <p> Thank You</p>";

            $verificationMail = new VerificationCodeMail;

            if($verificationMail->send($_POST['email'],$subject,$body)){

              $_SESSION['verification_email'] = $_POST['email'];
              header('location:verification-code.php');die;

            }else{
              $error = "<div class='alert alert-danger' > Please Try Again Later </div>";
            }

        }else{

            $error = "<div class='alert alert-danger' > Something went wrong </div>";
        }
     }
  }

?>
<div class="login-page">
  <div class="form">
    <div class="login">
      <div class="login-header">
        <h3 class = "header2" style="text-align:center;">Sign up</h3>
      </div>
    </div>
    <?= $error ?? "" ?>
    <form class="login-form" action="" method="post">

      <label class="as">As</label><br>
     <!-- <label class = "check">Customer
            <input type="radio" name="as">
            <span></span>
      </label>
      <label>Service Provider
            <input type="radio" name="as">
            <span></span>
      </label>
      <br>-->
      <select name="user" class="form-control my-2" id="">
                          <option <?= $validation->getOldValue('user') == 'Customer' ? 'selected' : '' ?> value="Customer">Customer</option>
                          <option  <?= $validation->getOldValue('user') == 'Service provider' ? 'selected' : '' ?> value="Service provider">Service Provider</option>
                      </select>
                      <?= $validation->getMessage('user') ?>

      <label>First Name*</label>
      <input class = "i2" id="fn" type="text" placeholder="Enter your First Name..." name="first_name" value="<?= $validation->getOldValue('first_name') ?>"/>  
      <?= $validation->getMessage('first name') ?>

      <label>Last Name*</label>
      <input class = "i2" id="fn" type="text" placeholder="Enter your Last Name..." name="last_name" value="<?= $validation->getOldValue('last_name') ?>"/>  
      <?= $validation->getMessage('last name') ?>
      
      <label for="email">Email*</label>
      <input class = "i2" id="email" type="text" placeholder="Enter your email..." name="email" value="<?= $validation->getOldValue('email') ?>"/>
      <?= $validation->getMessage('email') ?>

      <label>Phone*</label>
      <input class = "i2" id="phone" type="phone" placeholder="Enter your phone..." name="phone" value="<?= $validation->getOldValue('phone') ?>" />
      <?= $validation->getMessage('phone') ?>

      <label for="password">Password*</label>
      <input class = "i2" id="password" type="password" placeholder="Enter your password..." name="password" />
      <?= $validation->getMessage('password') ?>

      <label>Confirm Password*</label>
      <input class = "i2" id="password" type="password" placeholder="Confirm your password..." name="password_confirmation" />
      <?= $validation->getMessage('password_confirmation') ?>

      <label>Gender*</label><br>
      <select name="gender" class="form-control my-2" id="">
                          <option <?= $validation->getOldValue('gender') == 'm' ? 'selected' : '' ?> value="m">Male</option>
                          <option  <?= $validation->getOldValue('gender') == 'f' ? 'selected' : '' ?> value="f">Female</option>
                      </select>
                      <?= $validation->getMessage('gender') ?>

      <button class = "b2">Sign up</button>
      <p style="text-align:center ;" class="message2">Already a user? <a href="#">Sign in</a></p>
    </form>
  </div>
</div>
<div class="images">
  <img class="image" src="assets/img/logo/Pink Background.png" alt="">
</div>
