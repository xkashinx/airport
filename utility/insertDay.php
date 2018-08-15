<?php

    //$startDate = "2016-11-03";
    //$endDate = "2016-11-10";

    $startDate = date("Y-m-d", strtotime("-6 day", strtotime(date("Y-m-d"))));
    $endDate = date("Y-m-d");

    include "../connection.php";

    $query = "DELETE FROM `LED_Measurements`";
    if (mysqli_query(connect(), $query)) {
        echo "Old data has been deleted! <br>";
    }

    $temp = array();
    $temp[0]=53;
    $temp[1]=52;
    $temp[2]=51;
    $temp[3]=50;
    $temp[4]=49;
    $temp[5]=47;
    $temp[6]=49;
    $temp[7]=52;
    $temp[8]=55;
    $temp[9]=58;
    $temp[10]=62;
    $temp[11]=66;
    $temp[12]=69;
    $temp[13]=71;
    $temp[14]=71;
    $temp[15]=73;
    $temp[16]=70;
    $temp[17]=69;
    $temp[18]=67;
    $temp[19]=66;
    $temp[20]=62;
    $temp[21]=58;
    $temp[22]=55;
    $temp[23]=54;

    $tempc=array();
    $tempc[0]=54;
    $tempc[1]=53;
    $tempc[2]=53;
    $tempc[3]=52;
    $tempc[4]=53;
    $tempc[5]=53;
    $tempc[6]=53;
    $tempc[7]=54;
    $tempc[8]=55;
    $tempc[9]=57;
    $tempc[10]=59;
    $tempc[11]=62;
    $tempc[12]=63;
    $tempc[13]=65;
    $tempc[14]=66;
    $tempc[15]=67;
    $tempc[16]=67;
    $tempc[17]=66;
    $tempc[18]=66;
    $tempc[19]=66;
    $tempc[20]=65;
    $tempc[21]=63;
    $tempc[22]=60;
    $tempc[23]=57;

    function selectTemp ($sunnyTemp, $cloudyTemp) {
        $tempChoice = rand(1, 50);
        if($tempChoice > 25) {
            $selectedTemp = $sunnyTemp;
        } else {
            $selectedTemp = $cloudyTemp;
        }
        return $selectedTemp;
    }

    addDataByCode("00", $startDate, $endDate, $temp, $tempc);
    for ($n = 11; $n < 20; $n++) {
        addDataByCode ((string)$n, $startDate, $endDate, $temp, $tempc);
    }
    for ($n = 21; $n < 30; $n++) {
        addDataByCode ((string)$n, $startDate, $endDate, $temp, $tempc);
    }

    function addDataByCode ($code, $startDate, $endDate, $sunnyTemp, $cloudyTemp) {
        $link = connect();
        $count = 0;
        $date = $startDate;
        do {
            $randDay = rand(-199, 200)/100;
            $temperature = selectTemp($sunnyTemp, $cloudyTemp);
            for ($i = 0; $i < 24; $i++) {
                for ($j = 0; $j < 6; $j++) {
                    if($code !== "00") {
                        if($i < 10) {
                            $query = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`, `t2`, `t3`, `t4`) VALUES('".$date."','0".$i.":".$j."0:00','".$code."','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+2+(rand(0,100)/50)+$randDay)."')";
                            $query2 = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`, `t2`, `t3`, `t4`) VALUES('".$date."','0".$i.":".$j."5:00','".$code."','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+2+(rand(0,100)/50)+$randDay)."')";
                        } else {
                            $query = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`, `t2`, `t3`, `t4`) VALUES('".$date."','".$i.":".$j."0:00','".$code."','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+2+(rand(0,100)/50)+$randDay)."')";
                            $query2 = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`, `t2`, `t3`, `t4`) VALUES('".$date."','".$i.":".$j."5:00','".$code."','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+1+(rand(0,100)/50)+$randDay)."','".($temperature[$i]+2+(rand(0,100)/50)+$randDay)."')";
                        }
                    } else {
                        if($i < 10) {
                            $query = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`) VALUES('".$date."','0".$i.":".$j."0:00','00','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."')";
                            $query2 = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`) VALUES('".$date."','0".$i.":".$j."5:00','00','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."')";
                        } else {
                            $query = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`) VALUES('".$date."','".$i.":".$j."0:00','00','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."')";
                            $query2 = "INSERT INTO `LED_Measurements` (`date`, `time`, `code`, `t1`) VALUES('".$date."','".$i.":".$j."5:00','00','".($temperature[$i]-1+(rand(0,100)/50)+$randDay)."')";
                        }
                    }

                    if(mysqli_query($link, $query)) {
                        $count++;
                    } else {
                        echo "Failed to add points: ".mysqli_error($link)."<br />";
                    }

                    if(mysqli_query($link, $query2)) {
                        $count++;
                    } else {
                        echo "Failed to add points: ".mysqli_error($link)."<br />";
                    }
                    
                }
            }
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        } while ($date !== date("Y-m-d", strtotime("+1 day", strtotime($endDate))));
        echo "Finished! <br />";
        echo "Entered ".$count." data points for code = ".$code." on ".$startDate." to ".$endDate."<br />";
    }
