<?php
 
include_once 'database.php';
require_once('auth.php');
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
    if (!Auth::is_level([Auth::ADMIN, Auth::SUPERVISOR])) {
        $authError = "You are not authorized to create products";
    } else {
        try {
    
            $stmt = $conn->prepare("INSERT INTO tbl_products_a189629_pt2(fld_product_id,
                fld_product_name, fld_product_price, fld_product_type, fld_product_brand,
                fld_product_rating, fld_product_description, fld_product_image) VALUES(:pid, :name, :price, :type, :brand, :rating, :description, '')");    
        
            $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_INT);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        
            $pid = $_POST['pid'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $type = $_POST['type'];
            $brand =  $_POST['brand'];
            $rating = $_POST['rating'];
            $description = $_POST['description'];
        
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
        $authError = "You are not authorized to update products";
    } else {
        try {
    
            $stmt = $conn->prepare("UPDATE tbl_products_a189629_pt2 SET fld_product_id = :pid,
                fld_product_name = :name, fld_product_price = :price, fld_product_type = :type, 
                fld_product_brand = :brand, fld_product_rating = :rating, 
                fld_product_description = :description
                WHERE fld_product_id = :oldpid");
        
            $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':oldpid', $oldpid, PDO::PARAM_STR);
            
            $pid = $_POST['pid'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $type = $_POST['type'];
            $brand =  $_POST['brand'];
            $rating = $_POST['rating'];
            $description = $_POST['description'];
            $oldpid = $_POST['oldpid'];
            
            $stmt->execute();
    
            header("Location: products.php");
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
        $authError = "You are not authorized to delete products";
    } else {
        try {
        
            $stmt = $conn->prepare("DELETE FROM tbl_products_a189629_pt2 WHERE fld_product_id = :pid");
            
            $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
            
            $pid = $_GET['delete'];
            
            $stmt->execute();
        
            header("Location: products.php");
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
        $authError = "You are not authorized to edit products";
    } else {
        try {
        
            $stmt = $conn->prepare("SELECT * FROM tbl_products_a189629_pt2 WHERE fld_product_id = :pid");
            
            $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
            
            $pid = $_GET['edit'];
            
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