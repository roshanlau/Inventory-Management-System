<?php
 
include_once 'database.php';
require_once 'env.php';
require_once 'auth.php';
 
$conn = new PDO("mysql:host=" . Env::$servername . ";dbname=" . Env::$dbname, Env::$username, Env::$password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
  if (!Auth::is_level([Auth::ADMIN])) {
    $authError = "You are not authorized to perform this action";
  } else {
    try {
  
      $stmt = $conn->prepare("INSERT INTO tbl_staffs_a189629_pt2(fld_staff_num, fld_staff_fname, fld_staff_lname,
        fld_staff_gender, fld_staff_phone, fld_staff_email, fld_password, fld_access_level) VALUES(:sid, :fname, :lname, :gender,
        :phone, :email, :passwordHash, :accessLevel)");
    
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':passwordHash', $passwordHash, PDO::PARAM_STR);
      $stmt->bindParam(':accessLevel', $accessLevel, PDO::PARAM_STR);
        
      $sid = $_POST['sid'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender =  $_POST['gender'];
      $phone = $_POST['phone'];
      $email = $_POST['email'];
      $passwordHash = Auth::hash($_POST['password']);               //need to hash later
      $accessLevel = $_POST['access_level'];
          
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
    $authError = "You are not authorized to perform this action";
  } else {
    try {
  
      $stmt = $conn->prepare("UPDATE tbl_staffs_a189629_pt2 SET
        fld_staff_num = :sid, fld_staff_fname = :fname,
        fld_staff_lname = :lname, fld_staff_gender = :gender,
        fld_staff_phone = :phone, fld_staff_email = :email,
        fld_password = IFNULL(:passwordHash, fld_password),
        fld_access_level = :accessLevel
        WHERE fld_staff_num = :oldsid");
    
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':oldsid', $oldsid, PDO::PARAM_STR);
      $stmt->bindParam(':passwordHash', $passwordHash, PDO::PARAM_STR);
      $stmt->bindParam(':accessLevel', $accessLevel, PDO::PARAM_STR);
        
      $sid = $_POST['sid'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender = $_POST['gender'];
      $phone = $_POST['phone'];
      $email = $_POST['email'];
      $oldsid = $_POST['oldsid'];
      $passwordHash = isset($_POST['password']) && strlen($_POST['password']) != 0 ? Auth::hash($_POST['password']) : null;
      $accessLevel = $_POST['access_level'];
          
      $stmt->execute();
  
      header("Location: staffs.php");
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
}
 
//Delete
if (isset($_GET['delete'])) {
  if (!Auth::is_level([Auth::ADMIN])) {
    $authError = "You are not authorized to perform this action";
  } else {
    try {
  
      $stmt = $conn->prepare("DELETE FROM tbl_staffs_a189629_pt2 where fld_staff_num = :sid");
    
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        
      $sid = $_GET['delete'];
      
      $stmt->execute();
  
      header("Location: staffs.php");
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
    $authError = "You are not authorized to perform this action";
  } else { 
    try {
  
      $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a189629_pt2 where fld_staff_num = :sid");
    
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        
      $sid = $_GET['edit'];
      
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
