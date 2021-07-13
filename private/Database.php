 <?php 

class Database{
    //Set database parameters
    private $dbname = "college_ecom";
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
    $password = password_hash($password,PASSWORD_BCRYPT);
     $sql = 'INSERT INTO `users`(`name`, `email`, `aadhaar`, `password`, `is_active`) VALUES (:name,:email,:aadhaar,:password,:is_active)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':aadhaar',$aadhaar);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':is_active',$is_active);

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
    $sql = 'Insert into sellers (name,email,password,gst,pan) values (:name,:email,:password,:gst,:pan)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':gst',$gst);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':pan',$pan);

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
public function uploadProductTable($id,$name,$company,$category,$detail,$price,$stock,$image){
  $sql = "INSERT INTO `product`(`sellerID`,`Pname`,`Pcompany`,`category_ID`,`Pdetails`, `price`,`stock`,`image`) VALUES (:s,:pn,:pc,:c,:pd,:pr,:ps,:im)";
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
  public function findProductID(){
    $sql="SELECT MAX(`productID`) AS PMAX FROM `product`";
    $this->prepare($sql);
    $this->execute();
    $q=$this->fetchSingle();
    $p=(int)$q->PMAX;
    return $p; 
}
public function findcategory($category){
  $sql="SELECT `category_ID` FROM `category_table` WHERE `category`=:c";
    $this->prepare($sql);
    $this->bind(':c',$category);
    $this->execute();
    $q=$this->fetchSingle();
    $p=(int)$q->category_ID;
    return $p;
}
public function fetchCategory(){
    //sql query to select user using email
    $sql = 'SELECT * FROM  category_table';
    //prepare query
    $this->prepare($sql);
    //execute query
    $this->execute();
    //fetch single user record
    $result = $this->fetchMultiple();
    //return user details
    return $result;

}
public function uploadImageTable($id,$image){
  $sql2="INSERT INTO `product_image`(`productID`, `image`) VALUES (:p,:pim)";
  $this->prepare($sql2);
  $this->bind(':p',$id);
  $this->bind(':pim',$image);
  $this->execute();
}

   



   

}
?>