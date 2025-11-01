<?php
 include 'connect.php'; //(ConnectDtabase)  7titu hna 7it  fil init.php kanipmporteh f index.php
 
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

//Include Navbar On All Pages Expect The One With $Nonavbar variable
if (!isset($noNavbar)){include  $tpl . 'navbar.php';}


?>
