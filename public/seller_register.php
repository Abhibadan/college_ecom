<?php 
 session_start();

 require_once('../private/Database.php');

 $db = new Database;
 $password_err = '';
 $confirm_password_err = '';
 $email_err = '';

 
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
      
      if(isset($_POST['signin'])){
        $email =  filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
       
        if($db->validateSellerEmail($email)){
            $sellerDetails = $db->sellerDetails($email);
            print_r($sellerDetails);
            if(password_verify($password,$sellerDetails->password)){
                $_SESSION['id'] = $sellerDetails->id;
                $_SESSION['name'] = $sellerDetails->name;
                $_SESSION['email'] = $sellerDetails->email;
                 header('location:http://localhost/college_ecom/public/seller-dashboard.php');      
              }
            }

        
      }
    

      if(isset($_POST['signup'])){
          //collect form data
            $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
            $confirmPassword = filter_var($_POST['confirm_password'],FILTER_SANITIZE_STRING);
            $gst = filter_var($_POST['gst'],FILTER_SANITIZE_STRING);
            $pan = filter_var($_POST['pan'],FILTER_SANITIZE_STRING);

        if(strlen($password) < 6 ){
           $password_err = "Paasword should be minimum 6 characters long!";
        }

        if($password !== $confirmPassword){
        $confirm_password_err = "Password and Confirm password do not match!";
        }

        if($db->validateSellerEmail($email)){
        $email_err = "Email Already Exists!";
        
        }

        if(empty($password_err) && empty($aadhaar_err) && empty($confirm_password_err) && empty($email_err)){
            $message = "Account Created Successfully.Kindly Login To proceed!";
            $db->registerSeller($name,$email,$password,$gst,$pan);
        }
      }
  }


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../public/css/seller.css">
    <title>Document</title>
</head>

<body>


    <div class="login-wrap" id="login">
        <h2>Login</h2>

        <form action="seller_register.php" method="POST" class="form">
            <input type="text" placeholder="email" name="email" required />
            <input type="password" placeholder="Password" name="password" required />
            <input type="hidden" name="signin" value="signin">
            <button type="submit"> Sign in </button>
            <a href="#" id="show_sign_up_form">
                <p> Don't have an account? Register </p>
            </a>
        </form>
    </div>


    <div class="login-wrap" id="signup">
        <h2>Sign Up</h2>

        <form action="seller_register.php" method="POST" class="form">
            <input type="text" placeholder="Name" name="name" required />
            <input type="text" placeholder="Email" name="email" required
                class="<?php if(!empty($email_err)) { echo 'is-invalid'; } ?>" />
            <div class="invalid-feedback text-danger">
                <?php if(!empty($email_err)) { echo $email_err; } ?>
            </div>
            <input type="password" placeholder="Password" name="password" required
                class="<?php if(!empty($password_err)) { echo 'is-invalid'; } ?>" />
            <div class="invalid-feedback text-danger">
                <?php if(!empty($password_err)) { echo $password_err; } ?>
            </div>
            <input type="password" placeholder="Confirm Password" name="confirm_password" required
                class="<?php if(!empty($confirm_password_err)) { echo 'is-invalid'; } ?>" />
            <div class="invalid-feedback text-danger">
                <?php if(!empty($confirm_password_err)) { echo $confirm_password_err; } ?>
            </div>
            <input type="text" placeholder="GST" name="gst" required />
            <input type="text" placeholder="PAN" name="pan" required />
            <input type="hidden" name="signup" value="signup" required />
            <button type="submit"> Sign UP </button>
            <a href="#" id="show_sign_in_form">
                <p> Have an account? Sign In </p>
            </a>
        </form>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
// vertical align box   


$('#show_sign_up_form').click(() => {

    $('#login').hide();
    $('#signup').show();
})

$('#show_sign_in_form').click(() => {

    $('#login').show();
    $('#signup').hide();
})
</script>

</html>