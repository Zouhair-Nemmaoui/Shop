<?php 
    ob_start();
  session_start();

  $pageTitle = 'Show Items ';

  include 'init.php';

   // Check IF GET Request item Is Numeric and Get The Integer Value Of IT

  $itemid =isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']):0;
 
  // Select All Data Depend On This ID

  $stmt =  $con->prepare("SELECT 
                               items.*, 
                               categories.Name AS category_name,
                               users.Username 
                          FROM 
                               items 
                          INNER JOIN
                               categories
                          ON
                               categories.ID = items.Cat_ID 
                          INNER JOIN 
                               users
                          ON
                               users.UserID = items.Member_ID               
                          WHERE 
                               Item_ID = ?
                          AND 
                               Approve = 1 ");

  //  Execute Query 

   $stmt->execute(array($itemid )); 
  
   $count = $stmt->rowCount();

   if($count > 0) {
    
   //  Fetch The Data

   $item = $stmt->fetch();  


?>

  <h1 class="text-center"><?php echo $item['Name']  ?>  </h1>
  <div class="container">
       <div class="row">
        <div class="col-md-3">
    <?php if (!empty($item['item'])) {
        echo "<img class='img-responsive' src='admin/uploads/items/" . $item['item'] . "' alt='" . $item['Name'] . "' />";
    } else {
        echo '<div class="no-image">No Image</div>';
    } ?>  

        </div>
        <div class="col-md-9 item-info">
          <h2> <?php echo $item['Name']  ?> </h2>
          <p> <?php echo $item['Description']  ?> </p>
          <ul class="list-unstyled">
               <li>
               <i class="fa fa-calendar fa-fw"></i>  
               <span>  Added Date</span> : <?php echo $item['Add_Date']  ?> </li>
              <li>  
              <i class="fas fa-money-bill"></i>  
              <span> Price </span> : $<?php echo $item['Price']  ?> </li>
              <li> 
               <i class="fa fa-building fa-fw"></i> 
              <span> Made In  </span> : <?php echo $item['Country_Made']  ?> </li>
              <li> 
              <i class="fa fa-tags fa-fw"></i>  
              <span> Category</span> : <a href="categories.php?pageid= <?php echo  $item['Cat_ID']  ?>"> <?php echo $item['category_name']  ?> </a> </li> 
              <li>
              <i class="fa fa-user fa-fw"></i>  
              <span> Added By </span> : <a href="#">  <?php echo $item['Username']  ?> </a></li>
                <li class="tags-items">
              <i class="fa fa-user fa-fw"></i>  
              <span> Tags </span> : 
             <?php
$allTags = explode(",", $item['tags']);
foreach($allTags as $tag) {
    $tag = trim($tag);
    $lowertag = strtolower($tag);
    if (! empty($tag)){
       echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a> ';
}
    }
    
?>
        </ul> 
        </ul>
        
        </div>
       </div>
       <hr class="custom-hr">
     <?php if (isset($_SESSION['user'])){
          ?>
     
     <!-- Start Add Comment -->
       <div class="row">
       <div class="col-md-6 offset-md-3">
       <div class="add-comment">
       <h3>Add Your Comment </h3>
       <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
          <textarea name="comment" required></textarea>
          <input class="btn btn-primary" type="submit" value="Add Comment">
       </form>
       <?php
       
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     
           $comment     = strip_tags($_POST['comment']);
           $itemid      =  $item['Item_ID'];
           $userid      =   $_SESSION['uid'];
         

          if (!empty($comment)) {
    $stmt = $con->prepare("INSERT INTO 
        comments(comment, status, comment_date, item_id, user_id)
        VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

    $stmt->execute(array(
        'zcomment' => $comment,
        'zitemid'  => $itemid,
        'zuserid'  => $userid
    ));

    if ($stmt) {
        echo '<div class="alert alert-success">Comment Added</div>';
    }
}

       }
       
       ?>
      </div>
       </div>
       </div>
        <!-- End Add Comment -->
      <?php } else {
           echo '<a href = "login.php">Login</a> or <a href = "login.php"> Register </a> To Add Comment';
      }
      
      
      ?>
       <hr class="custom-hr">
          <?php 
          // Select All Users Except Admin 
      $stmt = $con->prepare("SELECT
                                   comments.*, users.UserName AS Member  
                             FROM 
                                  comments
                 
                             INNER JOIN 
                                   users
                            ON 
                                 users.UserID = comments.user_id  
                            WHERE 
                                item_id = ?  
                            AND 
                                status = 1       
                            ORDER BY 
                                  c_id DESC");

      // Execute the statement 
      $stmt ->execute(array($item['Item_ID']));
  
      // Assign To Variable 
      $comments = $stmt->fetchAll();

    
      
     ?>
       
     <?php 
       foreach ($comments as $comment ) { ?>
    <div class="comment-box">
         <div class="row">  
                <div class ="col-sm-2 text-center">
                    <?php
         $username = $comment['Member'];

             global $con;
            $stmt = $con->prepare("SELECT avatar FROM users WHERE Username = ?");
            $stmt->execute(array($username));
            $commentUser = $stmt->fetch();

        if (!empty($commentUser['avatar'])) {
                echo "<img class='my-imagee img-thumbnail img-circle' src='admin/uploads/avatars/" . $commentUser['avatar'] . "' alt='User Avatar' />";
            } else {
                echo "<div class='no-image'>No Image</div>";
            }
            ?>
                <br> 
         <?php echo  $comment ['Member']  ?> </div> 
                <div class= "col-sm-10"> 
               <p class="lead">
        <?php echo $comment ['comment']  ?>  </p>     
</div> 
</div> 
</div>
      <hr class="custom-hr">

  <?php    } ?>
 
     
  </div>

   <?php

   }else {
     echo '<div class ="container">';

    echo '<div class ="alert alert-danger"> There\'s no Such ID Or This Item Is Waiting Approval';

    echo '</div>';
   }


  include $tpl . 'footer.php';
  ob_end_flush();
  ?>  