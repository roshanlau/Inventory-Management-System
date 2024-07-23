<?php require_once 'auth.php'; ?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Macrosoft</a>
      
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <?php if (Auth::is_authed()) { ?>
            <span class="navbar-text">Welcome,
              <?= Auth::get_full_name() ?>
            </span>
          <?php } else { ?>
            <a href="login.php">Login</a>
          <?php } ?>
        </li>
        <?php if (Auth::is_authed()) { ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
              aria-expanded="false">Menu <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="products.php">Products</a></li>
              <li><a href="customers.php">Customers</a></li>
              <li class="<?= !Auth::is_level([Auth::SUPERVISOR, Auth::ADMIN]) ? 'disabled' : '' ?>"><a
                  href="staffs.php">Staffs</a></li>
              <li><a href="orders.php">Orders</a></li>
            </ul>
          </li>
        <?php } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>