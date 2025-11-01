<?php 
  ob_start();
  session_start();
  $pageTitle = 'Login';

  if (isset($_SESSION['user'])){
    header('Location: index.php'); 
}

 include 'init.php'; 

    // Check If User Coming From HTTP Post Request  

  if ($_SERVER['REQUEST_METHOD'] == 'POST' ){

    if(isset($_POST['login'])) {

 
    $user = $_POST['username'];
    $pass = $_POST['password'];
 
  
    $hashedPass = sha1($pass);

   // Check If The User Exist In Database

   $stmt =  $con->prepare("SELECT
                                   UserID, Username, Password 
                           FROM 
                                users 
                           WHERE 
                                Username = ? 
                           AND 
                               Password = ? 
                           ");
   $stmt->execute(array( $user, $hashedPass));  
   
   $get = $stmt->fetch();

   $count =$stmt->rowCount();
   
  // IF Count > 0 This Man the Database Conatin Record About This Username
  if ($count > 0 ){
    $_SESSION['user'] =  $user; //Register Session Name
   
    $_SESSION['uid'] = $get['UserID']; // Register User ID in Session

    header('Location: index.php'); //Redirect To Dashboard Page
   
   exit(); 
  }
} else { 
           $formErrors = array();

          $username  = $_POST['username'];

          $password  = $_POST['password'];
           
          $password2 = $_POST['password2'];

          $email     = $_POST['email'];

           if (isset($username)){
           
         $filteredUser = strip_tags($username);
          
        if (strlen( $filteredUser < 4)){
           $formErrors[] = 'Username Must be Larger than 4 Characters'; 
        }
          }

           if (isset($password) && isset($password2 )) {
        
           if (empty($password)){
            
              $formErrors[] ='Sorry Password Cant Be Empty ';

             } 

             if ( sha1($password) !== sha1($password2)){
            
              $formErrors[] ='Sorry Password Is Not Match';

             }
           
           
          }

           
           if (isset($email)){

      $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
           
          if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true ) {
         $formErrors[] = 'This Email Is Not Valid';          } 
          }
          
          // Check IF There's No Error Proceed The User Add
 
     if(empty($formErrors )){

        // Check If User Exist in Database

        $check = checkItem("Username", "users", $username);

        if ($check == 1){

          $formErrors[] = 'Sorry This User Is Exists ';
             
        }else { 

        //Inset UserInfo In Database
       
        $stmt = $con->prepare("INSERT INTO 
                             users(Username, Password, Email, RegStatus, Date)
                             VALUES(:zuser, :zpass, :zmail, 0, now())");

        $stmt->execute(array(
            'zuser' => $username ,
            'zpass' => sha1($password) ,
            'zmail' => $email
        ));
        // Echo success Message
  
        $succesMsg = 'Congrate You Are Now Registred User';
 
    } 
        }


}

}


?>   

    <div class="container login-page">
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> 
         <h1 class="text-center">
             <span class="selected" data-class="login">Login</span>|<span data-class="signup">Signup</span>
       </h1> 
          <input 
          class="form-control"
          type="text"
          name="username" 
          autocomplete="off" 
          placeholder="Type your username"
          required/>
          <input 
          class="form-control"
           type="password" 
           name="password" 
           autocomplete="new-password"
           placeholder="Type your password" />
          <input class="btn btn-primary w-100" name="login" type="submit" value="Login" />

      </form>
      <!-- End Login Form -->
       <!-- Start signup Form -->
            <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
         <h1 class="text-center"> 
           <span  data-class="login">Login</span>|<span class="selected" data-class="signup">Signup</span>
       </h1> 
          <input 
          pattern=".{4,}"
          title="Username Must Be Between 4"
          class="form-control"
          type="text"
          name="username" 
          autocomplete="off" 
          placeholder="Type your username"
          required/>

          <input 
           minlength="4"
           class="form-control"
           type="password" 
           name="password" 
           autocomplete="new-password"
           placeholder="Type a Complex password" 
           required/>
           
           <input 
           minlength="4"
          class="form-control"
           type="password" 
           name="password2" 
           autocomplete="new-password"
           placeholder="Type a password again" 
           required/>

             <input 
          class="form-control"
           type="email" 
           name="email" 
           placeholder="Type a Valid email"
           required />
          <input class="btn btn-success w-100" name="signup" type="submit" value="Signup" />

      </form>
    <div class="the-errors text-center">
  <?php  
         if (!empty($formErrors)) {
          foreach ($formErrors as $error) {
            echo '<div class ="msg error"> '. $error . '</div>';
          }
         } 

         if (isset($succesMsg)) {
          echo '<div class ="msg success">' . $succesMsg. '</div>';
         }

  ?>   
      </div> 
    </div>
          <!-- End signup Form -->
<?php 
   include $tpl . 'footer.php';  
   ob_end_flush();
?>
