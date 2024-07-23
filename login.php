<?php
include_once 'auth.php';

if (Auth::is_authed()) {
  header("Location: index.php");
}

$id = isset($_POST['id']) ? $_POST['id'] : "";

if (isset($_POST['id']) && isset($_POST['password'])) {
  Auth::login($_POST['id'], $_POST['password']);
  if (Auth::is_authed()) {

    if (isset($_POST['redirect']) && strlen($_POST['redirect']) > 0) {
      header("Location: " . $_POST['redirect']);
    } else {
      header("Location: index.php");
    }
  }
}

global $authError;

if (isset($_GET['error'])) {
  $authError = $_GET['error'];
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
  <style type="text/css">
  </style>
</head>

<body>
  <?php include_once 'nav_bar.php'; ?>
  <div class="container-fluid">
    <div class="row" style="margin-top:24rem">
      <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 ">
        <h1>Staff Login</h1>
        <form action="login.php" method="post">
          <?php
          if (isset($authError)) {
            ?>
            <div class="alert alert-danger" role="alert">
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
              <span class="sr-only">Error:</span>
              <?= $authError ?>
            </div>
            <?php
          }
          ?>
          <input type="text" hidden name="redirect" value="<?= (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) ? $_GET['redirect'] : "" ?>" />
          <div class="form-group">
            <label for="id-input">Staff ID</label>
            <input type="text" class="form-control" id="id-input" name="id" placeholder="Staff ID" value="<?= $id ?>">
          </div>
          <div class="form-group">
            <label for="password-input">Password</label>
            <input type="password" class="form-control" id="password-input" name="password" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap.min.js"></script>
</body>

</html>