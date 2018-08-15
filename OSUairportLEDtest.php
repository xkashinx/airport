<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>OSU Airport LED Test</title>
        
        <!-- jQuery CDN -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- HighChart.js CDN -->
        <script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
        <!-- HighCharts Exporting.js CDN -->
        <script type="text/javascript" src="https://code.highcharts.com/modules/exporting.js"></script>
        <!-- datetime picker-->
        <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
        <!-- DataTable CDN -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>

        <!-- Custom stylesheet -->
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    
    <body>

        <div id="navigation">
            <a class="navigation-tab selected-tab" href="OSUairportLEDtest.php">
                <div class="tab-text">Measurements</div>
            </a>
            <a class="navigation-tab" href="/error/errorTable.php">
                <div class="tab-text">Warnings</div>
            </a>
        </div>
    
    
        <div class="spacing"></div>
        
        <!-- Ambient Chart -->
        <div class="plot" id="ambientChart"></div>
        
        <div class="spacing"></div>
        
        <!-- Slab Chart -->
        <div class="dynamicChartDiv" id="hiddenDiv">
            <hr class="hiddenHr"/>
            <div class="spacing"></div>
            <div class="plot dynamicChart" id="customChart"></div>
            <div class="closeDynamicBtn" id="closePlotBtn">Close</div>
            <div class="spacing"></div>
            <hr class="hiddenHr" />
        </div>
        

        <div class="spacing"></div>
        <div id="slabPrompt">Click on LED number to see the data collected in the past 7 days.</div>
        <div id="slabField">
            <div id="slabWrapper">
                <div class="slabTitle">Slab 1 (Concrete)</div>
                <div class="slabTitle">Slab 2 (Asphalt)</div>
                <div class="slab" id="concreteSlab">
                    <div class="slabElement" id="slabCode_11">1</div>
                    <div class="slabElement" id="slabCode_12">2</div>
                    <div class="slabElement" id="slabCode_13">3</div>
                    <div class="slabElement" id="slabCode_14">4</div>
                    <div class="slabElement" id="slabCode_15">5</div>
                    <div class="slabElement" id="slabCode_16">6</div>
                    <div class="slabElement" id="slabCode_17">7</div>
                    <div class="slabElement" id="slabCode_18">8</div>
                    <div class="slabElement" id="slabCode_19">9</div>
                </div>
                <div class="slab" id="asphaltSlab">
                    <div class="slabElement" id="slabCode_21">1</div>
                    <div class="slabElement" id="slabCode_22">2</div>
                    <div class="slabElement" id="slabCode_23">3</div>
                    <div class="slabElement" id="slabCode_24">4</div>
                    <div class="slabElement" id="slabCode_25">5</div>
                    <div class="slabElement" id="slabCode_26">6</div>
                    <div class="slabElement" id="slabCode_27">7</div>
                    <div class="slabElement" id="slabCode_28">8</div>
                    <div class="slabElement" id="slabCode_29">9</div>
                </div>
            </div>
        </div>
        <div class="spacing"></div>
        <div class="spacing"></div>

        <!-- Form for Table -->
        <div class="formDynamic" id="formTable">
            <h2 align="center">Generate Custom Table for Measurements</h2>
            <form id="tableForm" action="tableData.php" method="get">
                <ul class="form-style">
                    <li>
                        <label>Measurement From</label>
                        <input type="checkbox" name="code[]" value="00"> Ambient <br />
                        <label></label>
                        Slab 1: <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="11"> LED 1
                        <input type="checkbox" name="code[]" value="12"> LED 2
                        <input type="checkbox" name="code[]" value="13"> LED 3
                        <input type="checkbox" name="code[]" value="14"> LED 4
                        <input type="checkbox" name="code[]" value="15"> LED 5 <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="16"> LED 6
                        <input type="checkbox" name="code[]" value="17"> LED 7
                        <input type="checkbox" name="code[]" value="18"> LED 8
                        <input type="checkbox" name="code[]" value="19"> LED 9 <br />
                        <div style="height:12px;"></div>
                        <label></label>
                        Slab 2: <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="21"> LED 1
                        <input type="checkbox" name="code[]" value="22"> LED 2
                        <input type="checkbox" name="code[]" value="23"> LED 3
                        <input type="checkbox" name="code[]" value="24"> LED 4
                        <input type="checkbox" name="code[]" value="25"> LED 5 <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="26"> LED 6
                        <input type="checkbox" name="code[]" value="27"> LED 7
                        <input type="checkbox" name="code[]" value="28"> LED 8
                        <input type="checkbox" name="code[]" value="29"> LED 9
                    </li>
                    <div class="warning code_warning"></div>
                    <li>
                        <label for="startDateTime">Start Date and Time</label>
                        <input type="text" class="dateTime" name="startDateTime" id="startDateTime" /><br />
                        <div class="warning startDateTime_warning"></div>
                        <label for="endDateTime">End Date and Time</label>
                        <input type="text" class="dateTime" name="endDateTime" id="endDateTime" />
                        <div class="warning endDateTime_warning"></div>
                    </li>
                    <li>
                        <label for="readings[]">Temperature Type</label>
                        <input type="checkbox" name="tempType[]" value="T1"> T1
                        <input type="checkbox" name="tempType[]" value="T2"> T2
                        <input type="checkbox" name="tempType[]" value="T3"> T3
                        <input type="checkbox" name="tempType[]" value="T4"> T4
                    </li>
                    <div class="warning tempType_warning" style="margin-bottom: 6px;"></div>
                    <li>
                        <button type="submit">Generate Table</button>
                    </li>
                </ul>
            </form>
        </div>

        <div id="tableWarning">
            Warning: Safari cannot export excel. Please use other browsers to export excel.
        </div>

        <div id="tableDiv">
            <table id="exampleTable" class="display" cellspacing="0">
                <thead>

                </thead>
            </table>
                <tbody>
                
                </tbody>
        </div>

        <div class="spacing"></div>

        <!-- Slab Chart -->
        <div class="dynamicChartDiv" id="hiddenDiv2">
            <hr class="hiddenHr"/>
            <div class="spacing"></div>
            <div class="plot dynamicChart" id="formChart"></div>
            <div class="closeDynamicBtn" id="closePlotBtn2">Close</div>
            <div class="spacing"></div>
            <hr class="hiddenHr"/>
        </div>

        <div class="spacing"></div>

        <!-- Form for Chart -->
        <div class="formDynamic" id="formForChart">
            <h2 align="center">Generate Custom Plot for Measurements</h2>
            <form id="chartForm" action="chartData.php" method="get">
                <ul class="form-style">
                    <li>
                        <label>Measurement From</label>
                        <input type="checkbox" name="code[]" value="00"> Ambient <br />
                        <label></label>
                        Slab 1: <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="11"> LED 1
                        <input type="checkbox" name="code[]" value="12"> LED 2
                        <input type="checkbox" name="code[]" value="13"> LED 3
                        <input type="checkbox" name="code[]" value="14"> LED 4
                        <input type="checkbox" name="code[]" value="15"> LED 5 <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="16"> LED 6
                        <input type="checkbox" name="code[]" value="17"> LED 7
                        <input type="checkbox" name="code[]" value="18"> LED 8
                        <input type="checkbox" name="code[]" value="19"> LED 9 <br />
                        <div style="height:12px;"></div>
                        <label></label>
                        Slab 2: <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="21"> LED 1
                        <input type="checkbox" name="code[]" value="22"> LED 2
                        <input type="checkbox" name="code[]" value="23"> LED 3
                        <input type="checkbox" name="code[]" value="24"> LED 4
                        <input type="checkbox" name="code[]" value="25"> LED 5 <br />
                        <label></label>
                        <input type="checkbox" name="code[]" value="26"> LED 6
                        <input type="checkbox" name="code[]" value="27"> LED 7
                        <input type="checkbox" name="code[]" value="28"> LED 8
                        <input type="checkbox" name="code[]" value="29"> LED 9
                    </li>
                    <div class="warning code_warning"></div>
                    <li>
                        <label for="startDateTime">Start Date and Time</label>
                        <input type="text" class="dateTime" name="startDateTime" id="startDateTime" /><br />
                        <div class="warning startDateTime_warning"></div>
                        <label for="endDateTime">End Date and Time</label>
                        <input type="text" class="dateTime" name="endDateTime" id="endDateTime" />
                        <div class="warning endDateTime_warning"></div>
                    </li>
                    <li>
                        <label for="readings[]">Temperature Type</label>
                        <input type="checkbox" name="tempType[]" value="T1"> T1
                        <input type="checkbox" name="tempType[]" value="T2"> T2
                        <input type="checkbox" name="tempType[]" value="T3"> T3
                        <input type="checkbox" name="tempType[]" value="T4"> T4
                    </li>
                    <div class="warning tempType_warning" style="margin-bottom: 6px;"></div>
                    <li>
                        <button type="submit" id="button-chart">Generate Plot</button>
                    </li>
                </ul>
            </form>
        </div>

        <div class="spacing"></div>
        <div id="go-top-div">Back to Top</div>
        <div class="spacing"></div>


        <!-- main.js -->
        <script type="text/javascript" src="js/main.js"></script>
        <!-- Datetime picker -->
        <script type="text/javascript" src="js/jquery.datetimepicker.full.min.js"></script>
        <!-- DataTables CDN -->
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
        <!-- DataTable export CDN -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" />
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    </body>
</html>
