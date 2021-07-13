<?php

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['logout'])){
    if($_POST['logout'] == 'logout_seller'){
        session_destroy();
        header('location:http://localhost/college_ecom/public/seller_register.php');
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
    <title>Document</title>
</head>

<body>
    <?php if(isset($_SESSION['email'])): ?>
    <div>
        <form action="seller-dashboard.php" method="POST">
            <input type="submit" value="Logout">
            <input type="hidden" name="logout" value="logout_seller">
        </form>
    </div>
    <?php endif; ?>
</body>

</html>