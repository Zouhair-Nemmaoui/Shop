<?php

   /*
   Manage Members Pages 
   You Can Add , Edit , Delete Members From Here
   */ 
session_start();
$pageTitle = 'Members';

if (isset($_SESSION['Username'])){
    

include 'init.php';

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

 // Start Manage Page 

 if ($do == 'Manage') {// Manage Page

    $query = '';

    if(isset($_GET['page']) && $_GET['page'] == 'Pending') {

        $query = 'And RegStatus = 0 ';
    }
     
      // Select All Users Except Admin 
      $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

      // Execute the statement 
      $stmt ->execute();
  
      // Assign To Variable 
      $rows = $stmt->fetchAll();

      if (! empty($rows)) {
 ?>  
    <h1 class="text-center"> Manage Members </h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table manage-members text-center table table-bordered">
        <tr>
            <td>
                #ID 
            </td>
             <td>
              Avatar
            </td>
            <td>
                Username
            </td>
            <td>
                Email
            </td>
            <td>
                Full Name 
            </td>
            <td>
                Registred Date
            </td>
            <td>
                Control 
            </td>
        </tr>
    
     <?php 
     foreach($rows as $row) {

          echo "<tr>";
          echo "<td>" . $row['UserID'] .  "</td>" ;
         echo "<td>";
             if (empty($row['avatar'])){
                echo 'No Image';
             } else  {
            echo " <img src='uploads/avatars/" . $row['avatar'] . "' alt = '' />";
             }
            
         echo "</td>" ;
         echo "<td>" . $row['Username'] . "</td>" ;
         echo "<td>" . $row['Email'] . "</td>" ;
         echo "<td>" . $row['FullName'] . "</td>" ;
         echo "<td>". $row['Date']  ."</td>" ;
         echo "<td>
        <a href = 'members.php?do=Edit&userid=" . $row['UserID'] . "' class = 'btn btn-success'><i class='fa fa-edit'></i> Edit </a> 
        <a href = 'members.php?do=Delete&userid=" . $row['UserID'] . "' class = 'btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";       
       
         if($row['RegStatus'] == 0) {
         echo  "<a 
                  href = 'members.php?do=Activate&userid=" . $row['UserID'] . "' 
                  class = 'btn btn-info activate'>
                  <i class='fa fa-check'></i> Activate </a>";

         }
      
        echo  "</td>" ;
          echo "</tr>";

     }
     ?>


        <tr>
      

            </table>
        </div>
    <a href ="members.php?do=Add" class="btn btn-primary">
        <i class="fa fa-plus">
    </i>  New Member 
    </a> 
    </div>
    <?php } else {
        echo '<div class="container">';
        echo '<div class="nice-message"> There\'s No Members To Show </div>';
        echo '<a href ="members.php?do=Add" class="btn btn-primary">
               <i class="fa fa-plus">
                </i>  New Member 
               </a> ';
        echo '</div>';
    } ?>
 <?php }
 elseif ($do == 'Add') {  // Add Members Page 
 ?>
  
    <h1 class="text-center"> Add New Member</h1>
    <div class="container">
   <form class="form-horizental" action="?do=Insert" method="POST" enctype="multipart/form-data">
 
    <!-- Start UserName field -->

   <div class="row mb-3">
    <label class="col-sm-2 control-label" >
        Username
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text"  name="username" class="form-control"  autocomplete="off"  required ="required" placeholder="Username To Login Into Shoop" />
    </div>
   </div>
   <!-- END UserName field -->

      <!-- Start Password field -->

      <div class="row mb-3">
    <label class="col-sm-2 control-label">
    Password 
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="password" name="password" class="form-control" autocomplete="new-password" required ="required" placeholder="Password Must Be Hard Complex"/>
    </div>
   </div>
   <!-- END Password field -->

      <!-- Start Email field -->

      <div class="row mb-3">
    <label class="col-sm-2 control-label">
        Email
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="email" name="email"  class="form-control" required ="required" placeholder="Email Must be Valid"/>
    </div>
   </div>
   <!-- END Email field -->

      <!-- Start Full Name  field -->

      <div class="row mb-3">
    <label class="col-sm-2 control-label">
         Full Name 
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text" name="full"   class="form-control"  required ="required"  placeholder="Full Name Appear In Your Profil Page"/>
    </div>
   </div>
   <!-- END Full Name field -->

   
      <!-- Start Avatar  field -->

      <div class="row mb-3">
    <label class="col-sm-2 control-label">
      User Avatar
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="file" name="avatar"   class="form-control"  required ="required" />
    </div>
   </div>
   <!-- END Avatar field -->


      <!-- Start Submit field -->
<div class="col-md-12 row mt-3">
    <div class="offset-md-2 col-md-10">
        <input type="submit" value="Add Member" class="btn btn-primary btn-sm"/>
    </div>
   </div>
   <!-- END Submit field -->
   </form>
    </div>
 
 <?php
  
 } elseif($do == 'Insert') { //Insert Member Page 
   
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        echo "<h1 class='text-center'> Insert Member</h1>";
        echo "<div class='container'>";
     
   // Upload Variables
$avatarName = $_FILES['avatar']['name'];
$avatarSize = $_FILES['avatar']['size']; 
$avatarTmp = $_FILES['avatar']['tmp_name'];
$avatarTyp = $_FILES['avatar']['type'];

// List Of Allowed File Types To Upload
$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");


// Get Avatar Extension 
$avatarExtension = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));



     // Get The Variables From The Form
 
 
     $user  =  $_POST['username'];
     $pass  =  $_POST['password'];
     $email =  $_POST['email'];
     $name  =  $_POST['full'];
     
     $hashPass = sha1($_POST['password']);
   
      
    // Validate The Form
 
    $formErrors = array(); 
     
    if(strlen($user) < 4) {
     $formErrors[]  = ' Username Cant Be Less Than <strong> 4 chracters </strong>  ';
    }   
    
    if(strlen($user) > 20) {
     $formErrors[]  = ' Username Cant Be More Than <strong> 20 chracters </strong>  ';
    }
 
     if(empty($user)) {
         $formErrors[]  = '  Username Cant Be <strong> Empty  </strong>  ';
         
     }
     if(empty($pass)) {
        $formErrors[]  = '  Password Cant Be <strong> Empty  </strong>  ';
        
    }
     
     if(empty($name)) {
         $formErrors[]  = ' Full Name Cant Be  <strong> Empty  </strong>  ';
     }
     
     if(empty($email)) {
         $formErrors[]  = ' Email Cant Be  <strong> Empty  </strong> ';
     }

    if(! empty($avatarName) &&! in_array($avatarExtension, $avatarAllowedExtension)) {
    $formErrors[]  = ' This Extension Is Not  <strong> Allowed  </strong> ';
    }

    if(empty($avatarName)) {
    $formErrors[]  = ' Avatar Is  <strong> Required </strong> ';
    }

    if($avatarSize > 4194304) {
    $formErrors[]  = ' Avatar Cant Be Larger Than<strong> 4MB </strong> ';
    }
      
      
 
     // Loop Into Errors Array And Echo It 
 
     foreach ($formErrors as $error ) {
 
      echo '<div class="alert alert-danger">' .  $error . '</div>';
     }

    // Check IF There's No Error Proceed The Update Operation 
   
     if(empty($formErrors )){

        $avatar = rand(0, 100000) . '_' . $avatarName;
        
        move_uploaded_file($avatarTmp,"uploads\avatars\\". $avatar );

        // Check If User Exist in Database

        $check = checkItem("Username", "users", $user);

        if ($check == 1){
            $theMsg =  '<div class="alert alert-danger"> Sorry This User Is Exist</div>';
             
            redirectHome($theMsg, 'back');
        }else { 
        //Inset UserInfo In Database
       
        $stmt = $con->prepare("INSERT INTO 
                             users(Username, Password, Email, FullName, RegStatus, Date, avatar)
                             VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar) ");

        $stmt->execute(array(
            'zuser'   => $user,
            'zpass'   => $hashPass ,
            'zmail'   => $email,
            'zname'   => $name,
            'zavatar' => $avatar
        ));
        // Echo success Message
  
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
        redirectHome($theMsg, 'back');    
    }
        }

    } 
     
    else{
       echo "<div class='container'>";

        $theMsg  = '<div class="alert alert-danger"> Soory You Cant Browse This Page Directly </div>';
  
        redirectHome($theMsg);

     echo "</div>";
    }
     
    echo "</div>";
 
} elseif ($do == 'Edit'){
   // Edit Page 

    // Check IF GET Request userid Is Numeric and Get The Integer Value Of IT

  $userid =isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']):0;
 
  // Select All Data Depend On This ID

  $stmt =  $con->prepare("SELECT * FROM users WHERE UserID= ? LIMIT  1");

  //  Execute Query 

   $stmt->execute(array( $userid )); 
  
   //  Fetch The Data

   $row= $stmt->fetch();  
   // The Row Count
   $count =$stmt->rowCount();
   //  IF There's Such ID Show The Form 

    if ($count > 0 ) {
 ?>
   <h1 class="text-center"> Edit Member</h1>
    <div class="container">
   <form class="form-horizental" action="?do=Update" method="POST" enctype="multipart/form-data">
   <input type="hidden" name="userid" value="<?php echo $userid ?>"/>
    <!-- Start UserName field -->

   <div class="row mb-3">
    <label class="col-sm-2 control-label" >
        Username
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text"  name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required"/>
    </div>
   </div>
   <!-- END UserName field -->

      <!-- Start Password field -->

      <div class="row mb-3">
    <label class="col-sm-2 control-label">
    Password 
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>"/>
        <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change"/>
    </div>
   </div>
   <!-- END Password field -->

      <!-- Start Email field -->

      <div class="row mb-3">
    <label class="col-sm-2 control-label">
        Email
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required"/>
    </div>
   </div>
   <!-- END Email field -->
   <!-- Star Avatar field -->
   <div class="row mb-3">
    <label class="col-sm-2 control-label">
      User Avatar
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="file" name="avatar"   class="form-control"  required ="required" />
    </div>
   </div>
     <!-- End Avatar field -->
      <!-- Start Full Name  field -->

      <div class="row mb-3">
    <label class="col-sm-2 control-label">
         Full Name 
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required"/>
    </div>
   </div>
   <!-- END Full Name field -->


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
   echo "<h1 class='text-center'> Update Member</h1>";
   echo "<div class='container'>";

      // Upload Variables
$avatarName = $_FILES['avatar']['name'];
$avatarSize = $_FILES['avatar']['size']; 
$avatarTmp = $_FILES['avatar']['tmp_name'];
$avatarTyp = $_FILES['avatar']['type'];

// List Of Allowed File Types To Upload
$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");


// Get Avatar Extension 
$avatarExtension = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));



   if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Get The Variables From The Form

    $id    =  $_POST['userid'];
    $user  =  $_POST['username'];
    $email =  $_POST['email'];
    $name  =  $_POST['full'];

    // Password Trick

    // Condition ? true : false;

    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);  
     
   // Validate The Form

   
   $formErrors = array(); 
     
   if(strlen($user) < 4) {
    $formErrors[]  = ' Username Cant Be Less Than <strong> 4 chracters </strong>  ';
   }   
   
   if(strlen($user) > 20) {
    $formErrors[]  = 'Username Cant Be More Than <strong> 20 chracters </strong>   ';
   }

    if(empty($user)) {
        $formErrors[]  = ' Username Cant Be <strong> Empty  </strong>   ';
        
    }
    
    if(empty($name)) {
        $formErrors[]  = 'Full Name Cant Be  <strong> Empty  </strong>   ';
    }
    
    if(empty($email)) {
        $formErrors[]  = ' Email Cant Be<strong> Empty  </strong>   ';
    }
     if(! empty($avatarName) &&! in_array($avatarExtension, $avatarAllowedExtension)) {
    $formErrors[]  = ' This Extension Is Not  <strong> Allowed  </strong> ';
    }

    if(empty($avatarName)) {
    $formErrors[]  = ' Avatar Is  <strong> Required </strong> ';
    }

    if($avatarSize > 4194304) {
    $formErrors[]  = ' Avatar Cant Be Larger Than<strong> 4MB </strong> ';
    }

    // Loop Into Errors Array And Echo It 

    foreach ($formErrors as $error ) {

        echo '<div class="alert alert-danger">' .  $error . '</div>';

    }
      if(empty($formErrors )){

        $avatar = rand(0, 100000) . '_' . $avatarName;
        
        move_uploaded_file($avatarTmp,"uploads\avatars\\". $avatar );
      }

   // Check IF There's No Error Proceed The Update Operation 

    if(empty($formErrors )){
       
        $stmt2 = $con->prepare("SELECT 
                                    * 
                                FROM 
                                   users 
                                WHERE
                                   Username = ? 
                                AND 
                                   UserID != ?");

        $stmt2->execute(array($user, $id));

        $count = $stmt2->rowCount();

        echo $count ;

     if($count == 1) {
    $theMsg = '<div class="alert alert-danger">Sorry, this user already exists.</div>';
    redirectHome($theMsg, 'back');
}
 else {
          // Update The DataBase With This Infos
     $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, avatar = ? WHERE UserID = ?");
     $stmt->execute(array($user, $email, $name, $pass, $avatar, $id));
       // Echo success Message
 
       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
    
       redirectHome($theMsg, 'back');
        } }

   }else{

    $theMsg = '<div class="alert alert-danger"> Soory You Cant Browse This Page Directly </div>';

    redirectHome($theMsg);
   }
    
   echo "</div>";
    
} elseif ($do == 'Delete'){// Delete Member Pages 
    echo "<h1 class='text-center'> Delete Member</h1>";
    echo "<div class='container'>";
 
   // Check IF GET Request userid Is Numeric and Get The Integer Value Of IT

  $userid =isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']):0;
 
  // Select All Data Depend On This ID

  
  
  $check = checkItem('userid', 'users', $userid);

 
  
 
   //  IF There's Such ID Show The Form 
 
    if ($check > 0 ) {
        $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
       
        $stmt->bindParam(":zuser", $userid );
       
        $stmt->execute();
       
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
       
        redirectHome($theMsg, 'back');
    } else {
        $theMsg =  '<div class="alert alert-danger"> This ID is Not Exist </div>';

        redirectHome($theMsg);
    }
    
    echo '</div>';
} elseif ($do == 'Activate'){
    echo "<h1 class='text-center'> Activate Member</h1>";
    echo "<div class='container'>";
 
   // Check IF GET Request userid Is Numeric and Get The Integer Value Of IT

  $userid =isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']):0;
 
  // Select All Data Depend On This ID

  
  
  $check = checkItem('userid', 'users', $userid);

 
  
 
   //  IF There's Such ID Show The Form 
 
    if ($check > 0 ) {
        $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
       
        $stmt->execute(array($userid));
       
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
       
        redirectHome($theMsg);
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