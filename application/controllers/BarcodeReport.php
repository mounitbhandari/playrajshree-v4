<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BarcodeReport extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Barcode_report_model');
        $this -> load -> model('cust_sale_report_model');
        $this -> is_logged_in();
    }

    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		$person_cat_id = $this -> session -> userdata('person_cat_id');
        if (!isset($is_logged_in) || $is_logged_in != 1 || ($person_cat_id!=1 && $person_cat_id!=4)) {
            echo 'you have no permission to use admin area'. '<a href="#!" ng-click="goToFrontPage()">Login</a>';
            die();
        }
	}




    public function angular_view_terminal_report(){
        ?>
        <style type="text/css">
            #search-results {
                max-height: 200px;
                border: 1px solid #dedede;
                border-radius: 1px;
                box-sizing: border-box;
                overflow-y: auto;
            }
            .report-table tr th,.report-table tr td{
                border: 1px solid black !important;
                font-size: 10px;
                line-height: 0px;
            }
            .tfoot-style{
                font-size: 12px;
                background-color: #ACD2DD;
                line-height: 0px;
                border: 1px solid black !important;

            }

            #stockist-table-div table th{
                background-color: #1b6d85;
                color: #a6e1ec;
                cursor: pointer;
            }

            a[ng-click]{
                cursor: pointer;
            }
        </style>
    	<div class="d-flex col-12" ng-include="headerPath"></div>
        <div class="d-flex col-12">
            <div class="col-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" ng-style="tab==1 && selectedTab" href="#" role="tab" ng-click="setTab(1)">Barcode Wise</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="row my-tab-1" class="bg-cpanel">
                            <div class="d-flex justify-content-center mb-1">
                                <div class="col-2" ng-show="!selectDate"><input type="text" class="form-control" ng-model="winning_date" ng-change="changeDateFormat(start_date)" readonly></div>
                                <div class="col-2" ng-show="selectDate"><input type="date" class="form-control" ng-model="barcode_report_date" ng-change="changeDateFormat(start_date)"></div>
                                <div class="col-2">
                                    <select ng-model="select_terminal" class="form-control"> 
                                        <option value="0" selected disabled>Select Terminal</option>
                                        <option value="0" selected="All">All</option>
                                        <option ng-repeat="x in terminalList" value="{{x.user_id}}">
                                            {{x.user_id}}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-1">
                                    <select ng-model="select_draw_time" class="form-control">
                                        <option value="0" selected disabled>Select Draw Time</option>
                                        <option value="0" selected="All">All</option>
                                        <option ng-repeat="x in drawTime" value="{{x.draw_master_id}}">
                                            {{(x.end_time |limitTo: 5) + ' '+ (x.meridiem)}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control" ng-model="select_barcode" placeholder="Enter Barcode">
                                </div>

                                <div class="ml-2"><input type="button" class="btn btn-info" value="Show" ng-click="getAllBarcodeDetailsByDate(barcode_report_date,select_terminal,select_draw_time)"></div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="loader mt-1" ng-show="isLoading2"></div>
                            </div>


                            <div class="d-flex justify-content-between" ng-show="!isLoading2">

                                <div class="col">

                                <style>
                                    tbody {
                                        display:block;
                                        max-height:500px;
                                        overflow-y:auto;
                                    }
                                    thead, tbody tr {
                                        display:table;
                                        width:100%;
                                        table-layout:fixed;
                                    }
                                    thead {
                                        /* width: calc( 100% - 1em ) */
                                    } 
                                </style>

                                    <table cellpadding="0" cellspacing="0" class="table table-hover   text-justify">
                                        <thead class="report-table">
                                            <tr>
                                                <th class="text-left">SL</th>
                                                <th class="text-left">Terminal Id</th>
                                                <th class="text-left">D.Time</th>
                                                <th class="text-left">T.Time</th>
                                                <th class="text-left">Barcode</th>
                                                <th class="text-left">Qty</th>
                                                <th class="text-left">Amount</th>
                                                <th class="text-left">Prize</th>
                                                <th class="text-left">Claimed</th>
                                                <th class="text-left">Particulars</th>
                                            </tr>
                                        </thead>

                                        <tbody class="report-table">
                                            <tr ng-repeat="x in barcodeWiseReport | filter : select_barcode" >
                                                <td class="text-left">{{ $index+1}}</td>
                                                <td>{{x.user_id}}</td>
                                                <td>{{x.draw_time +' '+ x.meridiem}}</td>
                                                <td>{{x.ticket_taken_time}}</td>
                                                <td class="text-left pl-0">{{x.barcode}}</td>
                                                <td class="text-right">{{x.quantity |number:2}}</td>
                                                <td class="text-right">{{x.amount |number:2}}</td>
                                                <td class="text-right">{{x.prize_value |number:2}}</td>
                                                <td>{{x.claimed}}</td>
                                                <td>
                                                    <a href="#" type="button" data-toggle="modal" data-target="#flipFlop" ng-click="showParticulars($index,x.barcode)">
                                                        Click here
                                                    </a>
                                                </td>
                                                <!-- <td ng-show="select_barcode_type.id==2">
                                                    <input type="button" value="Claim" class="btn btn-secondary" ng-click="claimedBarcodeForPrize(x,select_game.id)" ng-show="x.is_claimed == 0">
                                                    <input type="button" value="Claimed" class="btn btn-success" ng-disabled="true" ng-show="x.is_claimed == 1">
                                                </td> -->
                                            </tr>

                                            <tr ng-show="barcodeWiseReport.length" class="tfoot-style">
                                                <td colspan="6" class="text-bold">Grand Total</td>
                                                <td class="text-right">{{(barcodeWiseReportFooter.total_amount) | number:2}}</td>
                                                <td class="text-right">{{(barcodeWiseReportFooter.total_prize_value) | number:2}}</td>
                                                <td colspan="2"></td>
                                               
                                            </tr>
                                            <tr class="text-center tfoot-style" ng-show="barcodeWiseReport.length">
                                                <td colspan="6"></td>
                                                <td colspan="4" class="font-weight-bold text-left">Net Balance &nbsp;
                                                    {{((barcodeWiseReportFooter.total_amount) - (barcodeWiseReportFooter.total_prize_value)) | number:2}}
                                                </td>
                                            </tr>
                                        </tbody>

                                        <!-- <tfoot ng-show="barcodeWiseReport.length" class="tfoot-style">
                                            <tr>
                                                <td class="text-center">Grand Total</td>
                                                <td class="text-right">{{(barcodeWiseReportFooter.total_amount) | number:2}}</td>
                                                <td class="text-right">{{(barcodeWiseReportFooter.total_prize_value) | number:2}}</td>
                                                <td class="text-center" colspan="7"></td>

                                            </tr>
                                            <tr class="text-center">
                                                <td colspan="10" class="font-weight-bold">Net Balance &nbsp;
                                                    {{((barcodeWiseReportFooter.total_amount) - (barcodeWiseReportFooter.total_prize_value)) | number:2}}
                                                </td>
                                            </tr>
                                        </tfoot> -->
                                    </table>

                                    
                                    <div class="d-flex justify-content-center" ng-show="alertMsg2">
                                        <div>No records found</div>
                                    </div>
                                </div>

                            </div>


                            <!-- The modal -->
                            <div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="modalLabel">View details</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="word-wrap: break-word">
                                            {{barcodeWiseReport[target].particulars}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div> <!--//End of my tab1//-->
								<div class="d-flex" ng-show="false">
									<div class="col-3"><pre>barcodeWiseReport={{barcodeWiseReport | json}}</pre></div>
									<div class="col-3">{{select_terminal}}{{select_draw_time}}</div>
								</div>
        
                    </div>


                </div>
            </div>
        </div>

        <?php
    }





    public function get_2d_report_order_by_barcode(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Barcode_report_model->get_all_barcode_report_by_date($post_data['start_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }

    public function get_2d_draw_time(){
        $result=$this->Barcode_report_model->get_all_2d_draw_time_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_card_draw_time(){
        $result=$this->Barcode_report_model->get_card_draw_time_list()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


    public function get_card_report_order_by_barcode(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->Barcode_report_model->get_card_game_barcode_report_by_date($post_data['start_date'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }
     public function insert_claimed_barcode_details(){
            $post_data =json_decode(file_get_contents("php://input"), true);
            $result=$this->Barcode_report_model->insert_claimed_barcode($post_data['barcode'],$post_data['game_id'],$post_data['prize_value']);
            $report_array['records']=$result;
            echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }




}
?>