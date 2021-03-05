<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Game_model');
        
        //$this -> is_logged_in();
    }
    /*function is_logged_in() {
        $is_logged_in = $this -> session -> userdata('is_logged_in');
        $person_cat_id = $this -> session -> userdata('person_cat_id');
        if (!isset($is_logged_in) || $is_logged_in != '1' || $person_cat_id!=3) {
            echo 'you have no permission to use this area'. '<a href="#!" ng-click="goToFrontPage()">Login</a>';
            die();
        }
    }*/
    
    function get_sessiondata(){
        echo json_encode($this->session->userdata(),JSON_NUMERIC_CHECK);
    }
    

    function get_active_terminal_balance(){
        $terminal_id=$this-> session -> userdata('person_id');
        $result=$this->Game_model->select_terminal_balance($terminal_id);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }




    public function angular_view_play(){
        ?>
        <style>
            .report-table tr th,.report-table tr td{
                border: 1px solid white !important;
                font-size: 18px;
                line-height: 0px;
                white-space:nowrap;
                
            }
            .report-table tr th{
                /*padding: 25px;*/
                border-top-left-radius: 15px;
                border-top-right-radius: 15px;
            }
            .border-less {
                border-style:hidden!important;
            }

            .joditextBoxClass {
                width: 85px;
                height: 20px;
                text-align: center;
                font-weight: bold;
                font-family: Verdana, Arial, Helvetica, sans-serif;
            }
            .textBoxClass {
                width: 45px;
                height: 20px;
                text-align: center;
                font-weight: bold;
                font-family: Verdana, Arial, Helvetica, sans-serif;
            }
            

            .footer {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: red;
                color: white;
                text-align: center;
            }
            .card{
                /*height: 100vh;*/
            }
            .card-footer{
              /* height: 15vh;*/
                font-size: 9px;
                line-height: 18px;
            }
            .submit a {
                color: white;
                padding: 5px 11px;
                border-style: 2px solid
            }
           /* .result-list{
				 height: 150px;       /* Just for the demo          */
    			overflow-y: auto;    /* Trigger vertical scroll    */
			}*/
			
			.table-style{
				border: 1px solid black !important;
                font-size: 12px;
			}

            #heading-style{
                color:red;
                background-color:#ffff00a8;
            }
            .inputBox{
                height: 35px;
                width:  50px;
                background-color:#f5f5f5;
                border:1px solid #aaa;
                margin: 5px 4px 6px 0;
            }

            .inputTotalBox{
                height: 35px;
                width:  70px;
                background-color:#f5f5f5;
                border:1px solid #aaa;
                margin: 5px 4px 6px 0;
            }
            .game-name{
                color:#990000;
                border-radius:5px;
            }
            
            .result-style{
                /*font-family: "Lobster", cursive;*/
                text-shadow: 3px 0 #232931;
                font-size: 3.5rem;
                padding: 30px;
            }


           
		
            
        </style>
        <div id="pagewrap" style="border: 1px solid black;">       
        
        

            <div class="row col-xs-12 col-md-12 col-lg-12 col-sm-12"  id="content">
                <div class="col-xs-12 col-md-12 col-lg-12 col-sm-12  pt-0 pb-0">
                    	
                        <style>
                        
                            #result-table{
                                background-color:#990000;
                                color:white;
                                font-size:x-large;
                            }

                            .footerContent{
                                background-color: #990000;
                                font-size: 10px;
                                line-height: 1.2;
                                color: antiquewhite;
                            }
                            #rcorners1 {
                                border-radius: 25px;
                                background: #73AD21;
                                padding: 20px; 
                                width: 200px;
                                height: 150px;  
                            }
                            .result-page{
                                background-repeat: no-repeat;
                                 background-position: center; 
                                 background-image:url(img/game_bg.png);
                            }
                            .result-in-top{
                                background-repeat: no-repeat;
                                background-position: center; 
                                background-image:url(img/res_bg.png); 
                                background-size: 55px 45px;
                                width:55px; height:45px; font-size:20pt; color: rgb(255,0,0);
                            }
                            .group-box{
                                padding: 7px 11px;
                                text-transform: uppercase;
                                word-wrap: break-word;
                                white-space: normal;
                                cursor: pointer;
                                border: 0;
                                border-radius: .125rem;
                                box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
                                transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out,-webkit-box-shadow 0.15s ease-in-out;
                                font-size: .81rem;
                            }
                            .upper-romm-number{
                                font-weight: bold;
                            }
                        </style>

                            <table class="table-responsive" style="width:100%;background-color: green">
                                <tbody>
                                    <tr>
                                        <div class="d-flex flex-column font-weight-bold" style="background-color:green;font-size:30px;color:white"> <marquee style="font-weight:1000">{{scrollingMsg.message}}</marquee></div>
                                        <td><img class="img-responsive" style="width:200px;height:150px" src="img/national_lottery_logo.png"></td>
                                        <td>
                                        
                                    <div align="center" style="margin:20px">
                                        <table style="border: #e9561b; border-style: dotted;  " width="100%">
                                        <tbody><tr>
                                                                <td style="width:10vw" align="center"><span style=" font-size:18px;  vertical-align: auto; color:#FFFFFF">
                                                                        <span style="color: #fed22f; font-size: medium ">Date</span>
                                                                        <strong><br><span id="todays_date">{{(winningValue[0].draw_date) || gameStartingDate}}</span><br> 
                                                                        <span style="color: #fed22f; font-size: medium ">Result Draw</span><br>
                                                                        <span id="result_time">{{winningValue[0].end_time  | limitTo: 5}}{{winningValue[0].meridiem}}</span></strong></span></td>
                                            <td align="center" style="width:80vw">
                                                <span style=" font-size:24px; color:#FFFFFF"><strong>
                                            <table style="color:#990000; text-align:center; font-size:18px; width:100%; ">
                                                        <tbody><tr>
                                                            <td class="result-page font-weight-bold" style="background-size: 55px 55px; width:55px; height:55px; "> <strong>GA</strong><br><span style="font-size:14px">60-69</span> </td>
                                                            <td class="result-page font-weight-bold" style="background-size: 55px 55px; width:55px; height:55px;"> <strong>SA</strong><br><span style="font-size:14px">20-29</span> </td>
                                                            <td class="result-page font-weight-bold" style="background-size: 55px 55px; width:55px; height:55px;"><strong>RA</strong><br><span style="font-size:14px">10-19</span> </td>
                                                            <td class="result-page font-weight-bold" style="background-size: 80px 100px; width:80px; height:90px; " rowspan="2"><strong style="font-size:22px;"> GOA </strong><br> Star </td>
                                                            <td class="result-page font-weight-bold" style="background-size: 55px 55px; width:55px; height:55px; font-size:20pt; color: rgb(255,0,0);"><span id="star3_single">{{sum}}</span></td>
                                                        </tr>
                                                    <tr>
                                                                <td class="result-in-top font-weight-bold"><span id="result_GA">
                                                                {{winningValue[0].row_number + '' + winningValue[0].column_number}}
                                                                </span></td>
                                                        <td class="result-in-top font-weight-bold"><span id="result_GB">
                                                                {{winningValue[1].row_number + '' + winningValue[1].column_number}}
                                                        </span></td>
                                                        <td class="result-in-top font-weight-bold"><span id="result_GC">
                                                                {{winningValue[2].row_number + '' + winningValue[2].column_number}}
                                                        </span></td>
                                                        <td class="result-in-top font-weight-bold"><span id="star3_sp">
                                                        {{winningValue[0].jumble_number}}</span></td>
                                                </tr>
                                                    </tbody></table>
                                                        </strong>
                                                    </span>
                                            </td>
                                        </tr>
                                    </tbody></table>							
			                </div>
                                        
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column" ng-show="!isLogOut" style="background-color:green"> 
                                                <form class="form-inline" name="login_form">
                                                    <input type="text" class="form-control p-1" ng-model="loginData.user_id" id="email" placeholder="user name" required>
                                                    <label for="pwd">  :</label>
                                                    <input type="password" class="form-control p-1" ng-model="loginData.user_password" id="pwd" placeholder="Password" required>
                                                    
                                                    <button type="submit" class="btn btn-danger mt-2" ng-click="login(loginData)" ng-disabled="login_form.$invalid">Login</button>   
                                                </form> 
                                            </div>

                                            <div class="d-flex flex-column bg-gray-2" ng-show="isLogOut"> 
                                                <div class="col-12 pull-right pt-3 pb-3">
                                                    <button type="submit" class="btn btn-info ml-3" ng-click="logoutCpanel()">Logout!</button>   
                                                </div>
                                            </div>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td colspan="16">
                                            <div>
                                                <table width="100%"><tbody><tr>
                                                                        <td align="left"><span style=" font-size:16px; color: #fed22f">Current Time<strong><br><span style="color: #FFFFFF" id="currentTime">{{show_time}}</span></strong></span></td>
                                                    <td align="center"><span style=" font-size:20px; color:#fed22f">Next Draw Time<strong><br><span style="color: #FFFFFF" id="nextDraw">{{drawTimeList[0].end_time  | limitTo: 5}}{{drawTimeList[0].meridiem}}</span></strong></span></td>
                                                    <td align="right"><span style=" font-size:16px; color:#fed22f" ng-show="remainingTime>=0">Time Remaining<strong><br><span style="color: #FFFFFF;font-size:30px" id="timeRemaining">{{remainingTime | formatDuration}}</span></strong></span></td>
                                                </tr></tbody></table>							
                                            </div>
                                        </td>
	                                </tr>
                                </tbody>
                            </table>
                                
                                
                <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12 p-0">
                       
                    
                        
                        <div class="d-flex flex-column">
                            <div class=" submit text-white" style="background-color:#463434">
                             				                    
                                <span class="text-left pl-5" >Terminal id:<b>{{huiSessionData.user_id ? huiSessionData.user_id : 'XXXX'}}</b>
                                
                                &nbsp;</span>
                                <span class="text-left">Agent:<b>{{huiSessionData.person_name ? huiSessionData.person_name : 'XXXX'}}</b>&nbsp;</span>
                                <span class="text-left">Balance:{{activeTerminalDetails.current_balance | number:2}}&nbsp;</span>
                                <input type="button" ng-show="false" value="JODI" ng-click="singleGame=false;jodiGame=true;playInput=defaultPlayInput">
                                <input type="button" ng-show="false" value="SINGLE" ng-click="jodiGame=false;singleGame=true;playInput=defaultPlayInput">
                              
                               <a href="#" style="color:#CCFF00;font-weight:bold" class="btn" ng-click="getActiveTerminalBalance()" role="button">Refresh</a>
                               
                                <a href="" ng-click="showReportDiv=true" style="color:#CCFF00;font-weight:bold" ng-class="{disabled:isLogIn}" class="btn pull-right" role="button">New Report</a>

                                <a href="#!reportterm" style="color:#CCFF00;font-weight:bold" ng-class="{disabled:isLogIn}" target="_blank" class="btn pull-right" role="button">Old Report</a>
                                <a href="" style="color:#CCFF00;font-weight:bold" class="btn pull-right" role="button" ng-show="!showResultDiv" ng-click="showResultDiv=true;showReportDiv=false;getResultListByDate(todayDate)">Result</a>
                                <a href="" style="color:#CCFF00;font-weight:bold" class="btn pull-right" role="button" ng-show="showResultDiv || showReportDiv" ng-click="showResultDiv=false;;showResSummary=false;showReportDiv=false">Home</a>
                            </div>
                            <div  style="background-color:#F7EFEC7A" id="main-div" class="d-flex">
                             
                                <!-- game-table -->
                                <table ng-show="!showResultDiv && !showReportDiv" id="game-table" style="width:auto !important;height:max-content" class="table-responsive" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td colspan="13"><h2><br></h2></td>
                                        </tr>

                                        <tr id="heading-style" class="text-center">
                                            <td></td>
                                            <td class="upper-romm-number">Mrp</td>
                                            <td class="upper-romm-number">Win</td>
                                            <td class="upper-romm-number">1</td>
                                            <td class="upper-romm-number">2</td>
                                            <td class="upper-romm-number">3</td>
                                            <td class="upper-romm-number">4</td>
                                            <td class="upper-romm-number">5</td>
                                            <td class="upper-romm-number">6</td>
                                            <td class="upper-romm-number">7</td>
                                            <td class="upper-romm-number">8</td>
                                            <td class="upper-romm-number">9</td>
                                            <td class="upper-romm-number">0</td>
                                            <td class="upper-romm-number">Qty</td>
                                            <td class="upper-romm-number">Amount</td>
                                            <td class="upper-romm-number">{{winningValue[0].end_time  | limitTo: 5}}{{winningValue[0].meridiem}}</td>
                                        </tr>

                                        <tr class="text-center">
                                            <td style="font-size: 23px;background-size:200px 80px;border-radius:10px" class="game-name result-page">{{seriesList[0].series_name}}</td>
                                            <td style="font-size: 15px;">{{(seriesList[0].mrp*10) || ''}}</td>
                                            <td style="font-size: 15px;">{{seriesList[0].winning_price}}</td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[1]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[2]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[3]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[4]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[5]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[6]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[7]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[8]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[9]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesOne[0]" class="inputBox form-control"></td>
                                            <td><input hide-zero type="text" class="inputTotalBox form-control" ng-model="totalBoxSum1" readonly></td>
                                            <td><input hide-zero type="text" class="inputTotalBox form-control" ng-model="totalTicketBuy1" readonly></td>
                                            <td><input type="text" class="inputBox form-control result-in-top font-weight-bold text-center" ng-value="winningValue[0].row_number + '' + winningValue[0].column_number"  readonly></td>
                                           
                                        </tr>

                                        <tr class="text-center">
                                            <td style="font-size: 23px;background-size:200px 80px;border-radius:10px" class="game-name result-page">{{seriesList[1].series_name}}</td>
                                            <td style="font-size: 15px;">{{(seriesList[1].mrp*10) || ''}}</td>
                                            <td style="font-size: 15px;">{{seriesList[1].winning_price}}</td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[1]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[2]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[3]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[4]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[5]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[6]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[7]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[8]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[9]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesTwo[0]" class="inputBox form-control"></td>
                                            <td><input hide-zero type="text" class="inputTotalBox form-control" ng-model="totalBoxSum2" readonly></td>
                                            <td><input hide-zero type="text" class="inputTotalBox form-control" ng-model="totalTicketBuy2" readonly></td>
                                            <td><input type="text" class="inputBox form-control result-in-top font-weight-bold text-center" ng-value="winningValue[1].row_number + '' + winningValue[1].column_number" readonly></td>
                                           
                                        </tr>

                                        <tr class="text-center">
                                            <td style="font-size: 23px;background-size:200px 80px;border-radius:10px" class="game-name result-page">{{seriesList[2].series_name}}</td>
                                            <td style="font-size: 15px;">{{(seriesList[2].mrp*10) || ''}}</td>
                                            <td style="font-size: 15px;">{{seriesList[2].winning_price}}</td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[1]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[2]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[3]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[4]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[5]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[6]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[7]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[8]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[9]" class="inputBox form-control"></td>
                                            <td><input positive-integer-only maxlength="2" type="text" ng-model="seriesThree[0]" class="inputBox form-control"></td>
                                            <td><input hide-zero type="text" class="inputTotalBox form-control" ng-model="totalBoxSum3" readonly></td>
                                            <td><input hide-zero type="text" class="inputTotalBox form-control" ng-model="totalTicketBuy3" readonly></td>
                                            <td><input type="text" class="inputBox form-control result-in-top font-weight-bold text-center" ng-value="winningValue[2].row_number + '' + winningValue[2].column_number" readonly></td>
                                           
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="10"></td>
                                            <td>
                                                <button type="button" class="group-box btn-success" ng-click="submitGameValues(seriesOne,seriesTwo,seriesThree)" ng-disabled="false">Buy</button>
                                            </td>
                                            <td>
                                                <button type="button" class="group-box btn-info" ng-click="clearInputBox()">Clear</button>
                                            </td>
                                            <td style="font-size: 20px;">Total</td>
                                            <td><input hide-zero type="text" class="inputTotalBox" ng-model="sumOfBox" readonly></td>
                                            <td><input hide-zero type="text" class="inputTotalBox" ng-model="sumOfTicketPurchased" readonly></td>
                                        </tr>

                                        <tr>
                                            <td><input type="button" value="Advance Book" ng-disabled="!isLogOut"></td>
                                            <td colspan="12"></td>
                                            
                                        </tr>

                                    </tbody>

                                </table>    

                                <!-- end of game table -->
                                <!-- result-table  -->
                                <div class="row col-xs-12 col-md-12 col-sm-12 col-lg-12" ng-show="showResultDiv && !showReportDiv">
                                
                                        <div class="row col-lg-12 col-md-12 col-xs-12 col-sm-12" ng-show="!showResSummary">
                                                <div class="col-lg-8 col-md-8 col-xs-8 col-sm-8">
                                                    <input type="date" class="" ng-model="result_date">
                                                    <a href="#" class="btn btn-danger rounded-circle" ng-click="getResultListByDate(result_date)" role="button">Show</a>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                                    <a href="#" class="btn btn-danger rounded-circle" ng-click="showResSummary=true;showReportDiv=false" role="button">Result Summary</a>
                                                </div>
                                            </div>

                                            <div class="row col-lg-12 col-md-12 col-xs-12 col-sm-12" ng-show="showResSummary">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                                                    <a href="#" class="btn btn-danger rounded-circle" ng-click="showResSummary=false" role="button">Previous Sheet</a>
                                                </div>
                                        </div>





                                        <table align="center" ng-show="!showResSummary"   st-safe-src="resultData" st-table="displayCollection"  style="width: 100%;color:black" cellpadding="0" cellspacing="0" class="table table-bordered table-hover small text-justify">
                                            <thead>
                                                    <tr height="33" align="center">
                                                        <th class="text-center text-dark font-weight-bold" width="16.67%" style="background-color:#9DD929">Date</th>
                                                        <th class="text-center text-white font-weight-bold" width="16.67%" style="background-color:#2056E6">Slab</th>
                                                        <th class="text-center text-white font-weight-bold" width="16.67%" style="background-color:#A120E6">{{seriesList[0].series_name}}</th>
                                                        <th class="text-center text-white font-weight-bold" width="16.67%" style="background-color:#E620AA">{{seriesList[1].series_name}}</th>
                                                        <th class="text-center text-white font-weight-bold" width="16.67%" style="background-color:#E63820">{{seriesList[2].series_name}}</th>
                                                        <th class="text-center text-white font-weight-bold" width="16.67%" style="background-color:#E63820">Goa-Star</th>
                                                
                                                    </tr>                                           
                                            </thead>                                          
                                            
                                                <tbody>
                                                    <tr height="33" align="center" data-ng-if="displayCollection.length==0">
                                                        <td class="text-center text-dark font-weight-bold" width="16.67%" style="background-color:#9DD929">No result</td>
                                                        <td class="text-center text-white" width="16.67%" style="background-color:#2056E6">XX</td>
                                                        <td class="text-center text-white" width="16.67%" style="background-color:#A120E6">XX</td>
                                                        <td class="text-center text-white" width="16.67%" style="background-color:#E620AA">XX</td>
                                                        <td class="text-center text-white" width="16.67%" style="background-color:#E63820">XX</td>
                                                        <td class="text-center text-white" width="16.67%" style="background-color:#E63820">XX</td>

                                                    </tr>
                                                
                                                    <tr height="33" align="center" ng-repeat="x in displayCollection">
                                                        <td class="text-center text-dark font-weight-bold" width="16.67%" style="background-color:#9DD929">{{x.draw_date}}</td>
                                                        <td class="text-center text-white result-style" width="16.67%" style="background-color:#2056E6">{{x.end_time +' ' +x.meridiem}}</td>
                                                        <td class="text-center text-white result-style" width="16.67%" style="background-color:#A120E6">{{(x.lucky_zone) < 10 ? ('0'+x.lucky_zone) : (x.lucky_zone)}}</td>
                                                        <td class="text-center text-white result-style" width="16.67%" style="background-color:#E620AA">{{x.rajlaxmi < 10 ? ('0'+x.rajlaxmi) : (x.rajlaxmi)}}</td>
                                                        <td class="text-center text-white result-style" width="16.67%" style="background-color:#E63820">{{x.smartwin < 10 ? ('0'+x.smartwin) : (x.smartwin)}}</td>
                                                        <td class="text-center font-weight-bold  result-style text-white" width="16.67%" style="background-color:#E63820">{{x.single_result + ' (' + x.jumble_number + ')'}}</td>

                                                    </tr>
                                                    
                                                </tbody>		 	
                                            </table>
                                
                                </div>
                           
                            
                            </div>
                        </div>




                        <div class="row col-lg-12 col-md-12 col-xs-12 col-sm-12" ng-show="showResSummary">
                    
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style=" font-size:28px; color: green;" align="center">
                                            <br>
                                            <b>Results Summary</b>
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%" selected>Select Game : </td>
                                        <td style="width: 50%">
                                            <select ng-model="select_game">
                                                        <option selected disabled>Select Game</option>
                                                        <option ng-repeat="x in seriesList" value="{{x.play_series_id}}">
                                                            {{x.series_name}}
                                                        </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%" selected>Select Year : </td>
                                        <td style="width: 50%">
                                            <select class="large" ng-model="select_year">
                                                <option class="default" value="{{yy}}" selected="">
                                                    {{yy}}                        </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">Result Slot : </td>
                                        <td style="width: 50%">
                                            <select class="large" ng-model="select_month">
                                                                            <option class="default" value="1">Jan</option>
                                                                            <option class="default" value="2">Feb</option>
                                                                            <option class="default" value="3">Mar</option>
                                                                            <option class="default" value="4">Apr</option>
                                                                            <option class="default" value="5">May</option>
                                                                            <option class="default" value="6">Jun</option>
                                                                            <option class="default" value="7">Jul</option>
                                                                            <option class="default" value="8">Aug</option>
                                                                            <option class="default" value="9">Sep</option>
                                                                            <option class="default" value="10">Oct</option>
                                                                            <option class="default" value="11">Nov</option>
                                                                            <option class="default" value="12">Dec</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <input type="button" ng-click="resultSummaryByYearMonth(select_game,select_year,select_month)" value="Show Results" class="button">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        

                            <table align="center" cellpadding="0" cellspacing="0" class="table table-bordered table-responsive result-summary"  st-safe-src="resultData" st-table="displayCollection"  style="width: 100%;color:black;margin-top:30px">
                                    <thead>
                                        <tr height="33" align="center">
                                            <!-- <th>Game Name</th> -->
                                            <th>Draw Time</th>
                                            <th data-ng-repeat="i in getNumber(d) track by $index">{{$index+1}} </th>
                                        </tr>
                                    </thead>                                          
                                    <tbody>
                                        <tr height="33" align="center" ng-repeat="x in summaryData">
                                            <!-- <td></td> -->
                                            <td>{{x.draw_time}}</td>
                                            <td>{{x.day1}}</td>
                                            <td>{{x.day2}}</td>
                                            <td>{{x.day3}}</td>
                                            <td>{{x.day4}}</td>
                                            <td>{{x.day5}}</td>
                                            <td>{{x.day6}}</td>
                                            <td>{{x.day7}}</td>
                                            <td>{{x.day8}}</td>
                                            <td>{{x.day9}}</td>
                                            <td>{{x.day10}}</td>
                                            <td>{{x.day11}}</td>
                                            <td>{{x.day12}}</td>
                                            <td>{{x.day13}}</td>
                                            <td>{{x.day14}}</td>
                                            <td>{{x.day15}}</td>
                                            <td>{{x.day16}}</td>
                                            <td>{{x.day17}}</td>
                                            <td>{{x.day18}}</td>
                                            <td>{{x.day19}}</td>
                                            <td>{{x.day20}}</td>
                                            <td>{{x.day21}}</td>
                                            <td>{{x.day22}}</td>
                                            <td>{{x.day23}}</td>
                                            <td>{{x.day24}}</td>
                                            <td>{{x.day25}}</td>
                                            <td>{{x.day26}}</td>
                                            <td>{{x.day27}}</td>
                                            <td>{{x.day28}}</td>
                                            <td>{{x.day29}}</td>
                                            <td>{{x.day30}}</td>
                                            <td>{{x.day31}}</td>
                                        </tr>  
                                    </tbody>		 	
                            </table>
                        
                        </div>

                        <div ng-if="showReportDiv"  ng-include="'application/views/angular_views/terminal_report.html'"></div>

                            <!-- end of show only single result div-->
                        <div class="d-flex footerContent">
                            It's a amusement Game. So  use of  this website as lottery or any other illegal means is strictly prohibited.
Viewing this website is on your own risk. All the information shown here is sponsored and we warn you that Amusement  Numbers are only for fun.
We are not responsible for any issues or scam. We respect all country, state rules/laws. If you not agree with our site disclaimer. Please quit our site right now.
 @Copyright2019 All rights reserved to GoaStar software development co..
                        </div>
                </div>

                <div class="d-flex" ng-show="true">

                        <!-- Modal -->
                        <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog modal-notify modal-warning" role="document">
                                <!--Content-->
                                <div class="modal-content">
                                    <!--Header-->
                                    <div class="modal-header">
                                        <p class="heading lead text-center">Last Draw Result</p>
                                    </div>

                                    <!--Body-->
                                    <div class="modal-body" style="background-color:#da1e1ed6">
                                        <div class="text-center">
                                            <i class="fas fa-clock fa-4x mb-3 animated rotateIn"></i>
                                            <p style="font-size:6vw;color:black" ng-show="!showResultModalFlag">{{counter}}</p>
                                            <p style="font-size:4vw;color:white" ng-show="showResultModalFlag">{{winningValue[0].end_time  | limitTo: 5}}{{winningValue[0].meridiem}}</p>
                                        </div>
                                        
                                        <ul class="list-group z-depth-0 warm-flame-gradient color-block" ng-show="showResultModalFlag">
                                            <li class="list-group-item justify-content-between">
                                            {{seriesList[0].series_name}}
                                            <span class="text-white text-bold"><a class="btn-floating btn-lg purple-gradient">{{winningValue[0].row_number + '' + winningValue[0].column_number}}</a></span>
                                            </li>
                                            <li class="list-group-item justify-content-between">
                                            {{seriesList[1].series_name}}
                                            <span class="text-white text-bold"><a class="btn-floating btn-lg peach-gradient">{{winningValue[1].row_number + '' + winningValue[1].column_number}}</a></span>
                                            </li>
                                            <li class="list-group-item justify-content-between">
                                            {{seriesList[2].series_name}}
                                            <span class="text-white text-bold"><a class="btn-floating btn-lg blue-gradient">{{winningValue[2].row_number + '' + winningValue[2].column_number}}</a></span>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        <!--/.Content-->
                        </div>
                        </div>
  
                </div>
                


<!--        PRINT PAGE-->

        <div class="container" id="receipt-div" ng-show="false" ng-repeat="x in barcodeList">
            <div ng-repeat="x in barcodeList">
                <h4>{{x.bcd}}</h4>
                <div class="d-flex col-12 mt-1 pl-0">
                    <label  class="col-2">Barcode</label>
                    <div class="col-6">
                        <span ng-bind="x.bcd">: </span>
                    </div>
                </div>
                <div class="d-flex col-12 mt-1 pl-0">
                    <label  class="col-3">Commander 2DIGIT {{x.series_name}} - {{seriesList[0].mrp}}</label>
                </div>

                <div class="d-flex col-12 mt-1 pl-0">
                    <label  class="col-1">Date:</label><span ng-bind="purchase_date"></span>

                    <label  class="col-1">Dr.Time:</label> <span ng-bind="ongoing_draw" class="col-1"></span>
                </div>
                <hr style="border-top: dotted 1px;" />

                <div class="d-flex flex-wrap align-content-start">
                    <div class="p-2" ng-repeat="i in allGameValue track by $index">
                        {{i + ','}}&nbsp;

                    </div>
                </div>


                <hr style="border-top: dotted 1px;" />
                <div class="d-flex col-12" style="color:black">
                    <label  class="col-1">MRP</label><span>: {{seriesList[0].mrp}}</span>
                    <label  class="col-1">Qty:</label> <span ng-bind="totalticket_qty| number:2"></span>
                    <label  class="col-2">{{purchase_time}}</label>
                </div>
                <div class="d-flex col-12">
                    <label  class="col-1">Rs:</label><span ng-bind="totalticket_purchase|number: 2"></span>
                </div>
                <div class="d-flex col-12">
                    <label  class="col-2">Terminal Id</label><span>: <?php echo ($this->session->userdata('user_id'));?></span>

                </div>
                <div class="d-flex col-12">
                    <angular-barcode ng-model="x.bcd" bc-options="barcodeOilBill" bc-class="barcode" bc-type="img"></angular-barcode>
                </div>

            </div>


        </div>




<!--        END-->



        <?php
    }

    

    public function inser_2d_play_input(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Game_model->insert_game_values((object)$post_data['playDetails'],$post_data['drawId'],$post_data['purchasedTicket']);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_all_play_series(){
        $result=$this->Game_model->select_play_series()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_all_draw_time(){
        $result=$this->Game_model->select_from_draw_master()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
     public function get_all_draw_name_list(){
        $result=$this->Game_model->select_draw_name_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    

    public function get_game_activation_details(){
        $result=$this->Game_model->select_game_activation()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_draw_result(){
        $post_data =json_decode(file_get_contents("php://input"), true);

        $result=$this->Game_model->select_game_result_after_each_draw($post_data['drawId']);
        //print_r($result);
//        $report_array['records']=$result->records;
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }


    public function get_previous_result(){
        $result=$this->Game_model->select_previous_game_result()->result_array();
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }

    public function get_result_sheet_today(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Game_model->select_today_result_sheet()->result_array();
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }

    public function get_result_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Game_model->select_result_sheet_by_date($post_data['result_date'])->result_array();
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }


    
    
    function logout_cpanel(){
        $newdata = array(
            'person_id'  => '',
            'person_name'     => '',
            'user_id'=> 0,
            'person_cat_id'     => 0,
            'is_logged_in' => 0,
            'is_currently_loggedin' => 0,
        );
        $this->session->set_userdata($newdata);
        echo json_encode($newdata,JSON_NUMERIC_CHECK);
    }
    
    
    public function get_timestamp(){
    	$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
		echo $date->format('h:i:sA');    
            
    }
    
    
    public function get_message(){
        $result=$this->Game_model->select_message()->result_array();
         $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    public function get_active_draw(){
        $result=$this->Game_model->select_draw_interval();
        $current_draw = $result->active_draw->end_time;
       
        $draw_date=array();
        $draw_date = explode(":",$current_draw);
        $dt_milli_sec = (($draw_date[0] * 60 + $draw_date[1])*60 + $draw_date[2]) * 1000;

        $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $dt = $date->format('h:i:s');
        $current_time=array();
        $current_time = explode(":",$dt);
        $cur_time_milli_sec = (($current_time[0] * 60 + $current_time[1])*60 + $current_time[2]) * 1000;
        $intervalTime = $dt_milli_sec - $cur_time_milli_sec;

        $record=array();
        $record['intervalTime']=$intervalTime;
        $record['nextIntervalList']=$result->interval_list;
        echo json_encode($record,JSON_NUMERIC_CHECK);
    }

    public function save_pattern_result(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Game_model->insert_pattern_result($post_data['single'],$post_data['tripple'],$post_data['purchasedTicket']);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


    public function get_result_summary(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $game = $post_data['game'];
        $year = $post_data['year'];
        $month = $post_data['month'];
        $result=$this->Game_model->select_result_summary_data_by_year_month($game,$year,$month);
        echo json_encode($result,JSON_NUMERIC_CHECK);
    }


}
?>
