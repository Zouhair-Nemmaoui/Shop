<?php

    /* ==============
       == Items Page
       =================
    */ 
  
   ob_start();// Output Buffering Start

   session_start();

   $pageTitle = 'Items';
   

   if(isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

    if($do == 'Manage') {
       
 // Start Manage Page 

 if ($do == 'Manage') {// Manage Page
 
      $stmt = $con->prepare("SELECT 
                                  items.*, 
                                  categories.Name AS category_name, 
                                  users.Username AS member 
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
                              ORDER BY 
                                    Item_ID DESC       ");

      // Execute the statement 
      $stmt ->execute();
  
      // Assign To Variable 
      $items = $stmt->fetchAll();
      
      if(! empty($items)){

 ?>  
    <h1 class="text-center"> Manage Items </h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
        <tr>
            <td>
                #ID 
            </td>
            <td>
               Name
            </td>
            <td>
               Description
            </td>
            <td>
                Price 
            </td>
            <td>
                Adding Date
            </td>
            <td>
                Category
            </td>
            <td>
                Username
            </td>
            <td>
                Control 
            </td>
        </tr>
    
     <?php 
     foreach($items as $item) {

          echo "<tr>";
          echo "<td>" . $item['Item_ID'] .  "</td>" ;
         echo "<td>" . $item['Name'] . "</td>" ;
         echo "<td>" . $item['Description'] . "</td>" ;
         echo "<td>" . $item['Price'] . "</td>" ;
         echo "<td>". $item['Add_Date']  ."</td>" ;
          echo "<td>". $item['category_name']  ."</td>" ;
           echo "<td>". $item['member']  ."</td>" ;
         echo "<td>
        <a href = 'items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class = 'btn btn-success'><i class='fa fa-edit'></i> Edit </a> 
        <a href = 'items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class = 'btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";       
         if($item['Approve'] == 0) {
         echo  "<a 
                 href = 'items.php?do=Approve&itemid=" . $item['Item_ID'] . "' 
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
    <a href ="items.php?do=Add" class="btn btn-sm btn-primary">
        <i class="fa fa-plus">
        </i>  New Item  
    </a> 
    </div>
       <?php } else {
        echo '<div class="container">';
        echo '<div class="nice-message"> There\'s No Items To Show </div>';
        echo '<a href ="items.php?do=Add" class="btn btn-sm btn-primary">
                 <i class="fa fa-plus">
                 </i>  New Item  
              </a> ' ; 
        echo '</div>';
    } ?>
 <?php }
    } elseif ($do == 'Add') { ?>
        <h1 class="text-center"> Add New Item </h1>
    <div class="container">
   <form class="form-horizental" action="?do=Insert" method="POST"  enctype="multipart/form-data">
 
    <!-- Start Name field -->

   <div class="row mb-3">
    <label class="col-sm-2 control-label" >
        Name
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text" 
         name="name" 
         class="form-control" 
        
         placeholder="Name of The Item" />
    </div>
   </div>
   <!-- END Name field --> 

     <!-- Start Description field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
        Description 
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="description" 
        class="form-control"  
       
        placeholder="Description of The Item" />
    </div>
   </div>
   <!-- END Description field --> 

    <!-- Start Price field -->

    <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Price
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="price" 
        class="form-control"  
    
        placeholder="Price of The Item" />
    </div>
   </div>
   <!-- END Price field -->

   <!-- Start Country field -->

   <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Country  
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="country" 
        class="form-control"  
        
        placeholder="Country of Made" />
    </div>
   </div>
   <!-- END Country field --> 

     <!-- Start Status field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Status
    </label>
    <div class="col-sm-10 col-md-4">
        <select name="status">
            <option value="0">...</option> 
            <option value="1">New</option>
            <option value="2">Like New</option>
            <option value="3">Used</option>
            <option value="4">Old</option>
        </select>
    </div>
   </div>
   <!-- END Status field --> 

       <!-- Start Members field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Member 
    </label>
    <div class="col-sm-10 col-md-4">
        <select name="member">
            <option value="0">...</option> 
            <?php
                 $allMembers = getAllFrom("*" , "users" , " " ," " , "UserID");
                 foreach ($allMembers as $user) {
                    echo "<option value='" . $user['UserID'] . "'> " .  $user['Username']  . " </option>";
                 }

             ?>
        </select>
    </div>
   </div>
   <!-- END Members field -->

   <!-- Start Categories field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
     Category
    </label>
    <div class="col-sm-10 col-md-4">
        <select name="category">
            <option value="0">...</option> 
            <?php
             $allCats = getAllFrom("*" , "categories" , "where parent = 0" ," " , "ID");
              foreach ($allCats as $cat) {
                    echo "<option value='" . $cat['ID'] . "'> " .  $cat['Name']  . " </option>";
                  $childCats = getAllFrom("*" , "categories" , "where parent = {$cat['ID']}" ," " , "ID");

                foreach ($childCats as $child) {
                   echo "<option value='" . $child['ID'] . "'>--- " .  $child['Name']  ." </option>";    
                }  
                }

             ?>
        </select>
    </div>
   </div>
   <!-- END Categories field -->
   <div class="row mb-3">
    <label class="col-sm-2 control-label">
     Items 
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="file" name="item"  class="form-control"  required ="required" />
    </div>
   </div>
   <!-- Start Tags field -->

   <div class="row">
    <label class="col-sm-2 control-label" >
    Tags
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="tags" 
        class="form-control"  
        placeholder="Separate Tags With Comma (,)" />
    </div>
   </div>
   <!-- END Tags field --> 
      <!-- Start Submit field -->
      <div class="row">
            <div class="col-sm-10 col-md-4 offset-sm-2">
                <input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
            </div>
        </div>
   <!-- END Submit field -->



   </form>
    </div>

           <?php 


    } elseif ($do == 'Insert'){
  
   
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        echo "<h1 class='text-center'> Update Item</h1>";
        echo "<div class='container'>";
        // Upload Variables

      $itemName = $_FILES['item']['name'];
      $itemSize = $_FILES['item']['size']; 
      $itemTmp  = $_FILES['item']['tmp_name'];
      $itemTyp  = $_FILES['item']['type'];
      
      
   // List Of Allowed File Types To Upload

   $itemAllowedExtension = array("jpeg", "jpg", "png", "gif");
   
// Get Item Extension 
$itemExtension = strtolower(pathinfo($itemName, PATHINFO_EXTENSION));

   
   // Get The Variables From The Form
 
 
     $name    =  $_POST['name'];
     $desc    =  $_POST['description'];
     $price   =  $_POST['price'];
     $country =  $_POST['country'];
     $status  =  $_POST['status'];
     $member  =  $_POST['member'];
     $cat     =  $_POST['category'];
     $tags    =  $_POST['tags'];
    
     
  
      
    // Validate The Form
 
    $formErrors = array(); 
     
    if(empty($name)) {
     $formErrors[]  = 'Name Can\'t be  <strong> Empty </strong>  ';
    }   
    
    if(empty($desc)) {
     $formErrors[]  = 'Description Can\'t be  <strong> Empty </strong>   ';
    }
 
     if(empty($price)) {
         $formErrors[]  = 'Price Can\'t be  <strong> Empty </strong>   ';
         
     }
     if(empty($country)) {
        $formErrors[]  = '  Country Can\'t be  <strong> Empty </strong>   ';
        
    }
     
     if($status == 0 ) {
         $formErrors[]  = ' You Must Choose the  <strong> Status </strong>  ';
     } 

     if($member == 0 ) {
         $formErrors[]  = ' You Must Choose the  <strong> Member </strong>  ';
     }
      
     if($cat == 0 ) {
         $formErrors[]  = ' You Must Choose the  <strong> Category </strong>  ';
     }
       if(! empty($itemName) &&! in_array($itemExtension, $itemAllowedExtension)) {
    $formErrors[]  = ' This Extension Is Not  <strong> Allowed  </strong> ';
    }

    if(empty($itemName)) {
    $formErrors[]  = ' Item Is  <strong> Required </strong> ';
    }

    if($itemSize > 4194304) {
    $formErrors[]  = ' Item Cant Be Larger Than<strong> 4MB </strong> ';
    } 
      
 
     // Loop Into Errors Array And Echo It 
 
     foreach ($formErrors as $error ) {
 
      echo '<div class="alert alert-danger">' .  $error . '</div>';
     }
    // Check IF There's No Error Proceed The Update Operation 
 
     if(empty($formErrors )){
        
         $item = rand(0, 100000) . '_' . $itemName;
        
        move_uploaded_file($itemTmp,"uploads\items\\". $item );
        //Inset UserInfo In Database
       
        $stmt = $con->prepare("INSERT INTO 

                items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, item, tags)

             VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :zitems, :ztags)");

        $stmt->execute(array(
            'zname'    => $name,
            'zdesc'    => $desc ,
            'zprice'   => $price,
            'zcountry' => $country,
            'zstatus'  => $status,
            'zcat'     => $cat,
            'zmember'  => $member,
            'zitems'   => $item,
            'ztags'    => $tags
        ));
        // Echo success Message
  
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
        redirectHome($theMsg);    
 
        }

    }else{
       echo "<div class='container'>";

        $theMsg  = '<div class="alert alert-danger"> Soory You Cant Browse This Page Directly </div>';
  
        redirectHome($theMsg, 'back');

     echo "</div>";
    }
     
    echo "</div>";

    } elseif ($do == 'Edit') { 
  

    // Check IF GET Request item Is Numeric and Get The Integer Value Of IT

  $itemid =isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']):0;
 
  // Select All Data Depend On This ID

  $stmt =  $con->prepare("SELECT * FROM items WHERE Item_ID = ?");

  //  Execute Query 

   $stmt->execute(array($itemid )); 
  
   //  Fetch The Data

   $item = $stmt->fetch();  
   // The Row Count
   $count =$stmt->rowCount();
   //  IF There's Such ID Show The Form 

    if ($count > 0 ) {
 ?>
   <h1 class="text-center"> Edit Item </h1>
    <div class="container">
   <form class="form-horizental" action="?do=Update" method="POST">
    <input type="hidden" name="itemid" value="<?php echo $itemid ?>"/>
    <!-- Start Name field -->

   <div class="row mb-3">
    <label class="col-sm-2 control-label" >
        Name
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text" 
         name="name" 
         class="form-control" 
         placeholder="Name of The Item"  
         value="<?php echo $item['Name']  ?>"
         />
    </div>
   </div>
   <!-- END Name field --> 

     <!-- Start Description field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
        Description 
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="description" 
        class="form-control"  
        placeholder="Description of The Item"
        value="<?php echo $item['Description']  ?>" />
    </div>
   </div>
   <!-- END Description field --> 

    <!-- Start Price field -->

    <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Price
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="price" 
        class="form-control"  
        placeholder="Price of The Item"
        value="<?php echo $item['Price']  ?>" />
    </div>
   </div>
   <!-- END Price field -->

   <!-- Start Country field -->

   <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Country  
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="country" 
        class="form-control"  
        placeholder="Country of Made"
        value="<?php echo $item['Country_Made']  ?>" />
    </div>
   </div>
   <!-- END Country field --> 

     <!-- Start Status field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Status
    </label>
    <div class="col-sm-10 col-md-4">
        <select name="status">
           
            <option value="1" <?php if($item['Status'] == 1) {echo 'selected' ;} ?>>New</option>
            <option value="2" <?php if($item['Status'] == 2) {echo 'selected' ;} ?>>Like New</option>
            <option value="3" <?php if($item['Status'] == 3) {echo 'selected' ;} ?>>Used</option>
            <option value="4" <?php if($item['Status'] == 4) {echo 'selected' ;} ?>>Old</option>
        </select>
    </div>
   </div>
   <!-- END Status field --> 

       <!-- Start Members field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
      Member 
    </label>
    <div class="col-sm-10 col-md-4">
        <select name="member">
            
            <?php
                 $stmt = $con->prepare("SELECT * FROM users");
                 $stmt->execute();
                 $users = $stmt->fetchAll();
                 foreach ($users as $user) {
                    echo "<option value='" . $user['UserID'] . "'";
                    if($item['Member_ID'] ==  $user['UserID']) {echo 'selected' ;} 
                    echo "> " .  $user['Username']  . " </option>";
                 }

             ?>
        </select>
    </div>
   </div>
   <!-- END Members field -->

   <!-- Start Categories field -->

     <div class="row mb-3">
    <label class="col-sm-2 control-label" >
     Category
    </label>
    <div class="col-sm-10 col-md-4">
        <select name="category">
     
            <?php
                 $stmt2 = $con->prepare("SELECT * FROM categories");
                 $stmt2->execute();
                 $cats = $stmt2->fetchAll();
                 foreach ($cats as $cat) {
                    echo "<option value='" . $cat['ID'] . "'";
                    if($item['Cat_ID'] ==  $cat['ID']) {echo 'selected' ;} 
                    echo "> " .  $cat['Name']  . " </option>";
                 }

             ?>
        </select>
    </div>
   </div>
   <!-- END Categories field -->
    
  <!-- Start Tags field -->

   <div class="row mb-3">
    <label class="col-sm-2 control-label" >
    Tags
    </label>
    <div class="col-sm-10 col-md-4">
        <input 
        type="text"  
        name="tags" 
        class="form-control"  
        placeholder="Separate Tags With Comma (,)" 
        value="<?php echo $item['tags']  ?>" />
    </div>
   </div>

   
   <!-- END Tags field --> 
      <!-- Start Submit field -->

      <div class="col-md-12 row mt-3">
    <div class="offset-md-2 col-md-10">


        <input type="submit" value="Save Item" class="btn btn-primary btn-sm"/>
    </div>
   </div>
 

   <!-- END Submit field -->



   </form>

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
                            WHERE item_id = ? ");

      // Execute the statement 
      $stmt ->execute(array($itemid));
  
      // Assign To Variable 
      $rows = $stmt->fetchAll();

      if (! empty($rows)){
        
 ?>  
    <h1 class="text-center"> Manage [<?php echo $item['Name']  ?>] Comments </h1>
     
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
        <tr>
            
            <td>
                Comment 
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
     foreach($rows as $row) {

          echo "<tr>";
         echo "<td>" . $row['comment'] . "</td>" ;
         echo "<td>" . $row['Member'] . "</td>" ;
         echo "<td>". $row['comment_date']  ."</td>" ;
         echo "<td>
        <a href = 'comments.php?do=Edit&comid=" . $row['c_id'] . "' class = 'btn btn-success'><i class='fa fa-edit'></i> Edit </a> 
        <a href = 'comments.php?do=Delete&comid=" . $row['c_id'] . "' class = 'btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";       
       
         if($row['status'] == 0) {
         echo  "<a href = 'comments.php?do=Approve&comid=" 
                  . $row['c_id'] . "' 
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
    <?php } ?>
    
 
    </div>

 <?php
 //  Else Show Error Message    
} else {
     
    echo "<div class='container'>";

    $theMsg = '<div class="alert alert-danger"> There No Such ID </div>';

    redirectHome($theMsg); 

    echo "</div>";
}

    } elseif ($do == 'Update') {

      echo "<h1 class='text-center'> Update Item</h1>";
   echo "<div class='container'>";
   

   if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Get The Variables From The Form

    $id        =  $_POST['itemid'];
    $name      =  $_POST['name'];
    $desc      =  $_POST['description'];
    $price     =  $_POST['price'];
    $country   =  $_POST['country'];
    $status    =  $_POST['status'];
    $cat       =  $_POST['category'];
    $member    =  $_POST['member'];
    $tags      =  $_POST['tags'];
   


   
     
    // Validate The Form
 
    $formErrors = array(); 
     
    if(empty($name)) {
     $formErrors[]  = 'Name Can\'t be  <strong> Empty </strong>  ';
    }   
    
    if(empty($desc)) {
     $formErrors[]  = 'Description Can\'t be  <strong> Empty </strong>   ';
    }
 
     if(empty($price)) {
         $formErrors[]  = 'Price Can\'t be  <strong> Empty </strong>   ';
         
     }
     if(empty($country)) {
        $formErrors[]  = '  Country Can\'t be  <strong> Empty </strong>   ';
        
    }
     
     if($status == 0 ) {
         $formErrors[]  = ' You Must Choose the  <strong> Status </strong>  ';
     } 

     if($member == 0 ) {
         $formErrors[]  = ' You Must Choose the  <strong> Member </strong>  ';
     }
      
     if($cat == 0 ) {
         $formErrors[]  = ' You Must Choose the  <strong> Category </strong>  ';
     }
      
      
 
     // Loop Into Errors Array And Echo It 
 
     foreach ($formErrors as $error ) {
 
      echo '<div class="alert alert-danger">' .  $error . '</div>';
     }
   // Check IF There's No Error Proceed The Update Operation 

    if(empty($formErrors )){

       // Update The DataBase With This Infos

      $stmt = $con->prepare("UPDATE 
                                   items 
                             SET 
                                   Name         = ?, 
                                   Description  = ?, 
                                   Price        = ?, 
                                   Country_Made = ?,
                                   Status       = ?,
                                   Cat_ID       = ?,
                                   Member_ID    = ?,
                                   tags         = ?

                            WHERE 
                                   Item_ID = ?");
      $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

       // Echo success Message
 
       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
    
       redirectHome($theMsg, 'back');
    }

   }else{

    $theMsg = '<div class="alert alert-danger"> Soory You Cant Browse This Page Directly </div>';

    redirectHome($theMsg);
   }
    
   echo "</div>";


    
    } elseif ($do == 'Delete'){
        echo "<h1 class='text-center'> Delete Item</h1>";
    echo "<div class='container'>";
 
   // Check IF GET Request ItemID Is Numeric and Get The Integer Value Of IT

  $itemid =isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']):0;
 
  // Select All Data Depend On This ID

  
  
  $check = checkItem('Item_ID', 'items', $itemid);

 
  
 
   //  IF There's Such ID Show The Form 
 
    if ($check > 0 ) {
        $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
       
        $stmt->bindParam(":zid", $itemid );
       
        $stmt->execute();
       
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
       
        redirectHome($theMsg, 'back');
    } else {
        $theMsg =  '<div class="alert alert-danger"> This ID is Not Exist </div>';

        redirectHome($theMsg);
    }
    
    echo '</div>';

    } elseif ($do == 'Approve'){

    echo "<h1 class='text-center'> Approve Item</h1>";
    echo "<div class='container'>";
 
   // Check IF GET Request Item_ID Is Numeric and Get The Integer Value Of IT

  $itemid =isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']):0;
 
  // Select All Data Depend On This ID

  
  
  $check = checkItem('Item_ID', 'items', $itemid);

 
  
 
   //  IF There's Such ID Show The Form 
 
    if ($check > 0 ) {
        $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
       
        $stmt->execute(array($itemid));
       
        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
       
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

   
  ob_end_flush(); //Release The Output

?> 