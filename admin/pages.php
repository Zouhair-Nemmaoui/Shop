<?php


    /* 
    Categories => [ Manage | Edit | Update | Add | Insert | Delete | Stats ]
    */

    $do = '';

    if(isset($_GET['do'])){
        $do = $_GET['do'];
    } else {
        $do = 'Manage';  
    }

    // If The Page Is Main Page 

    if ($do == 'Manage') {
        echo 'Welcom You Are In Manage Category Page';
        echo '<a href="?do=Add">Add New Category +</a>';
    }elseif ($do  == 'Add') {
        echo 'Welcom You Are In Add Category Page';
    }elseif ($do  == 'Insert'){
        echo 'Welcome You Are In Insert Category Page';
    }else {
        echo 'Error There\'s No Page With This Name';
    }