<?php
 
 include_once 'database.php';
 require_once 'env.php';
  
 $conn = new PDO("mysql:host=" . Env::$servername . ";port=". Env::$port . ";dbname=" . Env::$dbname, Env::$username, Env::$password);
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
 //Create
 if (isset($_POST['create'])) {
  
   try {
  
     $stmt = $conn->prepare("INSERT INTO tbl_orders(fld_order_num, fld_staff_num,
       fld_customer_num) VALUES(:oid, :sid, :cid)");
    
     $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
     $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
     $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
        
     $oid = uniqid('O', true);
     $sid = $_POST['sid'];
     $cid = $_POST['cid'];
      
     $stmt->execute();
     }
  
   catch(PDOException $e)
   {
       echo "Error: " . $e->getMessage();
   }
 }
  
 //Update
 if (isset($_POST['update'])) {
  if (!Auth::is_level([Auth::ADMIN, Auth::SUPERVISOR])) {
    $authError = "You are not authorized to update orders";
  } else { 
    try {
    
      $stmt = $conn->prepare("UPDATE tbl_orders SET fld_staff_num = :sid,
        fld_customer_num = :cid WHERE fld_order_num = :oid");
      
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
          
      $oid = $_POST['oid'];
      $sid = $_POST['sid'];
      $cid = $_POST['cid'];
        
      $stmt->execute();
    
      header("Location: orders.php");
      }
    
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
}
  
 //Delete
  if (isset($_GET['delete'])) {
    if (!Auth::is_level([Auth::ADMIN, Auth::SUPERVISOR])) {
      $authError = "You are not authorized to delete orders";
    } else {
    try {
    
      $stmt = $conn->prepare("DELETE FROM tbl_orders WHERE fld_order_num = :oid");
      
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
          
      $oid = $_GET['delete'];
        
      $stmt->execute();
    
      header("Location: orders.php");
      }
    
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
    }
  }
  
 //Edit
  if (isset($_GET['edit'])) {
    if (!Auth::is_level([Auth::ADMIN, Auth::SUPERVISOR])) {
      $authError = "You are not authorized to edit orders";
    } else {
      try {
    
      $stmt = $conn->prepare("SELECT * FROM tbl_orders WHERE fld_order_num = :oid");
      
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
          
      $oid = $_GET['edit'];
        
      $stmt->execute();
    
      $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
      }
    
      catch(PDOException $e)
      {
          echo "Error: " . $e->getMessage();
      }
    }
  }
  
   $conn = null;
 ?>