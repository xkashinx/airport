<?php
    include "functions.php";
    $code = $_GET['code'];
    $data = plotWeekByCode($code);

    $T1 = array();
    $T2 = array();
    $T3 = array();
    $T4 = array();
    $dataSeries = array();

    $T1["name"] = "T1";
    $T2["name"] = "T2";
    $T3["name"] = "T3";
    $T4["name"] = "T4";

    $T1["color"] = "#8085e9";
    $T2["color"] = "#f15c80";
    $T3["color"] = "#e4d354";
    $T4["color"] = "#2b908f";

    // Set timezone of data to prevent data points drifting on x(time) axis
    date_default_timezone_set('America/New_York');
    if ($code === "00") {
        for($i = 0; $i < count($data); $i++) {
            $T1["name"] = "Ambient";
            $T1["color"] = "#7cb5ec";
            $T1["data"][$i][0] = strtotime($data[$i][0])."000";
            $T1["data"][$i][1] = $data[$i][1];
        }
        $dataSeries[0] = $T1;
    } else {
        for($i = 0; $i < count($data); $i++) {
            $T1["data"][$i][0] = strtotime($data[$i][0])."000";
            $T1["data"][$i][1] = $data[$i][1];
            $T2["data"][$i][0] = strtotime($data[$i][0])."000";
            $T2["data"][$i][1] = $data[$i][2];
            $T3["data"][$i][0] = strtotime($data[$i][0])."000";
            $T3["data"][$i][1] = $data[$i][3];
            $T4["data"][$i][0] = strtotime($data[$i][0])."000";
            $T4["data"][$i][1] = $data[$i][4];
        }
        $dataSeries[0] = $T1;
        $dataSeries[1] = $T2;
        $dataSeries[2] = $T3;
        $dataSeries[3] = $T4;
    }

    // return JSON
    print json_encode($dataSeries, JSON_NUMERIC_CHECK);
?>