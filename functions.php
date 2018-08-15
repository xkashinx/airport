<?php
    /* connection.php has function connect() which returns the $link */
    include "connection.php";

    /* check if mobile device */
    function mobile () {
        $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
        $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");

        return ($iphone || $android || $palmpre || $ipod || $berry || $ipad == true);
    }

    /*
     * Get date of most recent data entry
     *
     * @return $lastDate
     */
    function lastDate () {
        $link = connect();
        $query = "SELECT
                    date
                  FROM 
                    LED_Measurements
                  ORDER BY
                    date DESC 
                  LIMIT 1";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $lastDate = $row["date"];
        return $lastDate;
    }

    /**
     * Get one week data for a code
     * @param $code
     * @return $data
     */
    function plotWeekByCode ($code) {
        $link = connect();
        $lastDate = lastDate();
        $firstDate = date("Y-m-d", strtotime("-6 day", strtotime($lastDate)));
        $query = "SELECT `date`, `time`, `t1`, `t2`, `t3`, `t4` FROM LED_Measurements WHERE `code` = '".$code."' AND `date` BETWEEN '".$firstDate."' AND '".$lastDate."' ORDER BY date ASC, time ASC";
        $result = mysqli_query($link, $query);
        $i = 0;

        while ($row = mysqli_fetch_array($result)) {
            $date = $row["date"];
            $time = $row["time"];
            $data[$i][0] = $date." ".$time;
            $data[$i][1] = $row["t1"];
            if($code !== "00") {
                $data[$i][2] = $row["t2"];
                $data[$i][3] = $row["t3"];
                $data[$i][4] = $row["t4"];
            }
            $i++;
        }
        return $data;
    }

    /*
     * Get MySQL data for table with multiple code
     *
     * @param $code --> array
     * @param $startDateTime
     * @param $endDateTime
     * @param $tempType --> array of possibly T1~T4
     *
     * @return $data[entry#][
     *  0: Date
     *  1: Time
     *  2: Code
     *  3~6: Temperatures
     * ]
     */
    function getTableDataMultipleCode($code, $startDateTime, $endDateTime, $tempType) {
        $typeSelectorString = "`".strtolower($tempType[0])."`";
        for ($i = 1; $i < count($tempType); $i++) {
            $typeSelectorString = $typeSelectorString.", `".strtolower($tempType[$i])."`";
        }
        $typeSelectorString = $typeSelectorString." ";
        $codeSelectorString = "(";
        $i = 0;
        while ($i < count($code) - 1) {
            $codeSelectorString = $codeSelectorString." `code` = '".$code[$i]."' OR";
            $i++;
        }
        $codeSelectorString = $codeSelectorString." `code` = '".$code[$i]."')";
        $link = connect();
        $query = "SELECT 
                        `date`,
                        `time`, 
                        `code`,
                      ".$typeSelectorString."
                      FROM 
                        LED_Measurements 
                      WHERE 
                        ".$codeSelectorString." AND 
                        TIMESTAMP(`date`, `time`) BETWEEN '".$startDateTime."' AND '".$endDateTime."' 
                        ORDER BY date ASC, time ASC, code ASC";
        $result = mysqli_query($link, $query);
        $typeSize = count($tempType);
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $data[$i][0] = $row["date"]; //date
            $time = substr($row["time"], 0, 5); //get H:i format time
            $data[$i][1] = $time; //time
            $returnedCode = $row["code"];
            if ($returnedCode === "00") {
                $data[$i][2] = "Ambient";
            } else {
                $data[$i][2] = "Slab ".$returnedCode[0].", LED ".$returnedCode[1];
            }
            for ($j = 0; $j < $typeSize; $j++) {
                if($row[ $j + 3] === NULL) {
                    $data[$i][ $j+3 ] = "N/A"; //temperature reading null case
                } else {
                    $data[$i][ $j+3 ] = $row[ $j+3 ]; //temperature reading
                }
            }
            $i++;
        }
        return $data;
    }

    /**
     * get custom chart data for arbitrary codes and dates
     *
     * @param $code
     * @return $data
     */
    function getChartDataMultipleCode ($code, $startDateTime, $endDateTime, $tempType) {
        $typeSelectorString = "`".strtolower($tempType[0])."`";
        for ($i = 1; $i < count($tempType); $i++) {
            $typeSelectorString = $typeSelectorString.", `".strtolower($tempType[$i])."`";
        }
        $typeSelectorString = $typeSelectorString." ";
        $link = connect();
        $typeSize = count($tempType);
        $data = array();
        $seriesOffset = 0;
        date_default_timezone_set("America/New_York");
        for ($i = 0; $i < count($code); $i++) {
            if ($code[$i] === "00") {
                for ($j = 0; $j < $typeSize; $j++) {
                    $data[$seriesOffset + $j]["name"] = "Ambient ".$tempType[$j];
                }
            } else {
                for ($j = 0; $j < $typeSize; $j++) {
                    $data[$seriesOffset + $j]["name"] = "Slab ".$code[$i][0].", LED ".$code[$i][1]." ".$tempType[$j];
                }
            }

            $query = "SELECT
                        `date`,
                        `time`,
                        `code`,
                        ".$typeSelectorString."
                      FROM
                        LED_Measurements
                      WHERE
                        `code` = '".$code[$i]."' AND
                        TIMESTAMP(`date`, `time`) BETWEEN '".$startDateTime."' AND '".$endDateTime."'
                        ORDER BY date ASC, time ASC";
            $result = mysqli_query($link, $query);
            $entryOffset = 0;
            while ($row = mysqli_fetch_array($result)) {
                for ($j = 0; $j < $typeSize; $j++) {
                    $datetime = $row["date"]." ".$row["time"];
                    $data[$seriesOffset + $j]["data"][$entryOffset][0] = (int) (strtotime($datetime)."000");
                    if($row[strtolower( $tempType[$j] )] !== NULL) {
                        $data[$seriesOffset + $j]["data"][$entryOffset][1] = (double) $row[ strtolower( $tempType[$j] ) ];
                    } else {
                        $data[$seriesOffset + $j]["data"][$entryOffset][1] = NULL;
                    }
                }
                $entryOffset++;
            }
            $seriesOffset = $seriesOffset + $typeSize;
        }
        return $data;
    }
?>
