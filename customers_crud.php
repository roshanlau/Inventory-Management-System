<?php
 
include_once 'database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
  if (!Auth::is_level([Auth::ADMIN, Auth::SUPERVISOR])) {
    $authError = "You are not authorized to create customer records";
  } else {
    try {
  
      $stmt = $conn->prepare("INSERT INTO tbl_customers_a189629_pt2(fld_customer_num, fld_customer_fname,
        fld_customer_lname, fld_customer_gender, fld_customer_phone) VALUES(:cid, :fname, :lname,
        :gender, :phone)");
    
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        
      $cid = $_POST['cid'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender =  $_POST['gender'];
      $phone = $_POST['phone'];
        
      $stmt->execute();
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
}
 
//Update
if (isset($_POST['update'])) {
  if (!Auth::is_level([Auth::ADMIN, Auth::SUPERVISOR])) {
    $authError = "You are not authorized to update customer records";
  } else { 
    try {
  
      $stmt = $conn->prepare("UPDATE tbl_customers_a189629_pt2 SET fld_customer_num = :cid,
        fld_customer_fname = :fname, fld_customer_lname = :lname,
        fld_customer_gender = :gender, fld_customer_phone = :phone
        WHERE fld_customer_num = :oldcid");
    
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':oldcid', $oldcid, PDO::PARAM_STR);
        
      $cid = $_POST['cid'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender =  $_POST['gender'];
      $phone = $_POST['phone'];
      $oldcid = $_POST['oldcid'];
        
      $stmt->execute();
  
      header("Location: customers.php");
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
    $authError = "You are not authorized to delete customer records";
  } else {
    try {
  
      $stmt = $conn->prepare("DELETE FROM tbl_customers_a189629_pt2 WHERE fld_customer_num = :cid");
    
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
        
      $cid = $_GET['delete'];
      
      $stmt->execute();
  
      header("Location: customers.php");
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
    $authError = "You are not authorized to edit customer records";
  } else {
    try {
  
      $stmt = $conn->prepare("SELECT * FROM tbl_customers_a189629_pt2 WHERE fld_customer_num = :cid");
    
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
        
      $cid = $_GET['edit'];
      
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