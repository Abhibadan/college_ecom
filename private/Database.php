 <?php 

class Database{
    //Set database parameters
    // private $dbname = "college_ecom";
    private $dbname = "college_ecom_adminpanel";
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'abhibadan1234';
    protected $dbh , $stmt , $errmsg;
    
    public function __construct(){
       //Set dsn value
       $dsn =  'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
       
       //Set error type and establish persistent connection 
        $options = array(
            PDO::ATTR_PERSISTENT => 'true',
            PDO::ATTR_ERRMODE => 'PDO::EXCEPTION'
        );

        try{
            // Instantiate PDO class
            $this->dbh = new PDO($dsn,$this->username,$this->password,$options);
        }catch(PDOException $e){
            //Display errors if connection not established
            $this->errmsg = $e->getMessage();
            echo $this->errmsg;
        }
   }

  //  helpers
   
   public function prepare($sql){
     //prepare query
     $this->stmt = $this->dbh->prepare($sql);
   }

   public function bind($param,$value){
     //bind value to query
     $this->stmt->bindValue($param,$value);
   }
    
   public function execute(){
     //execute Query
     return $this->stmt->execute();
    
   }

   public function lastInsertedId(){
    return $this->dbh->lastInsertId();
   }

   public function fetchSingle(){
     //Fetch Single record from DB
      return $this->stmt->fetch(PDO::FETCH_OBJ);
   }
  
  public function fetchMultiple(){
    //Fetch Multiple Record From DB
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }
  
   public function rowCount(){
     //Fetch row count from DB
      return $this->stmt->rowCount();
  }

  public function getDate(){
    return date("Y-m-d h:i:s");
  }

  // end helpers

  public function validateUserEmail($email){
    //sql query to select user using email
    $sql = 'SELECT * FROM users WHERE email=:email';
    //prepare query
    $this->prepare($sql);
    //bind email value to :email
    $this->bind(':email',$email);
    //execute query
    $this->execute();
    //fetch rowCount
    $rowCount = $this->rowCount();
   //if rowCount > 0 i.e., user exists then return true else return false
    if($rowCount > 0){
        return true;
    }else{
        return false;
     }
  }

  public function validateSellerEmail($email){
  //sql query to select user using email
  $sql = 'SELECT * FROM sellers WHERE email=:email';
  //prepare query
  $this->prepare($sql);
  //bind email value to :email
  $this->bind(':email',$email);
  //execute query
  $this->execute();
  //fetch rowCount
  $rowCount = $this->rowCount();
  //if rowCount > 0 i.e., user exists then return true else return false
  if($rowCount > 0){
      return true;
  }else{
      return false;
    }
  }

  //register user
  public function registerUser($name,$aadhaar,$email,$password){
    $is_active = 1;
    $date = $this->getDate();
    $password = password_hash($password,PASSWORD_BCRYPT);
     $sql = 'INSERT INTO `users`(`name`, `email`, `aadhaar`, `password`, `is_active`,`created_at`) VALUES (:name,:email,:aadhaar,:password,:is_active,:created_at)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':aadhaar',$aadhaar);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':is_active',$is_active);
    $this->bind(':created_at',$date);

   //execute query
    if($this->execute()){
        return true;
    }else{
        return false;
    }
 }


  //register Seller
  public function registerSeller($name,$email,$password,$gst,$pan){
    $password = password_hash($password,PASSWORD_BCRYPT);
    $date = $this->getDate();
    $sql = 'Insert into sellers (name,email,password,gst,pan,created_at) values (:name,:email,:password,:gst,:pan,:created_at)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':gst',$gst);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':pan',$pan);
    $this->bind(':created_at',$date);

   //execute query
    if($this->execute()){
        return true;
    }else{
        return false;
    }
 }

 //Get User Details
 public function userDetails($email){
    //sql query to select user using email
    $sql = 'SELECT * FROM  users WHERE email=:email';
    //prepare query
    $this->prepare($sql);
      //bind email value to :email
    $this->bind(':email',$email);
    //execute query
    $this->execute();
    //fetch single user record
    $result = $this->fetchSingle();
    //return user details
    return $result;
}

//Get Seller Details
public function sellerDetails($email){
  //sql query to select user using email
  $sql = 'SELECT * FROM  sellers WHERE email=:email';
  //prepare query
  $this->prepare($sql);
    //bind email value to :email
  $this->bind(':email',$email);
  //execute query
  $this->execute();
  //fetch single user record
  $result = $this->fetchSingle();
  //return user details
  return $result;
}


public function getSportsProduct(){

  //sql query to select user sports products
  $sql = 'SELECT * FROM `products` join categories on products.category_ID = categories.category_ID
  Where category = "Sports"';
  //prepare query
  $this->prepare($sql);
  //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  return $result;

}

public function getElectronicsProduct(){

  //sql query to select user sports products
  $sql = 'SELECT * FROM `products` join categories on products.category_ID = categories.category_ID
  Where category = "Electronics"';
  //prepare query
  $this->prepare($sql);
  //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  return $result;

}

public function getProductDetails($productId){
   //sql query to select user sports products
   $sql = 'SELECT * FROM `products` Where productID = :productID';
   //prepare query
   $this->prepare($sql);
   //bind product id 
   $this->bind(':productID',$productId);
    //execute query
   $this->execute();
   //fetch multiple sports products record
   $result = $this->fetchSingle();
   //return sports products
   return $result;
}

public function addProductToCart($productDetails,$userId){
  $date = $this->getDate();
  $sql = 'Insert into carts (product_id,price,product_name,product_image,product_price,quantity,user_id,created_at) values (:product_id,:price,:product_name,:product_image,:product_price,:quantity,:user_id,:created_at)';
  $this->prepare($sql);
   // bind values to prepared variables
  $this->bind(':product_id',$productDetails->productID);
  $this->bind(':price',$productDetails->price);
  $this->bind(':product_price',$productDetails->price);
  $this->bind(':product_name',$productDetails->Pname);
  $this->bind(':product_image',$productDetails->image);
  $this->bind(':quantity',1);
  $this->bind(':user_id',$userId);
  $this->bind(':created_at',$date);

 //execute query
  if($this->execute()){
      return true;
  }else{
      return false;
  }
}

public function getCartByUser($userId){
  //sql query to select user sports products
  $sql = 'SELECT * FROM `carts` Where user_id = :userID';
  //prepare query
  $this->prepare($sql);
  //bind product id 
  $this->bind(':userID',$userId);
   //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  return $result;
}


   

public function getCartCount($userId){
  //sql query to select user sports products
  $sql = 'SELECT * FROM `carts` Where user_id = :userID';
  //prepare query
  $this->prepare($sql);
  //bind product id 
  $this->bind(':userID',$userId);
   //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  $rowCount = $this->rowCount();

  return $rowCount;
}

public function deleteCartItem($id){
  $sql = 'DELETE FROM `carts` WHERE product_id=:product_id';
  //prepare query
  $this->prepare($sql);
  //bind product id 
  $this->bind(':product_id',$id);
   //execute query
  if($this->execute()){
    return true;
  }else{
    return false;
  }
  
}

//featured products
public function featuredProducts(){
  //sql query to select user sports products
  $sql = 'SELECT * FROM `products` p JOIN ratings r on p.productID = r.pID WHERE r.rating > :rating';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':rating',3.5);
   //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return featured products
  return $result;

}

public function getCartSingleElement($productId){
  //sql query to select user sports products
  $sql = 'Select * from carts Where product_id = :productId';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':productId',$productId);
   //execute query
  $this->execute();
  
   $result = $this->fetchSingle();
   return $result;
}


public function updateCart($productId,$quantity){
//sql query to select user sports products
$sql = 'Select * from carts Where product_id = :productId';
//prepare query
$this->prepare($sql);
//bind rating 
$this->bind(':productId',$productId);
 //execute query
$this->execute();

$result = $this->fetchSingle();
  
//updated cart details
if($quantity > 0){

  $finalquantity = $quantity;
  $finalprice = $finalquantity * $result->product_price;

  $sql = 'Update carts SET price=:price, quantity =:quantity Where product_id =:productId';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':productId',$result->product_id);
  $this->bind(':price',$finalprice);
  $this->bind(':quantity',$finalquantity);
  //execute query
  $this->execute();
  return $finalquantity;
}

}

public function applyCoupon($couponCode,$cartTotal){
  $sql = 'Select * from coupons Where coupon_code =:coupon_code';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':coupon_code',$couponCode);
  $this->execute();
  $result = $this->fetchSingle();
  $rowCount = $this->rowCount();

  if($rowCount == 0){
    return '<div class="alert alert-danger"><strong>Failed</strong>.Invalid Coupon Code!</div>';
  }else{
    if($result->is_active = 0){
      return '<div class="alert alert-danger"><strong>Failed</strong>.Coupon Code has Expired!</div>';
    }else{
      
        $discountedPrice = $cartTotal *  ($result->discount_percentage/100);
         
        if($discountedPrice > $result->discount_upto){
          $total = $cartTotal - $result->discount_upto;
          return $total;
        }else{
          $total = $cartTotal - $result->discountedPrice;
          return $total;
        }

    }
     
  }


}

public function storePaymentDetails($userId,$paymentId,$amount){
    $date = $this->getDate();
    $sql = 'Insert into orders (user_id,total_amount,payment_id,order_status,created_at,payment_status) values (:user_id,:total_amount,:payment_id,:order_status,:created_at,:payment_status)';
    $this->prepare($sql);
    //bind rating 
    $this->bind(':user_id',$userId);
    $this->bind(':payment_id',$paymentId);
    $this->bind(':total_amount',$amount);
    $this->bind(':order_status','received');
    $this->bind(':created_at',$date);
    $this->bind(':payment_status','Paid');
    $this->execute();

    return $this->lastInsertedId();
  
}

public function storeOrderDetails($userId,$orderId){
    $carts = $this->getCartByUser($userId);  
    $date = $this->getDate(); 

    foreach($carts as $cart){
      $sql = 'Insert into order_details (order_id,user_id,product_id,product_price,product_name,product_image,quantity,created_at) 
              values (:order_id,:user_id,:product_id,:product_price,:product_name,:product_image,:quantity,:created_at)';
      $this->prepare($sql);
      //bind order details 
      $this->bind(':order_id',$orderId);
      $this->bind(':user_id',$userId);
      $this->bind(':product_id',$cart->product_id);
      $this->bind(':product_price',$cart->product_price);
      $this->bind(':product_name',$cart->product_name);
      $this->bind(':product_image',$cart->product_image);
      $this->bind(':quantity',$cart->quantity);
      $this->bind(':created_at',$date);
      $this->execute();
    }

    //Delete Cart Details
      $sql = 'DELETE FROM `carts` WHERE user_id=:user_id';
      $this->prepare($sql);
      //bind user ID
      $this->bind(':user_id',$userId);
      $this->execute();
    
 
}

public function saveContact($name,$email,$subject,$company,$message){
  $date = $this->getDate();

  $sql = 'Insert into contacts (name,email,subject,company,message,created_at) 
  values (:name,:email,:subject,:company,:message,:created_at)';
$this->prepare($sql);
//bind order details 
$this->bind(':name',$name);
$this->bind(':email',$email);
$this->bind(':subject',$subject);
$this->bind(':company',$company);
$this->bind(':message',$message);
$this->bind(':created_at',$date);
$this->execute();
}

// ///////////////////////////////////////////////// ABHIBADAN CODE /////////////////////////////////////

public function uploadProductTable($id,$name,$company,$category,$detail,$price,$stock,$image){
  $sql = "INSERT INTO `products`(`sellerID`,`Pname`,`Pcompany`,`category_ID`,`Pdetails`, `price`,`stock`,`image`) VALUES (:s,:pn,:pc,:c,:pd,:pr,:ps,:im)";
  $this->prepare($sql);
  $this->bind(':s',$id);
  $this->bind(':pn',$name);
  $this->bind(':pc',$company);
  $this->bind(':c',$category);
  $this->bind(':pd',$detail);
  $this->bind(':pr',$price);
  $this->bind(':ps',$stock);
  $this->bind(':im',$image);
  //execute query
  if($this->execute())
  {
    return true;
  }
  else{
    return false;
  }
}
//ABHI
//find lastinsert id,used for updating product image table
  public function findProductID(){
    $sql="SELECT MAX(`productID`) AS PMAX FROM `products`";
    $this->prepare($sql);
    $this->execute();
    $q=$this->fetchSingle();
    $p=(int)$q->PMAX;
    return $p; 
}
//ABHI
public function findcategory($category){
  $sql="SELECT `category_ID` FROM `categories` WHERE `categories`=:c";
    $this->prepare($sql);
    $this->bind(':c',$category);
    $this->execute();
    $q=$this->fetchSingle();
    //$p=(int)$q->category_ID;
    print_r($q);
    return $q;
}
//ABHI
public function fetchCategory(){
    //sql query to select user using email
    $sql = 'SELECT * FROM  categories';
    //prepare query
    $this->prepare($sql);
    //execute query
    $this->execute();
    //fetch single user record
    $result = $this->fetchMultiple();
    //return user details
    return $result;

}
//NEED DATE ABHI
//insert product image
public function uploadImageTable($id,$image){

  $date = $this->getDate();
  //insert product image for given product id
  $sql2="INSERT INTO `product_images`(`productID`, `image`,`created_at`) VALUES (:p,:pim,:created_at)";
  $this->prepare($sql2);
  $this->bind(':p',$id);
  $this->bind(':pim',$image);
  $this->bind(':created_at',$date);
  if($this->execute())
  {
    echo"ok";
  }
}





//ABHI
//seller database
public function sellerData($id){
  // $sql="SELECT p.Pname,p.Pcompany,c.category,p.stock-p.sold AS 'remain' FROM `products` AS p,categories AS c WHERE p.category_ID=c.category_ID AND p.sellerID= :i";
  $sql="Select * From products Where sellerId=:id";
  $this->prepare($sql);
  $this->bind(':id',$id);
  $this->execute();
  $q=$this->fetchMultiple();
  return $q; 
}
//ABHI
//sellect all of user profile for given user id
public function usersearch($userID)
{
  $sql="SELECT * FROM `users` WHERE `id`=:ui";
  $this->prepare($sql);
  $this->bind(':ui',$userID);
  $this->execute();
  $p=$this->fetchSingle();
  return $p;
}
//ABHI
//select all of seller profile for given seller id
public function sellerprofile($id){
  $seller="SELECT * FROM `sellers` WHERE `id`=:i";
  $this->prepare($seller);
  $this->bind(':i',$id);
  $this->execute();
  $q=$this->fetchSingle();
  return $q; 
}
//ABHI
//details of seller bank for given id
public function sellerbankdetails($id){
  $q="SELECT * FROM seller_banks WHERE sellerID=:i";
  $this->prepare($q);
  $this->bind(':i',$id);
  $this->execute();
  $q=$this->fetchSingle();
  return $q; 
}
//ABHI
//details of user bank for given id
public function userbankdetails($id){
  $q="SELECT * FROM user_banks WHERE userID=:i";
  $this->prepare($q);
  $this->bind(':i',$id);
  $this->execute();
  $q=$this->fetchSingle();
  return $q; 
}
//NEED DATE ABHI
//update seller details for given seller id
public function updateSeller($id,$name,$email,$flat,$building,$street,$area,$district,$state,$country,$PIN){
  $sql = "UPDATE `sellers` SET `name`=:na,`email`=:uemail,`flat`=:flt,`building`=:b,`street`=:st,`area`=:ar,`district`=:dis,`state`=:stat,`country`=:con,`PINcode`=:pin WHERE `id`=:i";
  $this->prepare($sql);
  $this->bind(':i',$id);
  $this->bind(':na',$name);
  $this->bind(':uemail',$email);
  $this->bind(':flt',$flat);
  $this->bind(':b',$building);
  $this->bind(':st',$street);
  $this->bind(':ar',$area);
  $this->bind(':dis',$district);
  $this->bind(':stat',$state);
  $this->bind(':con',$country);
  $this->bind(':pin',$PIN);
  //execute query
  if($this->execute())
  {
    return true;
  }
  else{
    return false;
  }
}
//NEED DATE ABHI
//update user details for given user id
public function updateUser($id,$name,$email,$flat,$building,$street,$area,$district,$state,$country,$PIN){
  $sql = "UPDATE `users` SET `name`=:na,`email`=:uemail,`flat`=:flt,`building`=:b,`street`=:st,`area`=:ar,`district`=:dis,`state`=:stat,`country`=:con,`PINcode`=:pin WHERE `id`=:i";
  $this->prepare($sql);
  $this->bind(':i',$id);
  $this->bind(':na',$name);
  $this->bind(':uemail',$email);
  $this->bind(':flt',$flat);
  $this->bind(':b',$building);
  $this->bind(':st',$street);
  $this->bind(':ar',$area);
  $this->bind(':dis',$district);
  $this->bind(':stat',$state);
  $this->bind(':con',$country);
  $this->bind(':pin',$PIN);
  //execute query
  if($this->execute())
  {
    return true;
  }
  else{
    return false;
  }
}
//NEED DATE ABHI
//update seller bank details 
public function updateSellerBank($id,$account_number,$IFCS,$CBIN){
  $q="UPDATE seller_banks SET account_number=:ac,IFCScode=:ifcs,`CBINno`=:cbin WHERE sellerID=:i";
  $this->prepare($q);
  $this->bind(':i',$id);
  $this->bind(':ac',$account_number);
  $this->bind(':ifcs',$IFCS);
  $this->bind(':cbin',$CBIN);
  $this->execute();
}
//NEED DATE ABHI
//update user bank details
public function updateUserBank($id,$account_number,$IFCS,$CBIN){
  $date = $this->getDate();
  $q="UPDATE user_banks SET account_number=:ac,IFCScode=:ifcs,`CBINno`=:cbin , updated_at=:updated_at WHERE userID=:i";
  $this->prepare($q);
  $this->bind(':i',$id);
  $this->bind(':ac',$account_number);
  $this->bind(':ifcs',$IFCS);
  $this->bind(':cbin',$CBIN);
  $this->bind(':updated_at',$date);
  $this->execute();
}
//NEED DATE ABHI UNDER IF
 //register seller bank in profile page if not registered
 public function registerSellerBank($id)
 {
  $date = $this->getDate();
   //check registered or not
   $sql1="SELECT `ID` FROM `seller_banks` WHERE `sellerID`=:id";
   $this->prepare($sql1);
   $this->bind(':id',$id);
   $this->execute();
   $q=$this->fetchSingle();
   if(empty($q))
   {
     //register if not registered
     $sql="INSERT INTO `seller_banks`(`sellerID`,created_at) VALUES (:s,:created_at)";
   $this->prepare($sql);
   $this->bind(':s',$id);
   $this->bind(':created_at',$date);
   $this->execute();
   }
   
 }

//NEED DATE ABHI UNDER IF
 //register user bank in profile page if not registered
 public function registerUserBank($id)
 {
  $date = $this->getDate();
   //check registered or not
   $sql1="SELECT `ID` FROM `user_banks` WHERE `userID`=:id";
   $this->prepare($sql1);
   $this->bind(':id',$id);
   $this->execute();
   $q=$this->fetchSingle();
   if(empty($q))
   {
     //register if not registered
      $sql="INSERT INTO `user_banks`(`userID`,created_at) VALUES (:s,:created_at)";
      $this->prepare($sql);
      $this->bind(':s',$id);
      $this->bind(':created_at',$id);
      $this->execute();
   }
   
 }
 //ABHI
 //fetch user order list
 public function userOrder($id,$orderid)
 {
   $sql="SELECT orders.id,order_details.product_name,order_details.product_price,order_details.quantity FROM order_details, orders WHERE orders.id=order_details.order_id AND orders.id=:o AND orders.user_id=:i";
   $this->prepare($sql);
   $this->bind(':i',$id);
   $this->bind(':o',$orderid);
   $this->execute();
   $p=$this->fetchMultiple();
   return $p;
 }
 //ABHI
 //count order with same id
 public function countOrder($id)
 {
   $sql="SELECT `id`,`total_amount`,`order_status` FROM `orders` WHERE `user_id`=:i";
   $this->prepare($sql);
   $this->bind(':i',$id);
   $this->execute();
   $q=$this->fetchMultiple();
   return($q);
 }

 


}


?>