<?php

    /* ==============
       == Category Page 
       =================
    */ 
  
   ob_start();// Output Buffering Start

   session_start();

   $pageTitle = 'Categories';

  if(isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;

   if($do == 'Manage') {
        
        $sort = 'Asc';

        $sort_array = array('Asc', 'Desc');

    if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
           
            $sort = $_GET['sort'];
        }

        $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
        
        $stmt2->execute();
        
        $cats = $stmt2->fetchAll(); ?>
          
        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
             <div class="panel panel-default">
                  <div class="panel-heading">
            <i class="fa fa-edit"></i>Manage Categories
                   <div class = "option float-end">
                      <i class="fa fa-sort"></i>  Ordering: [     
                        <a class="<?php if ($sort == 'Asc') { echo 'active' ;} ?>" href="?sort=Asc">Asc</a> |
                        <a class="<?php if ($sort == 'Desc') { echo 'active' ;} ?>" href="?sort=Desc">Desc</a> ]
                      <i class="fa fa-eye"></i>View : [ 
                         <span class="active" data-view="full"> Full </span> | 
                         <span data-view="classic">Classic </span> ]       
                    </div>
                  </div>
                  <div class="panel-body">
                       <?php
                         foreach($cats as $cat) {
                            
                            echo "<div class='cat'>";
                                 echo "<div class='hidden-buttons'>";
                                      echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit </a>";
                                      echo "<a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete </a>";  
                                 echo "</div>";
                                 echo "<h3>" . $cat['Name'] . '</h3>';
                                 echo "<div class ='full-view'> " ;
                                 echo "<p>"; if($cat['Description'] == '') {echo 'This category has no description' ;} else {echo  $cat['Description']; } echo   "</p>"; 
                                 if($cat['Visibility'] == 1) { echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>'; }
                                 if($cat['Allow_Comment'] == 1) { echo '<span class="commenting"><i class="fa fa-close"></i>Comment Disabled</span>'; }
                                 if($cat['Allow_Ads'] == 1) { echo '<span class="advertises"><i class="fa fa-close"></i>Ads Disabled</span>'; }  
                                
                                     // Get Child Categories
                        $childCats = getAllFrom("*", "categories","where parent = {$cat['ID']}", "", "ID" ,"ASC" );
                              if (! empty($childCats)){
                              echo "<h4 class='child-head'> Child Categories</h4>";
                              echo "<ul class='list-unstyled child-cats'>";
                              foreach($childCats as $c ){
                                echo "<li class='child-link'>
                                <a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
                                <a href='categories.php?do=Delete&catid=". $c['ID'] ."' class='show-delete confirm'> Delete </a>                            
                                </li>";
                              }
                              echo "</ul>";
                            }
                            
                                 echo "</div>";
                          
                            echo "</div>";
                            echo "<hr>"; 
                        }
                         ?>
        </div>            
        </div>
        <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
        </div>
          <?php 

    } elseif ($do == 'Add') { ?>
        
        <h1 class="text-center"> Add New Category </h1>
    <div class="container">
   <form class="form-horizental" action="?do=Insert" method="POST">
 
    <!-- Start Name field -->

   <div class="row mb-3">
    <label class="col-md-3" >
        Name
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text"  name="name" class="form-control"  autocomplete="off"  required ="required" placeholder="Name Of The Category" />
    </div>
   </div>
   <!-- END Name field -->

      <!-- Start Description field -->

      <div class="row mb-3">
    <label class="col-md-3">
    Description
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text" name="description" class="form-control"  placeholder="Describe The Category"/>
    </div>
   </div>
   <!-- END Description field -->

      <!-- Start Ordering field -->

      <div class="row mb-3">
    <label class="col-md-3">
    Ordering
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text" name="ordering"  class="form-control" placeholder="Number To Arrange The Categories"/>
    </div>
   </div>
   <!-- END Ordering field -->
    <!-- Start Category Type -->
    <div class="row mb-3">
    <label class="col-md-3">
   Parent?
    </label>
    <div class="col-sm-10 col-md-4">
      <select name="parent">
    <option value="0">None</option>
    <?php   
       $allCats = getAllFrom("*" , "categories", "where parent = 0","", "ID", "ASC");

       foreach( $allCats as $cat ) {
        echo  "<option value = '" . $cat['ID'] . "'>" . $cat['Name'] . " </option>";
       }

    ?>
      </select>
    </div>
   </div>
     <!-- End Category Type -->
      <!-- Start Visibility field -->

      <div class="row mb-3">
    <label class="col-md-3">
        Visible
    </label>
    <div class="col-sm-10 col-md-4">
       <div>
        <input id="vis-yes" type="radio" name="visibility" value="0" checked />
        <label for="vis-yes">Yes</label>
       </div>
       <div>
        <input id="vis-no" type="radio" name="visibility" value="1"  />
        <label for="vis-no">No</label>
       </div> 
       
    </div>
   </div>
   <!-- END Visibility field -->
    <!-- Start Commenting field -->

    <div class="row mb-3">
    <label class="col-md-3">
       Allow Commenting 
    </label>
    <div class="col-sm-10 col-md-4">
       <div>
        <input id="com-yes" type="radio" name="commenting" value="0" checked />
        <label for="com-yes">Yes</label>
       </div>
       <div>
        <input id="com-no" type="radio" name="commenting" value="1"  />
        <label for="com-no">No</label>
       </div> 
       
    </div>
   </div>
   <!-- End Commenting  field -->
    <!-- Start Ads field -->

    <div class="row mb-3">
    <label class="col-md-3">
       Allow Ads  
    </label>
    <div class="col-sm-10 col-md-4">
       <div>
        <input id="ads-yes" type="radio" name="ads" value="0" checked />
        <label for="ads-yes">Yes</label>
       </div>
       <div>
        <input id="ads-no" type="radio" name="ads" value="1"  />
        <label for="ads-no">No</label>
       </div> 
       
    </div>
   </div>
   <!-- End Ads  field -->

    <!-- Start Submit field -->

   <div class="row">
        <div class="offset-md-3 col-md-6">
        <input type="submit" value="Add Category" class="btn btn-primary btn-sm"/>
        </div>
   </div>
   <!-- END Submit field -->
</form>
    
</div>

           <?php 

    } elseif ($do == 'Insert'){
 
      if($_SERVER['REQUEST_METHOD'] == 'POST'){

         echo "<h1 class='text-center'>Insert Category </h1>";
         echo "<div class='container'>";

        // Get The Variables From The Form


        $name     =  $_POST['name'];
        $desc     =  $_POST['description'];
        $parent   =  $_POST['parent'];
        $order    =  $_POST['ordering'];
        $visible  =  $_POST['visibility'];
        $comment  =  $_POST['commenting'];
        $ads      =  $_POST['ads'];

 
       // Check If Category Exist in Database

       $check = checkItem("Name", "categories", $name);

       if ($check == 1){

           $theMsg =  '<div class="alert alert-danger"> Sorry This Category Is Exist</div>';
         
          redirectHome($theMsg, 'back');
      }
      else { 

       //Inset Category Info In Database
   
    $stmt = $con->prepare("INSERT INTO 
                         categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
                         VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads) ");

    $stmt->execute(array(
        'zname'    => $name,
        'zdesc'    => $desc,
        'zparent'  => $parent,
        'zorder'   => $order,
        'zvisible' => $visible,
        'zcomment' => $comment,
        'zads'     =>  $ads
    ));

    // Echo success Message

    $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
    redirectHome($theMsg, 'back');    
} 
  

}else{
   echo "<div class='container'>";

    $theMsg  = '<div class="alert alert-danger"> Soory You Cant Browse This Page Directly </div>';

    redirectHome($theMsg, 'back');

 echo "</div>";
}
 
echo "</div>";

    } elseif ($do == 'Edit') { 
    
    // Check IF GET Request catid Is Numeric and Get The Integer Value Of IT

  $catid =isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']):0;
 
  // Select All Data Depend On This ID

  $stmt =  $con->prepare("SELECT * FROM categories WHERE ID = ?");

  //  Execute Query 

   $stmt->execute(array($catid)); 
  
   //  Fetch The Data

   $cat = $stmt->fetch();  
   // The Row Count
   $count =$stmt->rowCount();
   //  IF There's Such ID Show The Form 

    if ($count > 0 ) {
 ?>
 <h1 class="text-center"> Edit Category </h1>
    <div class="container">
   <form class="form-horizental" action="?do=Update" method="POST">
   <input type="hidden" name="catid" value="<?php echo $catid ?>"/>
 
    <!-- Start Name field -->

   <div class="row mb-3">
    <label for="categoryName" class="col-md-3" >
        Name
    </label>
    <div class="col-sm-10 col-md-4">
        <input  type="text"  name="name" class="form-control" id="categoryName"  required ="required" placeholder="Name Of The Category" value="<?php echo $cat['Name'] ?>" />
    </div>
   </div>
   <!-- END Name field -->

      <!-- Start Description field -->

      <div class="row mb-3">
    <label class="col-md-3">
    Description
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text" name="description" class="form-control"  placeholder="Describe The Category" value="<?php echo $cat['Description'] ?>" />
    </div>
   </div>
   <!-- END Description field -->

      <!-- Start Ordering field -->

      <div class="row mb-3">
    <label class="col-md-3">
    Ordering
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="text" name="ordering"  class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering'] ?>" />
    </div>
   </div>
   <!-- END Ordering field -->
    <!-- Start Category Type -->
    <div class="row mb-3">
    <label class="col-md-3">
   Parent?  
    </label>
    <div class="col-sm-10 col-md-4">
      <select name="parent">
    <option value="0">None</option>
    <?php   
       $allCats = getAllFrom("*" , "categories", "where parent = 0","", "ID", "ASC");

       foreach( $allCats as $c ) {
        echo  "<option value = '" . $c['ID'] . "'";
        if ($cat['parent']== $c['ID']) {
          echo 'selected';
        }
        echo ">" . $c['Name'] . " </option>";
       }

    ?>
      </select>
    </div>
   </div>
     <!-- End Category Type -->
      <!-- Start Visibility field -->

      <div class="row mb-3">
    <label class="col-md-3">
        Visible
    </label>
    <div class="col-sm-10 col-md-4">
       <div>
        <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) { echo 'checked' ;} ?> />
        <label for="vis-yes">Yes</label>
       </div>
       <div>
        <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) { echo 'checked' ;} ?> />
        <label for="vis-no">No</label>
       </div> 
       
    </div>
   </div>
   <!-- END Visibility field -->
    <!-- Start Commenting field -->

    <div class="row mb-3">
    <label class="col-md-3">
       Allow Commenting 
    </label>
    <div class="col-sm-10 col-md-4">
       <div>
        <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) { echo 'checked' ;} ?>   />
        <label for="com-yes">Yes</label>
       </div>
       <div>
        <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) { echo 'checked' ;} ?>  />
        <label for="com-no">No</label>
       </div> 
       
    </div>
   </div>
   <!-- End Commenting  field -->
    <!-- Start Ads field -->

    <div class="row mb-3">
    <label class="col-md-3">
       Allow Ads  
    </label>
    <div class="col-sm-10 col-md-4">
       <div>
        <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) { echo 'checked' ;} ?>   />
        <label for="ads-yes">Yes</label>
       </div>
       <div>
        <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) { echo 'checked' ;} ?> />
        <label for="ads-no">No</label>
       </div> 
       
    </div>
   </div>
   <!-- End Ads  field -->
  <!-- Start Submit field -->
    <div class="row">
    <div class="offset-md-3 col-md-6">
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

    } elseif ($do == 'Update') {
    //Update Page
   echo "<h1 class='text-center'> Update Category</h1>";
   echo "<div class='container'>";

   if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Get The Variables From The Form

    $id        =  $_POST['catid'];
    $name      =  $_POST['name'];
    $desc      =  $_POST['description'];
    $order     =  $_POST['ordering'];
    $parent    =  $_POST['parent']; 

    $visible   =  $_POST['visibility'];
    $comment   =  $_POST['commenting'];
    $ads       =  $_POST['ads'];

   
       // Update The DataBase With This Infos

      $stmt = $con->prepare("UPDATE
                                categories 
                            SET 
                               Name = ?, 
                               Description = ?, 
                               Ordering = ?, 
                               parent = ?, 
                               Visibility = ?,
                               Allow_Comment = ?,
                               Allow_Ads = ? 
                            WHERE 
                                ID = ?");
      $stmt->execute(array($name, $desc, $order, $parent, $visible,  $comment, $ads, $id));

       // Echo success Message
 
       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
    
       redirectHome($theMsg, 'back');
 

   }else{

    $theMsg = '<div class="alert alert-danger"> Soory You Cant Browse This Page Directly </div>';

    redirectHome($theMsg);
   }
    
   echo "</div>";
    } elseif ($do == 'Delete'){
        echo "<h1 class='text-center'> Delete Category</h1>";
        echo "<div class='container'>";
     
       // Check IF GET Request Catid Is Numeric and Get The Integer Value Of IT
    
      $catid =isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']):0;
     
      // Select All Data Depend On This ID
    
      
      
      $check = checkItem('ID', 'categories', $catid);
    
     
      
     
       //  IF There's Such ID Show The Form 
     
        if ($check > 0 ) {
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
           
            $stmt->bindParam(":zid", $catid );
           
            $stmt->execute();
           
            $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
           
            redirectHome($theMsg, 'back');
        } else {
            $theMsg =  '<div class="alert alert-danger"> This ID is Not Exist </div>';
    
            redirectHome($theMsg);
        }
        
        echo '</div>';
    } 
    include $tpl . 'footer.php';

   } else {
    header('Location: index.php');

    exit();
   }

   
  ob_end_flush(); //Release The Output

?> 