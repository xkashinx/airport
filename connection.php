<?php
/**
 * Created by PhpStorm.
 * User: jiachensun
 * Date: 10/3/16
 * Time: 1:43 PM
 */

    /**
     * initialize connection
     * @return mysqli connection
     */
    function connect() {
        $link = mysqli_connect("localhost", "root", "", "LED_Measurements");
        if ($link) {
            
        } else {
            die ("Connection failed: " . mysqli_connect_error());
        }
        return $link;
    }
        

?>