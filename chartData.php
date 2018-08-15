<?php
/**
 * 
 */
    include "functions.php";
    $code = $_GET["code"];
    $startDateTime = $_GET["startDateTime"];
    $endDateTime = $_GET["endDateTime"];
    $tempType = $_GET["tempType"];

    $data = getChartDataMultipleCode($code, $startDateTime, $endDateTime, $tempType);

    print json_encode($data);
?>