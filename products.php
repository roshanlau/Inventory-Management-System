<?php
    require_once 'auth.php';
    if (!Auth::is_authed()) {
    $authError = "You are not authorized to view this page. Please log in first. ";
    header("Location: login.php?redirect=products.php&error=$authError");
    }

    include_once 'products_crud.php';
    include_once 'env.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>My Software Ordering System : Products</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap.min.css" />
    <link href="https://cdn.datatables.net/v/bs/jszip-3.10.1/b-2.4.2/b-html5-2.4.2/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap.min.css">
    <style>
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: left;
        }

        .rating input {
            display: none;
        }

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
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="alert alert-danger" role="alert">
                        <p>
                        <?= $authError ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (Auth::is_level([Auth::SUPERVISOR, Auth::ADMIN])) { ?>
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="page-header">
                        <h2>Create New Product</h2>
                    </div>
                <form action="products.php" method="post" class="form-horizontal">
                    
                        <div class="form-group">
                            <label for="productid" class="col-sm-3 control-label ">ID</label>
                            <div class="col-sm-9">
                                <input name="pid" type="text" class="form-control" id="productid" placeholder="Product ID" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_id']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="productname" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input name="name" type="text" class="form-control" id="productname" placeholder="Product Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="productprice" class="col-sm-3 control-label">Price</label>
                            <div class="col-sm-9">
                                <input name="price" type="number" step="0.01" min="0" class="form-control" id="productprice" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_price']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="producttype" class="col-sm-3 control-label">Type</label>
                            <div class="col-sm-9">
                                <select name="type" class="form-control" id="producttype" required>
                                    <option value="">Please select</option>
                                    <option value="Tax Software" <?php if(isset($_GET['edit'])) if($editrow['fld_product_type']=="Tax Software") echo "selected"; ?>>Tax Software</option>
                                    <option value="Operating System" <?php if(isset($_GET['edit'])) if($editrow['fld_product_type']=="Operating System") echo "selected"; ?>>Operating System</option>
                                    <option value="Antivirus Software" <?php if(isset($_GET['edit'])) if($editrow['fld_product_type']=="Antivirus Software") echo "selected"; ?>>Antivirus Software</option>
                                    <option value="File Management, Encryption & Compression Software" <?php if(isset($_GET['edit'])) if($editrow['fld_product_type']=="File Management, Encryption & Compression Software") echo "selected"; ?>>File Management, Encryption & Compression Software</option>
                                    <option value="Editing Software" <?php if(isset($_GET['edit'])) if($editrow['fld_product_type']=="Editing Software") echo "selected"; ?>>Editing Software</option>
                                    <option value="Speech Recognition Software" <?php if(isset($_GET['edit'])) if($editrow['fld_product_type']=="Speech Recognition Software") echo "selected"; ?>>Editing Software</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label label for="productbrand" class="col-sm-3 control-label">Brand</label>
                            <div class="col-sm-9">
                                <select name="brand" class="form-control" id="productbrand" required>
                                    <option value="">Please select</option>
                                    <option value="Adobe" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Adobe") echo "selected"; ?>>Adobe</option>
                                    <option value="Avast" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Avast") echo "selected"; ?>>Avast</option>
                                    <option value="AVG" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="AVG") echo "selected"; ?>>AVG</option>
                                    <option value="Corel" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Corel") echo "selected"; ?>>Corel</option>
                                    <option value="Graphixly" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Graphixly") echo "selected"; ?>>Graphixly</option>
                                    <option value="H&R Block" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="H&R Block") echo "selected"; ?>>H&R Block</option>
                                    <option value="Intuit" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Intuit") echo "selected"; ?>>Intuit</option>
                                    <option value="Laplink Software" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Laplink Software") echo "selected"; ?>>Laplink Software</option>
                                    <option value="Magix" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Magix") echo "selected"; ?>>Magix</option>
                                    <option value="Microsoft" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Microsoft") echo "selected"; ?>>Microsoft</option>
                                    <option value="NortonLifeLock" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="NortonLifeLock") echo "selected"; ?>>NortonLifeLock</option>
                                    <option value="McAfee" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="McAfee") echo "selected"; ?>>McAfee</option>
                                    <option value="Malwarebytes" <?php if(isset($_GET['edit'])) if($editrow['fld_product_brand']=="Malwarebytes") echo "selected"; ?>>Malwarebytes</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="productrating" class="col-sm-3 control-label">Rating</label>
                            <div class="col-sm-9">
                                <div class="rating">
                                    <input type="radio" id="star5" name="rating" value="5" <?php if(isset($_GET['edit'])) if($editrow['fld_product_rating']==5) echo "checked"; ?>><label for="star5"> </label>
                                    <input type="radio" id="star4" name="rating" value="4" <?php if(isset($_GET['edit'])) if($editrow['fld_product_rating']==4) echo "checked"; ?>><label for="star4"> </label>
                                    <input type="radio" id="star3" name="rating" value="3" <?php if(isset($_GET['edit'])) if($editrow['fld_product_rating']==3) echo "checked"; ?>><label for="star3"> </label>
                                    <input type="radio" id="star2" name="rating" value="2" <?php if(isset($_GET['edit'])) if($editrow['fld_product_rating']==2) echo "checked"; ?>><label for="star2"> </label>
                                    <input type="radio" id="star1" name="rating" value="1" <?php if(isset($_GET['edit'])) if($editrow['fld_product_rating']==1) echo "checked"; ?> required><label for="star1"> </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="productdescription" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" cols="40" rows="5" class="form-control" id="productdescription" required><?php if(isset($_GET['edit'])) echo $editrow['fld_product_description']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <?php if (isset($_GET['edit'])) { ?>
                                <input type="hidden" name="oldpid" value="<?php echo $editrow['fld_product_id']; ?>">
                                <button class="btn btn-default" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
                                <?php } else { ?>
                                <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
                                <?php } ?>
                                <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
                            </div>
                        </div>
                    </div>                         
                </form>
            </div>
        
    <?php } ?>

    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="page-header">
            <h2>Products List</h2>
        </div>
        <table class="table table-striped table-bordered" id="product-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Brand</th>
                    <th>Rating</th>
                    <th></th>
                </tr>
                </thead>
                    <tbody>
                        <?php
                            // Read
                            
                            try {
                                $conn = new PDO("mysql:host=" . Env::$servername . ";port=". Env::$port . ";dbname=" . Env::$dbname, Env::$username, Env::$password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $conn->prepare("SELECT * FROM tbl_products");
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                            
                            foreach($result as $readrow) {
                        ?>   
                            <tr>
                                <td><?php echo $readrow['fld_product_id']; ?></td>
                                <td><?php echo $readrow['fld_product_name']; ?></td>
                                <td><?php echo $readrow['fld_product_price']; ?></td>
                                <td><?php echo $readrow['fld_product_type']; ?></td>
                                <td><?php echo $readrow['fld_product_brand']; ?></td>
                                <td><?php echo $readrow['fld_product_rating']; ?></td>
                                <td>
                                    <a href="products_details.php?pid=<?php echo $readrow['fld_product_id']; ?>" 
                                    data-id="<?php echo $readrow['fld_product_id']; ?>" 
                                    class="btn btn-warning btn-xs product-details-btn" role="button">Details</a>
                                    <?php if (Auth::is_level([Auth::SUPERVISOR, Auth::ADMIN])) { ?>
                                        <a href="products.php?edit=<?php echo $readrow['fld_product_id']; ?>" class="btn btn-success btn-xs" role="button">Edit</a>
                                        <a href="products.php?delete=<?php echo $readrow['fld_product_id']; ?>" onclick="return confirm('Are you sure to delete?');" 
                                        class="btn btn-danger btn-xs" role="button">Delete</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                            }
                            $conn = null;

                            }
                            catch(PDOException $e){
                                    echo "Error: " . $e->getMessage();
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade modal-open" tabindex="-1" role="dialog" id="details-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Product Details</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->        

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs/jszip-3.10.1/b-2.4.2/b-html5-2.4.2/datatables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.product-details-btn').click(function (e) {
                e.preventDefault();
                var pid = $(this).data('id');
                $('.modal-body').load(`products_details.php?pid=${pid} #target`, null, () => {
                $('#details-modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                })
            });
            const table = $('#product-table').DataTable({
                dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-2'i><'col-sm-7'p><'col-sm-3'B>>",
                buttons: [
                {
                    extend: 'excel',
                    text: 'Export to Excel'
                },
                ],
                order: [[1, 'asc']],
                columns: [
                null,
                null,
                { searchable: false },
                null,
                null,
                null,
                { searchable: false },
                ],
                lengthMenu: [[5, 10, 20, 30, -1], [5, 10, 20, 30, 'All']],

                });
                table.buttons().container().children().addClass('btn btn-default')
            
            //show modal of product details
            
        });
        $(document).keyup(function(e) {
            if (e.key === "Escape") { // escape key maps to keycode `27`
                $('.modal').modal('hide'); // replace '.modal' with your modal's class or id
            }
        });
        
    </script>

    <style>
        .dt-buttons {
        margin-top: 0.5rem;
        text-align: right !important;
        }

        div.dataTables_paginate {
        text-align: center !important;
        }
    </style>
    
    

</body>
</html>