<?php
session_start();

require_once('../private/Database.php');
require_once('../public/response.php');
$db = new Database;
$response = new Response;

$data = [ 
'user_id' => $_SESSION['id'],
'payment_id' => $_POST['razorpay_payment_id'],
'amount' => $_POST['totalAmount'],

];

//store payment details
$orderId = $db->storePaymentDetails($data['user_id'],$data['payment_id'],$data['amount']);

//create order deails
$db->storeOrderDetails($data['user_id'],$orderId);

$response->success('Payment Success');

// you can write your database insertation code here
// after successfully insert transaction in database, pass the response accordingly
// $arr = array('msg' => 'Payment successfully credited', 'status' => true);  
// return $data;
?>