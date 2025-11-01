<?php 
    
  session_start();

  $pageTitle = 'Create New Item';

  include 'init.php';

  if(isset($_SESSION['user'])){
  

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
      
      // Upload Variables

      $itemName = $_FILES['item']['name'];
      $itemSize = $_FILES['item']['size']; 
      $itemTmp  = $_FILES['item']['tmp_name'];
      $itemTyp  = $_FILES['item']['type'];
      
      
   // List Of Allowed File Types To Upload

   $itemAllowedExtension = array("jpeg", "jpg", "png", "gif");
   
   // Get Item Extension 
   $itemExtension = strtolower(pathinfo($itemName, PATHINFO_EXTENSION));

   

     $formErrors = array();
     
     $name     = strip_tags($_POST['name']);
     $desc     = strip_tags($_POST['description']);
     $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
     $country  = strip_tags($_POST['country']);
     $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
     $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
     $tags     = strip_tags($_POST['tags']);

    
   if(strlen($name) < 4) {
    $formErrors[] = 'Item Title Must Be At Least 4 Characters';
   }
     if(strlen($desc) < 10) {
    $formErrors[] = 'Item Description Must Be At Least 10 Characters';
   }
     if(strlen($country) < 2) {
    $formErrors[] = 'Item Title Must Be At Least 2 Characters';
   } 
     if(empty($price)) {
    $formErrors[] = 'Item Price Must Be Not Empty';
   }
    if(empty($status)) {
    $formErrors[] = 'Item Status Must Be Not Empty';
   }
    if(empty($category)) {
    $formErrors[] = 'Item Category Must Be Not Empty';
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
   if(empty($formErrors )){

      $item = rand(0, 100000) . '_' . $itemName;
        
        move_uploaded_file($itemTmp,"admin\uploads\items\\". $item );
 
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
            'zcat'     => $category,
            'zmember'  => $_SESSION['uid'],
             'zitems'   => $item,
            'ztags'    =>  $tags  
        ));
        // Echo success Message
       if($stmt){
        $succesMsg = 'Item Has Been Added';  
       }
      
 
        }
  }  
?>

  <h1 class="text-center"><?php echo $pageTitle  ?></h1>
  <div class="create-ad block">
  <div class="container">
    <div class="card border-primary mb-3">
      <div class="card-header bg-primary text-white">
        <?php echo $pageTitle  ?>
      </div>
      <div class="card-body">
           <div class="row">
               <div class="col-md-8">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="row g-3 align-items-center main-form" enctype="multipart/form-data">

  <!-- Name field -->
  <div class="col-md-12 row align-items-center mt-3">
    <label for="name" class="col-md-2 col-form-label text-end">Name</label>
    <div class="col-md-10">
      <input
          pattern=".{4,}"
          title="This Field Require At Least 4 Characters" 
          type="text" 
          class="form-control live" 
          id="name" 
          name="name" 
          placeholder="Name of The Item" 
          data-class=".live-title" 
          required  >
    </div>
  </div>

  <!-- Description field -->
  <div class="col-md-12 row align-items-center mt-3">
    <label for="description" class="col-md-2 col-form-label text-end">Description</label>
    <div class="col-md-10">
      <input 
            pattern=".{10,}"
             title="This Field Require At Least 10 Characters" 
            type="text" 
            class="form-control live" 
            id="description" 
            name="description" 
            placeholder="Description of The Item" 
            data-class=".live-desc" 
             required>
    </div>
  </div>

  <!-- Price field -->
  <div class="col-md-12 row align-items-center mt-3">
    <label for="price" class="col-md-2 col-form-label text-end">Price</label>
    <div class="col-md-10">
      <input 
            type="number" 
            step="0.01" 
            class="form-control live" 
            id="price" 
            name="price" 
            placeholder="Price of The Item" 
            data-class=".live-price" 
            required>
    </div>
  </div>

  <!-- Country field -->
  <div class="col-md-12 row align-items-center mt-3">
    <label for="country" class="col-md-2 col-form-label text-end">Country</label>
    <div class="col-md-10">
      <input 
           type="text" 
           class="form-control" 
           id="country" 
           name="country" 
           placeholder="Country of Made" 
           required>
    </div>
  </div>

  <!-- Status field -->
  <div class="col-md-12 row align-items-center mt-3">
    <label for="status" class="col-md-2 col-form-label text-end">Status</label>
    <div class="col-md-10">
      <select name="status" id="status" class="form-select" required>
        <option value="">...</option>
        <option value="1">New</option>
        <option value="2">Like New</option>
        <option value="3">Used</option>
        <option value="4">Old</option>
      </select>
    </div>
  </div>

 <!-- Start Category field -->
<div class="col-md-12 row align-items-center mt-3">
    <label for="category" class="col-md-2 col-form-label text-end">Category</label>
    <div class="col-md-10">
        <select name="category" id="category" class="form-select" required>
            <option value="">...</option>
            <?php
            $cats = getAllFrom('*', 'categories', '', '', 'ID');
            foreach ($cats as $cat) {
                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
            }
            ?>
        </select>
    </div>
</div>
<!-- End Category field -->
    <div class="col-md-12 row align-items-center mt-3">
    <label class="col-md-2 col-form-label text-end">
     Items 
    </label>
    <div class="col-sm-10 col-md-4">
        <input type="file" name="item"  class="form-control"  required ="required" />
    </div>
   </div>
<!-- Start Tags field -->
<div class="col-md-12 row align-items-center mt-3">
    <label for="tags" class="col-md-2 col-form-label text-end">Tags</label>
    <div class="col-md-10">
        <input 
            type="text" 
            name="tags" 
            id="tags"
            class="form-control" 
            placeholder="Separate Tags With Comma (,)" 
        />
    </div>
</div>
<!-- End Tags field -->
  <!-- Submit field -->
  <div class="col-md-12 row mt-3">
    <div class="offset-md-2 col-md-10">
      <input  type="submit" value="Add Item"  class="btn btn-primary btn-sm">
</div>
</div>
</form>
</div>
       
        </div>
        <!-- Start Loopiong Through Errors -->
       <?php
        if (! empty($formErrors)) {
          foreach ($formErrors as $error) {
            echo '<div class = "alert alert-danger">' . $error . '</div>' ;
          }
        }
        if (isset($succesMsg)) {
          echo '<div class ="alert alert-success">' . $succesMsg. '</div>';
         }
 
       
         ?>
     
       <!-- End Loopiong Through Errors -->
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