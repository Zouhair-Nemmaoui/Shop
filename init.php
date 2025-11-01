<?php
   // Error Reporting

   ini_set('display_errors', 'On');
   error_reporting(E_ALL);
 include 'admin/connect.php'; //(ConnectDtabase)  7titu hna 7it  fil init.php kanipmporteh f index.php
 
   $sessionUser = '';
   if (isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
   }
// Routes
 $tpl = 'includes/template/'; //Template Directory
 $lang = 'includes/langauge/'; // Language Directory
 $func = 'includes/functions/'; // Function Derectrory
 $css = 'layouts/css/'; //  CSS Directory
 $js = 'layouts/js/'; //  JS Directory


//Include The Important Files
include $func . 'function.php';
include $lang . 'eng.php';
include  $tpl . 'header.php';


?>
