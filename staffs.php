<?php
    include_once 'staffs_crud.php';
    require_once 'env.php';
    require_once 'auth.php';
    if (!Auth::is_authed()) {
        $authError = "You are not authorized to view this page. Please log in first. ";
        header("Location: login.php?redirect=staffs.php&error=$authError");
    }
    if (!Auth::is_level([Auth::ADMIN, Auth::SUPERVISOR])) {
        $authError = "You are not authorized to view staffs";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>My Software Ordering System : Staffs</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .rating label {
            cursor: pointer;
            width: 40px;
            height: 40px;
            background-image: url('star.png'); 
            background-size: cover;
        }

        .rating input:checked ~ label {
            background-image: url('star-filled.png'); 
        }
    </style>
</head>
<body>

    <?php include_once 'nav_bar.php'; ?>

    <div class="container-fluid">
        <?php if (isset($authError)) { ?>
            <div class="alert alert-danger" role="alert">
                <p>
                <?= $authError ?>
                </p>
            </div>
        <?php } else { ?>
            <?php if (Auth::is_level([Auth::SUPERVISOR]) && isset($_GET['edit']) || Auth::is_level([Auth::ADMIN])) { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <div class="page-header">
                            <h2>Create New Staffs</h2>
                        </div>    
                        <form action="staffs.php" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label for="staffid" class="col-sm-3 control-label ">Staff ID</label>
                                <div class="col-sm-9">
                                    <input name="sid" type="text" class="form-control" id="staffid" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_num']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fname" class="col-sm-3 control-label ">First Name</label>
                                <div class="col-sm-9">
                                    <input name="fname" type="text" class="form-control" id="fname" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_fname']; ?>" required>
                                </div>
                            </div>    
                            <div class="form-group">
                                <label for="lname" class="col-sm-3 control-label ">Last Name</label>
                                <div class="col-sm-9">
                                    <input name="lname" type="text" class="form-control" id="lname" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_lname']; ?>" required>
                                </div>
                            </div>                 
                            <div class="form-group">
                                <label for="gender" class="col-sm-3 control-label">Gender</label>
                                <div class="col-sm-9">
                                    <div class="radio">
                                        <label for="Male">
                                            <input name="gender" type="radio" value="Male" id="Male" <?php if(isset($_GET['edit'])) if($editrow['fld_staff_gender']=="Male") echo "checked"; ?> required> Male
                                        </label>
                                    </div>
                                    <div class="radio">
                                    <label for="Female">
                                        <input name="gender" type="radio" value="Female" id="Female" <?php if(isset($_GET['edit'])) if($editrow['fld_staff_gender']=="Female") echo "checked"; ?>> Female
                                    </label>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="phonenum" class="col-sm-3 control-label ">Phone Number</label>
                                <div class="col-sm-9">
                                    <input name="phone" type="text" class="form-control" id="phonenum" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_phone']; ?>" required>
                                </div>
                            </div>      
                            <div class="form-group">
                                <label for="emailaddr" class="col-sm-3 control-label ">Email Address</label>
                                <div class="col-sm-9">
                                    <input name="email" type="text" class="form-control" id="emailaddr" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_staff_email']; ?>" required>
                                </div>
                            </div>    

                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-9">
                                <input name="password" type="password" class="form-control" id="password" placeholder="Password" value="">
                                <br>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="access_level" class="col-sm-3 control-label">Access Level</label>
                                <div class="col-sm-9">
                                <select name="access_level" class="form-control">
                                    <option option value="" disabled selected>Please select</option>
                                    <?php foreach (Auth::getAccessLevelsMap() as $accessLevel => $accessLevelString): ?>
                                    <option value="<?= $accessLevel ?>" <?php if (isset($_GET['edit']) && $editrow['fld_access_level'] == $accessLevel)
                                        echo "selected"; ?>>
                                        <?= $accessLevelString ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">   
                                <?php if (isset($_GET['edit'])) { ?>
                                    <input type="hidden" name="oldcid" value="<?php echo $editrow['fld_staff_num']; ?>" >
                                    <button class="btn btn-default" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
                                    <?php } else { ?>
                                    <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
                                    <?php } ?>
                                    <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h2>Staffs List</h2>
                </div>              
                <table class="table table-striped table-bordered">                        
                <tr>
                    <th>Staff ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Phone Number</th>
                    <th>Email Address</th>
                    <th></th>
                </tr> 
                <?php
                    // Read
                    $per_page = 5;
                    if (isset($_GET["page"]))
                        $page = $_GET["page"];
                    else
                        $page = 1;
                    $start_from = ($page-1) * $per_page;
                    try {
                        $conn = new PDO("mysql:host=" . Env::$servername . ";port=". Env::$port . ";dbname=" . Env::$dbname, Env::$username, Env::$password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt = $conn->prepare("SELECT * FROM tbl_staffs LIMIT $start_from, $per_page");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                    }
                    catch(PDOException $e){
                            echo "Error: " . $e->getMessage();
                    }
                        foreach($result as $readrow) {
                    ?>                              
                    <tr>
                        <td><?php echo $readrow['fld_staff_num']; ?></td>
                        <td><?php echo $readrow['fld_staff_fname']; ?></td>
                        <td><?php echo $readrow['fld_staff_lname']; ?></td>
                        <td><?php echo $readrow['fld_staff_gender']; ?></td>
                        <td><?php echo $readrow['fld_staff_phone']; ?></td>
                        <td><?php echo $readrow['fld_staff_email']; ?></td>
                        <td>
                        <a href="staffs.php?edit=<?php echo $readrow['fld_staff_num']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
                        <a href="staffs.php?delete=<?php echo $readrow['fld_staff_num']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
                        </td>
                    </tr>
                    <?php
                    }
                    $conn = null;
                    ?>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <nav>
                    <ul class="pagination">
                    <?php
                    try {
                        $conn = new PDO("mysql:host=" . Env::$servername . ";port=". Env::$port . ";dbname=" . Env::$dbname, Env::$username, Env::$password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt = $conn->prepare("SELECT * FROM tbl_staffs");
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        $total_records = count($result);
                    }
                    catch(PDOException $e){
                            echo "Error: " . $e->getMessage();
                    }
                    $total_pages = ceil($total_records / $per_page);
                    ?>
                    <?php if ($page==1) { ?>
                        <li class="disabled"><span aria-hidden="true">«</span></li>
                    <?php } else { ?>
                        <li><a href="staffs.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                    <?php
                    }
                    for ($i=1; $i<=$total_pages; $i++)
                        if ($i == $page)
                        echo "<li class=\"active\"><a href=\"staffs.php?page=$i\">$i</a></li>";
                        else
                        echo "<li><a href=\"staffs.php?page=$i\">$i</a></li>";
                    ?>
                    <?php if ($page==$total_pages) { ?>
                        <li class="disabled"><span aria-hidden="true">»</span></li>
                    <?php } else { ?>
                        <li><a href="staffs.php?page=<?php echo $page+1 ?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
                    <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>  
    <?php } ?>              



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>