<?php
    include "functions.php";
    $code = $_GET["code"];
    $startDateTime = $_GET["startDateTime"];
    $endDateTime = $_GET["endDateTime"];
    $tempType = $_GET["tempType"];

    $tempData = getTableDataMultipleCode($code, $startDateTime, $endDateTime, $tempType);

    $data["tempType"] = $tempType;
    $data["tempData"] = $tempData;
    print json_encode($data);
?>
