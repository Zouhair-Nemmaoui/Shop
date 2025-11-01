<?php 
    
  session_start();

  $pageTitle = 'Profile';

  include 'init.php';

  if(isset($_SESSION['user'])){
  
    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");

    $getUser->execute(array($sessionUser));

    $info = $getUser->fetch();

    $userid = $info['UserID'];
    


?>

  <h1 class="text-center">My Profile</h1>
  <div class="information block">
  <div class="container">
    <div class="card border-primary mb-3">
      <div class="card-header bg-primary text-white">
        My Information
      </div>
      <div class="card-body">
        <ul class="list-unstyled"> 
        <li class="card-text">
            <i class="fa fa-unlock-alt fa-fw"></i>
            <span>Login Name</span> : <?php echo $info['Username']?> 
        </li>
        <li class="card-text">
            <i class="far fa-envelope fa-fw"></i>
            <span>  Email </span>: <?php echo $info['Email']?>
        </li>
        <li class="card-text">
           <i class="fa fa-user fa-fw"></i>
            <span>  Full Name</span> : <?php echo $info['FullName']?>  
          </li>       
        <li class="card-text">
            <i class="fa fa-calendar fa-fw"></i>
            <span> Register Date </span> : <?php echo $info['Date']?>  
          </li>
        <li class="card-text">
             <i class="fa fa-tags fa-fw"></i>
            <span>Favorite Category </span>  : 
  </li>
          </ul>
         <a href="#" class="btn btn-default">Edit Information</a> 
      </div>
    </div>
  </div>
</div>
    <div id="my-ads" class="my-ads block">
  <div class="container">
    <div class="card border-primary mb-3">
      <div class="card-header bg-primary text-white">
        My Items
      </div>
      <div class="card-body">
        <p class="card-text">   
   <?php 
       $myItems = getAllFrom("*" ,"items", "where Member_ID = $userid", "" , "Item_ID");

       if (! empty($myItems)){ 
         echo '<div class="row">';     
        foreach($myItems as $item ) {
        echo '<div class = "col-sm-6 col-md-3">';
         echo '<div class = "thumbnail item-box">';
        if($item['Approve'] == 0){ echo '<span class="approve-status">Waiting Approval </span>';}
         echo '<span class = "price-tag">$' . $item['Price'] .'</span>';
        if (!empty($item['item'])) {
        echo "<img class='img-responsive' src='admin/uploads/items/" . $item['item'] . "' alt='" . $item['Name'] . "' />";
    }  else {
        echo '<div class="no-image">No Image</div>';
    }

             echo '<div class = "caption">';
                  echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                 echo '<p>' . $item['Description']  .  '</p>'; 
                 echo '<div class = "date">' . $item['Add_Date']  .  '</div>';         
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    }else {
      echo 'Sorry There\' No Ads To Show, Create <a href="newad.php">New Ad </a>';
    }
    
    ?>
 </p>
</div>
</div>
</div>

  <div class="my-comment block">
  <div class="container">
    <div class="card border-primary mb-3">
      <div class="card-header bg-primary text-white">
      Latest Comments
      </div>
      <div class="card-body">
        <p class="card-text">
         <?php

       $myComments = getAllFrom("comment" ,"comments" , "where user_id = $userid" ,"","c_id");

        if (! empty($myComments)){
        foreach ($myComments as $comment){
          echo '<p>' . $comment['comment'] . '</p>';
        }
        } else {
          echo 'There\'s No Comments to Show';
        }
?></p>
      </div>
    </div>
  </div>
</div>

   
<?php
  } else {
     header('Location: login.php');
     exit();
  }
  include $tpl . 'footer.php';?>  