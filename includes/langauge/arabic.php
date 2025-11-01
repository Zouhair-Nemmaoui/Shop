<?php

     function lang ($phrase) { 
        static $lang = array (
         // HomePage

           'MESSAGE' => 'Welcome In Arabic ',
           'ADMIN' => 'Arabic Admin'
        );
        return  $lang[$phrase];

     }