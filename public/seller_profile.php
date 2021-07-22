<?php
session_start();
 
require_once('../private/Database.php');
$db=new Database();
$id=$_SESSION['seller_id'];
$db->registerSellerBank($id);
$profile=$db->sellerprofile($id);
$bank=$db->sellerbankdetails($id);
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    
    $id=$_SESSION['seller_id'];
    $pin_err='';
    $acc_err='';
    $ifcs_err='';
    $cbin_err='';
    $name=filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $email=filter_var($_POST['email'],FILTER_SANITIZE_STRING);
    $flat=filter_var($_POST['flat'],FILTER_SANITIZE_STRING);
    $building=filter_var($_POST['building'],FILTER_SANITIZE_STRING);
    $street=filter_var($_POST['street'],FILTER_SANITIZE_STRING);
    $area=filter_var($_POST['area'],FILTER_SANITIZE_STRING);
    $district=filter_var($_POST['district'],FILTER_SANITIZE_STRING);
    $state=filter_var($_POST['state'],FILTER_SANITIZE_STRING);
    $country=filter_var($_POST['country'],FILTER_SANITIZE_STRING);
    $PIN=filter_var($_POST['pin'],FILTER_SANITIZE_STRING);
    $account_number=filter_var($_POST['account'],FILTER_SANITIZE_STRING);
    $IFCS=filter_var($_POST['ifcs'],FILTER_SANITIZE_STRING);
    $CBIN=filter_var($_POST['cbin'],FILTER_SANITIZE_STRING);
    if(strlen($PIN)!=6)
    {
        $pin_err="invalid pin!";
    }
    if(strlen($account_number)!=11)
    {
        $acc_err="invalid Account number!";
    }
    if(strlen($IFCS)!=11)
    {
        $ifcs_err="invalid IFCS number!";
    }
    if(strlen($CBIN)!=11)
    {
        $cbin_err="invalid CBIN number!";
    }
    if(empty($pin_err) && empty($acc_err) && empty($ifcs_err) && empty($cbin_err))
    {
    if($db->updateSeller($id,$name,$email,$flat,$building,$street,$area,$district,$state,$country,$PIN))
    {
        $db->updateSellerBank($id,$account_number,$IFCS,$CBIN);
        header("refresh:0.1; url=seller_profile.php");
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Shop | Upload Product</title>

    <!-- Font awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- SmartMenus jQuery Bootstrap Addon CSS -->
    <link href="css/jquery.smartmenus.bootstrap.css" rel="stylesheet">
    <!-- Product view slider -->
    <link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.css">
    <!-- slick slider -->
    <link rel="stylesheet" type="text/css" href="css/slick.css">
    <!-- price picker slider -->
    <link rel="stylesheet" type="text/css" href="css/nouislider.css">
    <!-- Theme color -->
    <link id="switcher" href="css/theme-color/default-theme.css" rel="stylesheet">
    <!-- Top Slider CSS -->
    <link href="css/sequence-theme.modern-slide-in.css" rel="stylesheet" media="all">

    <!-- Main style sheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Google Font -->
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <?php if(isset($message)) : ?>
    <div class="alert alert-success text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- wpf loader Two -->
    <div id="wpf-loader-two">
        <div class="wpf-loader-two-inner">
            <span>Loading</span>
        </div>
    </div>
    <!-- / wpf loader Two -->
    <!-- SCROLL TOP BUTTON -->
    <a class="scrollToTop" href="#"><i class="fa fa-chevron-up"></i></a>
    <!-- END SCROLL TOP BUTTON -->


    <!-- Start header section -->
    <header id="aa-header">
        <!-- start header top  -->
        <div class="aa-header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-header-top-area">
                            <!-- start header top left -->
                            <div class="aa-header-top-left">
                                <!-- start language -->
                                <div class="aa-language">
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" type="button" id="dropdownMenu1"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <img src="img/flag/ind.jpg" alt="india flag">INDIA
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="#"><img src="img/flag/ind.jpg" alt="">INDIA</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- / language -->

                                <!-- start currency -->
                                <div class="aa-currency">
                                    <div class="dropdown">

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="#"><i class="fa fa-euro"></i>EURO</a></li>
                                            <li><a href="#"><i class="fa fa-jpy"></i>YEN</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- / currency -->
                                <!-- start cellphone -->
                                <div class="cellphone hidden-xs">
                                    <p><span class="fa fa-phone"></span>+91 7003465016 | +91 8777252070</p>
                                </div>
                                <!-- / cellphone -->
                            </div>
                            <!-- / header top left -->
                            <div class="aa-header-top-right">
                                <ul class="aa-head-top-nav-right">
                                    <?php if(isset($_SESSION['seller_email'])) : ?>
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    <li><a href="account.php"><?php echo $_SESSION['seller_name']; ?></a></li>
                                    <?php endif; ?>
                                    <!--<li class="hidden-xs"><a href="wishlist.php">Wishlist</a></li>
                                    <li class="hidden-xs"><a href="cart.php">My Cart</a></li>
                                    <li class="hidden-xs"><a href="checkout.php">Checkout</a></li>-->
                                    <?php if(isset($_SESSION['seller_email'])) : ?>
                                    <li>
                                        <form action="index.php" method="POST">
                                            <input type="submit" value="Logout" class="logout">
                                            <input type="hidden" name="logout" value="logout_user">
                                        </form>
                                    </li>
                                    <?php endif; ?>


                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / header top  -->

        <!-- start header bottom  -->
        <div class="aa-header-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-header-bottom-area">
                            <!-- logo  -->
                            <div class="aa-logo">
                                <!-- Text based logo -->
                                <a href="index.php">
                                    <span class="fa fa-shopping-cart"></span>
                                    <p>daily<strong>Shop</strong> <span>Your Shopping Partner</span></p>
                                </a>
                                <!-- img based logo -->
                                <!-- <a href="index.php"><img src="img/logo.jpg" alt="logo img"></a> -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / header bottom  -->
    </header>
    <!-- / header section -->
    <!-- menu -->
    <section id="menu">
        <div class="container">
            <div class="menu-area">
                <!-- Navbar -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="navbar-collapse collapse">
                        <!-- Left nav -->
                        <ul class="nav navbar-nav">
                            <li><a href="seller_profile.php"> Profile</a></li>
                            <li><a href="seller_database.php">Products</a></li>
                            <li><a href="upload.php">Upload</a></li>

                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- / menu -->
    <!-- Cart view section -->
    <section id="aa-myaccount">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-myaccount-area">
                        <div class="row justify-content-center">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="aa-myaccount-register">
                                    <h4 align='center'>Hi <?php echo($profile->name);?></h4>
                                    <form action="seller_profile.php" method="POST" class="aa-login-form"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left collapsed"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapseOne" aria-expanded="false"
                                                                aria-controls="collapseOne">
                                                                Your Profile
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                        data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Name:
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="name"
                                                                        placeholder="username"
                                                                        value="<?php echo($profile->name);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Email Address:
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="email"
                                                                        placeholder="username"
                                                                        value="<?php echo($profile->email);?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header" id="headingTwo">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left collapsed"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapseTwo" aria-expanded="false"
                                                                aria-controls="collapseTwo">
                                                                Address
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                        data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Flat :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="flat" placeholder="flat"
                                                                        value="<?php echo($profile->flat);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Building :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="building"
                                                                        placeholder="building"
                                                                        value="<?php echo($profile->building);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Street :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="street"
                                                                        placeholder="street"
                                                                        value="<?php echo($profile->street);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Area :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="area" placeholder="area"
                                                                        value="<?php echo($profile->area);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    District :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="district"
                                                                        placeholder="district"
                                                                        value="<?php echo($profile->district);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    State :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="state" placeholder="state"
                                                                        value="<?php echo($profile->state);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Country :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="country"
                                                                        placeholder="country name"
                                                                        value="<?php echo($profile->country);?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    PIN code :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="pin" placeholder="PIN code"
                                                                        value="<?php echo($profile->PINcode);?>"
                                                                        required
                                                                        class="<?php if(!empty($pin_err)) { echo 'is-invalid'; } ?>" />
                                                                    <?php if(!empty($pin_err)) { echo $pin_err; } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-header" id="headingThree">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left collapsed"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#collapseThree" aria-expanded="false"
                                                                aria-controls="collapseThree">
                                                                Account Details
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseThree" class="collapse"
                                                        aria-labelledby="headingThree" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    Account Number :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="account"
                                                                        placeholder="account"
                                                                        value="<?php echo($bank->account_number);?>"
                                                                        required
                                                                        class="<?php if(!empty($acc_err)) { echo 'is-invalid'; } ?>" />
                                                                    <?php if(!empty($acc_err)) { echo $acc_err; } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    IFCS Number :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="ifcs"
                                                                        placeholder="IFCS code"
                                                                        value="<?php echo($bank->IFCScode);?>" required
                                                                        class="<?php if(!empty($ifcs_err)) { echo 'is-invalid'; } ?>" />
                                                                    <?php if(!empty($ifcs_err)) { echo $ifcs_err; } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    CBIN Number :
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name='cbin'
                                                                        placeholder="CBIN number"
                                                                        value="<?php echo($bank->CBINno);?>" required
                                                                        class="<?php if(!empty($cbin_err)) { echo 'is-invalid'; } ?>" />
                                                                    <?php if(!empty($cbin_err)) { echo $cbin_err; } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5"></div>
                                                <div class="col-md-3"><input type="submit" name="upload" value="UPLOAD">
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- footer -->
    <footer id="aa-footer">
        <!-- footer bottom -->
        <div class="aa-footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-footer-top-area">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <h3>Main Menu</h3>
                                        <ul class="aa-footer-nav">
                                            <li><a href="#">Home</a></li>
                                            <li><a href="#">Our Services</a></li>
                                            <li><a href="#">Our Products</a></li>
                                            <li><a href="#">About Us</a></li>
                                            <li><a href="#">Contact Us</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <div class="aa-footer-widget">
                                            <h3>Knowledge Base</h3>
                                            <ul class="aa-footer-nav">
                                                <li><a href="#">Delivery</a></li>
                                                <li><a href="#">Returns</a></li>
                                                <li><a href="#">Services</a></li>
                                                <li><a href="#">Discount</a></li>
                                                <li><a href="#">Special Offer</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <div class="aa-footer-widget">
                                            <h3>Useful Links</h3>
                                            <ul class="aa-footer-nav">
                                                <li><a href="#">Site Map</a></li>
                                                <li><a href="#">Search</a></li>
                                                <li><a href="#">Advanced Search</a></li>
                                                <li><a href="#">Suppliers</a></li>
                                                <li><a href="#">FAQ</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <div class="aa-footer-widget">
                                            <h3>Contact Us</h3>
                                            <address>
                                                <p> kolkata</p>
                                                <p><span class="fa fa-phone"></span>+91 7003465016 | +91 8777252070</p>
                                                <p><span class="fa fa-envelope"></span>dailyshop@gmail.com</p>
                                            </address>
                                            <div class="aa-footer-social">
                                                <a href="#"><span class="fa fa-facebook"></span></a>
                                                <a href="#"><span class="fa fa-twitter"></span></a>
                                                <a href="#"><span class="fa fa-google-plus"></span></a>
                                                <a href="#"><span class="fa fa-youtube"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer-bottom -->
        <div class="aa-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-footer-bottom-area">
                            <p>Designed by <a href="http://www.markups.io/">MarkUps.io</a></p>
                            <div class="aa-footer-payment">
                                <span class="fa fa-cc-mastercard"></span>
                                <span class="fa fa-cc-visa"></span>
                                <span class="fa fa-paypal"></span>
                                <span class="fa fa-cc-discover"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- / footer -->
    <!-- Login Modal -->
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Login or Register</h4>
                    <form class="aa-login-form" action="">
                        <label for="">Username or Email address<span>*</span></label>
                        <input type="text" placeholder="Username or email">
                        <label for="">Password<span>*</span></label>
                        <input type="password" placeholder="Password">
                        <button class="aa-browse-btn" type="submit">Login</button>
                        <label for="rememberme" class="rememberme"><input type="checkbox" id="rememberme"> Remember
                            me </label>
                        <p class="aa-lost-password"><a href="#">Lost your password?</a></p>
                        <div class="aa-register-now">
                            Don't have an account?<a href="account.php">Register Now!</a>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>



    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="js/jquery.smartmenus.js"></script>
    <!-- SmartMenus jQuery Bootstrap Addon -->
    <script type="text/javascript" src="js/jquery.smartmenus.bootstrap.js"></script>
    <!-- To Slider JS -->
    <script src="js/sequence.js"></script>
    <script src="js/sequence-theme.modern-slide-in.js"></script>
    <!-- Product view slider -->
    <script type="text/javascript" src="js/jquery.simpleGallery.js"></script>
    <script type="text/javascript" src="js/jquery.simpleLens.js"></script>
    <!-- slick slider -->
    <script type="text/javascript" src="js/slick.js"></script>
    <!-- Price picker slider -->
    <script type="text/javascript" src="js/nouislider.js"></script>
    <!-- Custom js -->
    <script src="js/custom.js"></script>


</body>

</html>