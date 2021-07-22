<?php 
session_start();
require_once('../private/Database.php');
require_once('./response.php');
$db = new Database;
$response = new Response;



//allow cart page access to only logged in user
if(empty($_SESSION['email'])){ 
  header('location:http://localhost/college_ecom/public/account.php');
}

// store item into cart
   if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
      $productId = $_GET['id'];
      //get product details
       $productDetails = $db->getProductDetails(base64_decode($productId)); 
      //get user id
      $userId = $_SESSION['id'];
      //add product to cart
      $db->addProductToCart($productDetails,$userId);
   }


 //cart items
 if(!empty($_SESSION['id'])){
  $carts = $db->getCartByUser($_SESSION['id']);
 }
  //cart total price
  $cartTotal = 0;
  if(!empty($carts)){
  foreach($carts as $cart){
      $cartTotal +=  $cart->price;
    }
}

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
       //logout user
       if(isset($_POST['logout'])){
        if($_POST['logout'] == 'logout_user'){
            session_destroy();
           }
        }

        //login using modal
        if(isset($_POST['email'])){
        $email =  filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);



        if($db->validateUserEmail($email)){
            $userDetails = $db->userDetails($email);

            if(password_verify($password,$userDetails->password)){
                $_SESSION['name'] = $userDetails->name;
                $_SESSION['id'] = $userDetails->id;
                $_SESSION['email'] = $userDetails->email;
                $response->success("Login Success");
                exit;
            }else{
                $response->error("Invalid login Credentials!");
                exit;
            }

        }else{
            $response->error("Email Not Found!Please Sign Up.");
            exit;
        }

        }

        if(isset($_POST['key']) && isset($_POST['id'])){
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);

            //delete product from cart
           $db->deleteCartItem($id);
         
          
           //update cart details
           $carts = $db->getCartByUser($_SESSION['id']);
           $cartTotal = 0;
           foreach($carts as $cart){
            $cartTotal +=  $cart->price;
          }

          return $response->success('Cart Item Removed Successfully!',$cartTotal);
        }

        if(isset($_POST['key']) && isset($_POST['product_id'])){
            $id = filter_var($_POST['product_id'],FILTER_SANITIZE_NUMBER_INT);
            $quantity = filter_var($_POST['quantity'],FILTER_SANITIZE_NUMBER_INT);

            $finalquantity = $db->updateCart($id,$quantity);

            $cartSingleElement = $db->getCartSingleElement($id);
           
            $carts = $db->getCartByUser($_SESSION['id']);
            $cartTotal = 0;
            foreach($carts as $cart){
             $cartTotal +=  $cart->price;
           }

           $data = [ "quantity" => $finalquantity , "cartTotal" => $cartTotal ,"productTotalPrice" => $cartSingleElement->price ];

           return $response->success('Cart Item Updated Successfully!',$data);
 

        }

  }
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Shop | Cart Page</title>

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
                                            <img src="img/flag/ind.jpg" alt="India flag">INDIA
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
                                    <?php if(isset($_SESSION['email'])) : ?>
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    <li><a href="user_profile.php"><?php echo $_SESSION['name']; ?></a></li>
                                    <?php endif; ?>
                                    <?php if(!isset($_SESSION['email'])) : ?>
                                    <li><a href="account.php">My Account</a></li>
                                    <?php endif; ?>
                                    <?php if(!isset($_SESSION['email'])) : ?>
                                    <li><a href="seller_register.php">Become A Seller</a></li>
                                    <?php endif; ?>
                                    <li class="hidden-xs"><a href="wishlist.php">Wishlist</a></li>
                                    <li class="hidden-xs"><a href="cart.php">My Cart</a></li>
                                    <?php if(isset($_SESSION['email'])) : ?>
                                    <li><a href="user_order.php">My Order</a></li>
                                    <?php endif; ?>
                                    <li class="hidden-xs"><a href="checkout.php">Checkout</a></li>
                                    <?php if(isset($_SESSION['email'])) : ?>
                                    <li>
                                        <form action="index.php" method="POST">
                                            <input type="submit" value="Logout" class="logout">
                                            <input type="hidden" name="logout" value="logout_user">
                                        </form>
                                    </li>
                                    <?php else: ?>
                                    <li><a href="" data-toggle="modal" data-target="#login-modal">Login</a></li>
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
                            <!-- / logo  -->
                            <!-- cart box -->
                            <div class="aa-cartbox">
                                <a class="aa-cart-link" href="cart.php">
                                    <span class="fa fa-shopping-basket"></span>
                                    <span class="aa-cart-title">SHOPPING CART</span>
                                    <?php if(!empty($_SESSION['id'])): ?><span
                                        class="aa-cart-notify"><?php  echo $db->getCartCount($_SESSION['id']);  ?></span><?php endif; ?>
                                </a>
                                <div class="aa-cartbox-summary">
                                    <ul>
                                        <?php if(!empty($carts)): ?>
                                        <?php foreach($carts as $cart): ?>
                                        <li>
                                            <a class="aa-cartbox-img" href="#"><img
                                                    src="photos/<?php echo $cart->product_image; ?>" alt="img"></a>
                                            <div class="aa-cartbox-info">
                                                <h4><a href="#"><?php echo $cart->product_name; ?></a></h4>
                                                <p><?php echo $cart->quantity; ?> x <?php echo $cart->price; ?></p>
                                            </div>
                                            <a class="aa-remove-product" href="#"><span class="fa fa-times"></span></a>
                                        </li>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                        <li>
                                            <span class="aa-cartbox-total-title">
                                                Total
                                            </span>
                                            <span class="aa-cartbox-total-price">
                                                &#8377; <?php echo $cartTotal; ?>
                                            </span>
                                        </li>
                                    </ul>
                                    <?php if(!isset($_SESSION['id'])): ?>
                                    <a class="aa-cartbox-checkout aa-primary-btn"
                                        href="http://localhost/college_ecom/public/account.php">Login</a>
                                    <?php else: ?>
                                    <a class="aa-cartbox-checkout aa-primary-btn"
                                        href="http://localhost/college_ecom/public/checkout.php">Checkout</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- / cart box -->
                            <!-- search box -->
                            <div class="aa-search-box">
                                <form action="">
                                    <input type="text" name="" id="" placeholder="Search here ex. 'man' ">
                                    <button type="submit"><span class="fa fa-search"></span></button>
                                </form>
                            </div>
                            <!-- / search box -->
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
                            <li><a href="index.php">Home</a></li>

                            <li><a href="http://localhost/college_ecom/public/product.php?id=1">Sports</a></li>
                            <li><a href="http://localhost/college_ecom/public/product.php?id=2">Electronics</a>
                            </li>

                            <li><a href="http://localhost/college_ecom/public/contact.php">Contact</a></li>

                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- / menu -->

    <!-- catg header banner section -->
    <section id="aa-catg-head-banner">
        <img src="img/cart.png" alt="fashion img" class="create_account_banner">
        <div class="aa-catg-head-banner-area">
            <div class="container">
                <div class="aa-catg-head-banner-content">
                    <h2>Cart Page</h2>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Cart</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- / catg header banner section -->

    <!-- Cart view section -->
    <section id="cart-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="cart-view-area">
                        <div class="cart-view-table">
                            <form action="">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <div id="delete_cart_message" class="text-danger"></div>
                                            <?php if(!empty($carts)): ?>
                                            <?php foreach($carts as $cart): ?>
                                            <tr>
                                                <td class="hide_id"> <a href=""><?php echo $cart->product_id; ?></a>
                                                </td>
                                                <td><a class="remove" href="#">
                                                        <fa class="fa fa-close"></fa>
                                                    </a></td>
                                                <td><a href="#"><img src="./photos/<?php echo $cart->product_image; ?>"
                                                            alt="img"></a></td>
                                                <td><a class="aa-cart-title"
                                                        href="#"><?php echo $cart->product_name;?></a></td>
                                                <td><?php echo $cart->product_price;?></td>
                                                <td><input class="aa-cart-quantity" id="cart_quantity" type="number"
                                                        value="<?php echo $cart->quantity; ?>"></td>
                                                <td><?php echo $cart->price; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <!-- Cart Total view -->
                            <div class="cart-view-total">
                                <h4>Cart Totals</h4>
                                <table class="aa-totals-table">
                                    <tbody>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td id="subtotal"><?php echo $cartTotal; ?></td>

                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td id="total"><?php echo $cartTotal; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="http://localhost/college_ecom/public/checkout.php"
                                    class="aa-cart-view-btn">Proced to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Cart view section -->


    <!-- Subscribe section -->
    <section id="aa-subscribe">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-subscribe-area">
                        <h3>Subscribe our newsletter </h3>
                        <p>24/7 Hours available service.Get the latest ServiceNow update</p>
                        <form action="" class="aa-subscribe-form">
                            <input type="email" name="" id="" placeholder="Enter your Email">
                            <input type="submit" value="Subscribe">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Subscribe section -->

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
                                                <p>Kolkata</p>
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
                    <form class="aa-login-form" id="login_form">
                        <div id="login_err" class="text-danger"></div>
                        <label for=""> Email address<span>*</span></label>
                        <input type="text" placeholder="user@gmail.com" required id="email">
                        <label for="">Password<span>*</span></label>
                        <input type="password" placeholder="Password" id="password" required name="password">
                        <button class="aa-browse-btn" type="submit">Login</button>
                        <label for="rememberme" class="rememberme"><input type="checkbox" id="rememberme"> Remember me
                        </label>
                        <p class="aa-lost-password"><a href="#">Lost your password?</a></p>
                        <div class="aa-register-now">
                            Don't have an account?<a href="account.php">Register now!</a>
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
    <script src="js/main.js"></script>

    <script>
    document.body.addEventListener("click", deleteCartProduct)

    function deleteCartProduct(e) {

        let productId = 0;

        //Fetch Product Id From DOM
        if (e.target.parentElement.classList.contains('remove')) {
            e.preventDefault();
            productId = e.target.parentElement.parentElement.parentElement.firstElementChild.innerText
            e.target.parentElement.parentElement.parentElement.remove();

            //remove product from cart
            $.ajax({
                url: "cart.php",
                data: {
                    key: "deleteCartItem",
                    id: productId,
                },
                method: "POST",
                success: function(response) {
                    let responseData = JSON.parse(response)
                    if (responseData.status == 403) {

                    }

                    if (responseData.status == 200) {
                        //update total and subtotal values
                        $('#total').html(responseData.data)
                        $('#subtotal').html(responseData.data)
                        $('#delete_cart_message').html(responseData.message)
                    }

                },
                error: function(response) {

                }
            });
        }


        //update cart quantity
        if (e.target.classList.contains("aa-cart-quantity")) {
            let quantity = e.target.value;
            let product_id = e.target.parentElement.parentElement.firstElementChild.innerText;
            let cartTotalSingleElement = e.target.parentElement.parentElement.lastElementChild;

            $.ajax({
                url: "cart.php",
                data: {
                    key: "update_cart_quantity",
                    product_id: product_id,
                    quantity: quantity
                },
                method: "POST",
                success: function(response) {
                    let responseData = JSON.parse(response)
                    if (responseData.status == 403) {

                    }

                    if (responseData.status == 200) {
                        //update total and subtotal values
                        if (responseData.data.quantity > 0) {
                            $('#total').html(responseData.data.cartTotal)
                            $('#subtotal').html(responseData.data.cartTotal)
                            $('.aa-cart-quantity').html(responseData.data.quantity)
                            cartTotalSingleElement.innerText = responseData.data.productTotalPrice

                        }

                    }

                },
                error: function(response) {

                }
            });
        }


    }
    </script>

</body>


</html>