<nav class="navbar navbar-expand-lg" style="background-color: #2f3640;">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php" style="color: white;"><?php echo lang('HOME_ADMIN'); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="categories.php" style="color: white;"><?php echo lang('CATEGORIES'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php" style="color: white;"><?php echo lang('ITEMS'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php?do=Manage" style="color: white;"><?php echo lang('MEMBERS'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php" style="color: white;"><?php echo lang('COMMENTS'); ?></a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
            Zouhair
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item"  href="../index.php">Visit Shop</a></li>
            <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
            <li><a class="dropdown-item" href="Logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
