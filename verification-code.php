<?php

use App\Database\Models\User;
use App\Http\Requests\Validation;

$title = "Verification Code";
include "layouts/header.php";
include "layouts/navbar.php";

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST){

  $validation = new Validation;

  $validation-> setInputValue($_POST['verification_code'])-> setInputValueName('verification code')-> required()-> digits(6);

  if(empty($validation->getErrors())){

    $user = new User;

    $user-> setVerification_code($_POST['verification_code']) ->setEmail($_SESSION['verification_email']);
    $result = $user->codeVerification() ;

    if($result == false){

      $error = "<div class='alert alert-danger'> Something went wrong </div>";

    }
    else{

      if($result->num_rows == 1){

        $user->setEmail_verified_at(date('Y-m-d H:i:s'));
        if($user->verifyUser()){

          unset($_SESSION['verification_email']);
          
          $success = "<div class='alert alert-success text-center'> Correct Code You Will be redirected to sign in page shotrly ... </div>";
          header('refresh:4;url=signin.php');

        }else{

          $error = "<div class='alert alert-danger'> Something went wrong </div>";

        }

      }
      else{

        $error = "<div class='alert alert-danger'> Wrong Code </div>";

      }
    }
    
  }
}

 ?>
<div class="login-register-area ptb-100">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 col-md-12 ml-auto mr-auto">
        <div class="login-register-wrapper">
          <div class="login-register-tab-list nav">
            <a class="active" data-toggle="tab" href="#lg1">
              <h4><?= $title ?></h4>
            </a>
           
          </div>
          <div class="tab-content">
            <div id="lg1" class="tab-pane active">
              <div class="login-form-container">
                <div class="login-register-form">
                  <?= $error ?? "" ?>
                  <?= $success ?? "" ?>
                  <form action="#" method="post">
                    <input type="number" name="verification_code" placeholder="Verification Code" />
                    <?= isset($validation) ? $validation->getMessage('verification code') : '' ?>
                    <div class="button-box">
                      <button type="submit"><span>Verify</span></button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>