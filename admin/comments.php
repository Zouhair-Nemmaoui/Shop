<?php

   /*
   Manage Comments Page
   You Can  Edit , Delete, Approve Comments From Here
   */ 
session_start();
$pageTitle = 'Comments';

if (isset($_SESSION['Username'])){
    

include 'init.php';

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

 // Start Manage Page 

 if ($do == 'Manage') {// Manage Page

 
     
      // Select All Users Except Admin 
      $stmt = $con->prepare("SELECT
                                   comments.*, items.Name AS Item_Name, users.UserName AS Member  
                             FROM 
                                  comments
                             INNER JOIN    
                                   items  
                             ON 
                                 items.Item_ID = comments.item_id 
                             INNER JOIN 
                                   users
                            ON 
                                 users.UserID = comments.user_id  
                            ORDER BY 
                                  c_id DESC");

      // Execute the statement 
      $stmt ->execute();
  
      // Assign To Variable 
      $comments = $stmt->fetchAll();
      
    if(! empty($comments)){
 ?>  
    <h1 class="text-center"> Manage Comments </h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
        <tr>
            <td>
                #ID 
            </td>
            <td>
                Comment 
            </td>
            <td>
                Item Name
            </td>
            <td>
                User Name 
            </td>
            <td>
                Added Date
            </td>
            <td>
                Control 
            </td>
        </tr>
    
     <?php 
     foreach($comments as $comment) {

          echo "<tr>";
          echo "<td>" . $comment['c_id'] .  "</td>" ;
         echo "<td>" . $comment['comment'] . "</td>" ;
         echo "<td>" . $comment['Item_Name'] . "</td>" ;
         echo "<td>" . $comment['Member'] . "</td>" ;
         echo "<td>". $comment['comment_date']  ."</td>" ;
         echo "<td>
        <a href = 'comments.php?do=Edit&comid=" . $comment['c_id'] . "' class = 'btn btn-success'><i class='fa fa-edit'></i> Edit </a> 
        <a href = 'comments.php?do=Delete&comid=" . $comment['c_id'] . "' class = 'btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";       
       
         if($comment['status'] == 0) {
         echo  "<a href = 'comments.php?do=Approve&comid=" 
                  . $comment['c_id'] . "' 
                  class = 'btn btn-info activate'>
                  <i class='fa fa-check'></i> Approve </a>";

         }
      
        echo  "</td>" ;
          echo "</tr>";

     }
     ?>


        <tr>
      

            </table>
        </div>
    </div>
 <?php } else {
        echo '<div class="container">';
        echo '<div class="nice-message"> There\'s No Comments To Show </div>';
        echo '</div>';
    } ?>
 <?php }
      
    elseif ($do == 'Edit'){
   // Edit Page 

    // Check IF GET Request comid Is Numeric and Get The Integer Value Of IT

  $comid =isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):0;
 
  // Select All Data Depend On This ID

  $stmt =  $con->prepare("SELECT * FROM comments WHERE c_id= ? ");

  //  Execute Query 

   $stmt->execute(array( $comid )); 
  
   //  Fetch The Data

   $row= $stmt->fetch();  
   // The Row Count
   $count =$stmt->rowCount();
   //  IF There's Such ID Show The Form 

    if ($count > 0 ) {
 ?>
   <h1 class="text-center"> Edit Comment</h1>
    <div class="container">
   <form class="form-horizental" action="?do=Update" method="POST">
   <input type="hidden" name="comid" value="<?php echo $comid ?>"/>
    <!-- Start Comment field -->

   <div class="row">
    <label class="col-sm-2 control-label" >
            Comment
    </label>
    <div class="col-sm-10 col-md-4">
        <textarea class="form-control" name="comment">
            <?php echo $row['comment']  ?>
        </textarea>
    </div>
   </div>
   <!-- END Comment field -->

      <!-- Start Submit field -->

      <div class="col-md-12 row mt-3">
    <div class="offset-md-2 col-md-10">

        <input type="submit" value="Save" class="btn btn-primary btn-sm"/>
    </div>
   </div>
   <!-- END Submit field -->



   </form>
    </div>
 



 <?php
 //  Else Show Error Message    
} else {
     
    echo "<div class='container'>";

    $theMsg = '<div class="alert alert-danger"> There No Such ID </div>';

    redirectHome($theMsg); 

    echo "</div>";
 }
}elseif($do == 'Update'){//Update Page
   echo "<h1 class='text-center'> Update Comment</h1>";
   echo "<div class='container'>";

   if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Get The Variables From The Form

    $comid    =  $_POST['comid'];
    $comment  =  $_POST['comment'];

       // Update The DataBase With This Infos

      $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");

      $stmt->execute(array($comment, $comid));

       // Echo success Message
 
       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
    
       redirectHome($theMsg, 'back');
 

   }else{

    $theMsg = '<div class="alert alert-danger"> Soory You Cant Browse This Page Directly </div>';

    redirectHome($theMsg);
   }
    
   echo "</div>";
    
} elseif ($do == 'Delete'){// Delete Comment Pages 
    echo "<h1 class='text-center'> Delete Comment</h1>";

    echo "<div class='container'>";
 
   // Check IF GET Request comid Is Numeric and Get The Integer Value Of IT

  $comid =isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):0;
 
  // Select All Data Depend On This ID

  
  
  $check = checkItem('c_id', 'comments', $comid);

 
  
 
   //  IF There's Such ID Show The Form 
 
    if ($check > 0 ) {
        $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
       
        $stmt->bindParam(":zid", $comid );
       
        $stmt->execute();
       
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
       
        redirectHome($theMsg, 'back');
    } else {
        $theMsg =  '<div class="alert alert-danger"> This ID is Not Exist </div>';

        redirectHome($theMsg);
    }
    
    echo '</div>';
} elseif ($do == 'Approve'){
    echo "<h1 class='text-center'> Approve Comment</h1>";
    echo "<div class='container'>";
 
   // Check IF GET Request comid Is Numeric and Get The Integer Value Of IT

  $comid =isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):0;
 
  // Select All Data Depend On This ID

  $check = checkItem('c_id', 'comments', $comid);

   //  IF There's Such ID Show The Form 
 
    if ($check > 0 ) {
        $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
       
        $stmt->execute(array($comid));
       
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved</div>';
       
        redirectHome($theMsg, 'back');
    } else {
        $theMsg =  '<div class="alert alert-danger"> This ID is Not Exist </div>';

        redirectHome($theMsg);
    }
    
    echo '</div>';
}

include $tpl . 'footer.php';
}  else {
    header('Location: index.php');
    exit();
}