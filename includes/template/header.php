<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php getTitle(); ?> </title>
   
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
  

 
    <link rel="stylesheet" href="<?php echo $css; ?>fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" /> 

    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" /> 
    
    <link rel="stylesheet" href="<?php echo $css; ?>front.css" />

 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <script src="<?php echo $js; ?>bootstrap.bundle.min.js"></script>
    <script src="<?php echo $js; ?>backend.js"></script>
</head>
<body>

<div class="upper-bar bg-light py-2">
  <div class="container d-flex justify-content-between">
    <div class="welcome">
      <?php 
        if (isset($_SESSION['user'])){ ?>
      <?php

$userID = $_SESSION['uid']; 

$userimage = getAllFrom('avatar', 'users', "WHERE UserID = $userID", '', 'UserID');

foreach ($userimage as $user) {
    if (!empty($user['avatar'])) {
        echo "<img class='my-image img-thumbnail img-circle' src='admin/uploads/avatars/" . $user['avatar'] . "' alt='User Avatar' />";
    } else {
        echo "<div class='no-image'>No Image</div>";
    }
}

?>
       <div class="btn-group my-info">
     
        <span class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown">
          <?php echo $sessionUser ?>
          <span class="caret"></span>
          </span>
          <ul class="dropdown-menu">
            
             <li> <a href="profile.php"> My Profile </a> </li>
            <li> <a href="newad.php">New Item</a> </li>
              <li> <a href="profile.php#my-ads"> My Items </a> </li>
            <li> <a href="logout.php">  Logout </a> </li>
          </ul>
        
       </div>     
   
        <?php
      
  
            }  
      
      ?>
    </div>

    <div class="auth">
      <?php 
        if (!isset($_SESSION['user'])){
      ?>
        <a href="login.php"><span class="float-end">Login/Signup</span></a>
      <?php } ?>
    </div>
  </div>
</div>

  </div>
</div>
<nav class="navbar navbar-expand-lg" style="background-color: #2f3640;">
  <div class="container">
    <a class="navbar-brand" href="index.php"  style="color: #ccc;">Homepage</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php 
        $allCats = getAllFrom("*", "categories","where parent = 0", "", "ID" ,"ASC" );
        
        foreach($allCats as $cat): ?>
          <li class="nav-item"  ">
      <?php 
$pagename = str_replace(' ', '-', $cat['Name']); 
    ?>
<a class="nav-link" href="categories.php?pageid=<?= $cat['ID']; ?>" style="color: #ccc;">
  <?= $cat['Name']; ?></a>

  
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    
  </div>
</nav>
 