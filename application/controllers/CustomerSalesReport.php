<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerSalesReport extends CI_Controller {
    

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('Person');
        $this -> load -> model('Cust_sale_report_model');
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




    public function angular_view_customer_sale_report(){
        ?>
        <style type="text/css">
            #search-results {
                max-height: 200px;
                border: 1px solid #dedede;
                border-radius: 3px;
                box-sizing: border-box;
                overflow-y: auto;
            }
            .report-table tr th,.report-table tr td{
                border: 1px solid black !important;
                font-size: 10px;
                line-height: 1.5;
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
                <ul class="nav nav-tabs nav-justified " role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" ng-style="tab==1 && selectedTab" href="#" role="tab" ng-click="setTab(1)">Total Sale report</a>
                    </li>

                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="row my-tab-1" class="bg-cpanel">
                            <form name="stockistForm" class="form-horizontal">
                                <div class="card">

                                    <div class="card-header">
                                        <div class="d-flex justify-content-center">
                                            <div class=""><input type="date" class="form-control" ng-model="start_date" ng-change="changeDateFormat(start_date)"></div>
                                            <div class="ml-2 mr-2">To</div>
                                            <div class=""><input type="date" class="form-control" ng-model="end_date" ng-change="changeDateFormat(end_date)"></div>

                                            <div class="col-2" ng-if="PersonCategoryId==1">
                                                <select ng-model="select_stockist" class="form-control" ng-change="getTerminalList(select_stockist)">
                                                    <option selected disabled>Select Stockist</option>
                                                    <option value="0" selected="All">All</option>
                                                    <option ng-repeat="x in stockistList" value="{{x.user_id}}">
                                                        {{x.user_id}}
                                                    </option>
                                                </select>
                                            </div>




                                            <div class="col-2">
                                                <select ng-model="select_terminal" class="form-control">
                                                    <option selected disabled>Select Terminal</option>
                                                    <option value="0" selected="All" ng-show="terminalList.length">All</option>
                                                    <option ng-repeat="x in terminalList" value="{{x.user_id}}">
                                                        {{x.user_id}}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="ml-2"><input type="button" class="btn btn-info" value="Show" ng-click="getAllTerminalTotalSale(start_date,end_date,select_stockist,select_terminal)"></div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <div class="loader mt-1" ng-show="isLoading"></div>
                                        </div>

                                        <div class="d-flex" ng-show="!isLoading">
                                            <div class="col-3"></div>
                                            <div class="col-6">


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
                                                        width: calc( 100% - 1em )
                                                    } 
                                                </style>


                                                <table cellpadding="0" cellspacing="0" class="table table-bordered table-hover report-table small text-justify">
                                                    <thead>
                                                    <tr>
                                                        <th class="p-0 text-center">Terminal ID</th>
                                                        <th class="p-0  text-center">Agent name</th>
                                                        <th class="p-0  text-center">Date</th>
                                                        <th class="p-0 text-center">Amount</th>
                                                        <th class="p-0  text-center">Commission</th>
                                                        <th class="p-0  text-center">Prize Value</th>
                                                        <th class="p-0 text-center ">Net payable</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <tr ng-repeat="x in saleReport" ng-style="$index==0 && grandTotalStyle || {{x.ticket_taken_time=='Total' && totalRowStyle}}">
                                                        <td class="p-0">
                                                            {{$index==0? 'GRAND TOTAL' : x.user_id}}
                                                        </td>
                                                        <td class="p-0 text-center"> {{$index==0? '' : x.agent_name}}</td>
                                                        <td class="p-0 ">{{$index==0? '' : x.ticket_taken_time}}</td>
                                                        <td class="p-0  text-right">{{x.amount | number:2}}</td>
                                                        <td class="p-0  text-right">{{x.commision | number:2}}</td>
                                                        <td class="p-0  text-right">{{x.prize_value| number:2}}</td>
                                                        <td class="p-0 text-right">{{x.net_payable | number:2}}</td>
                                                    </tr>


                                                    </tbody>


                                                </table>
                                            </div>
                                            <div class="col-3"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-center" ng-show="alertMsg">
                                    <div>No records found</div>
                                </div>
                            </form>
                        </div> <!--//End of my tab1//-->
                        <div class="d-flex">
<!--                                                        <div class="col"><pre>stockistList={{stockistList | json}}</pre></div>-->
                            <!--                            <div class="col"><pre>terminalList={{terminalList | json}}</pre></div>-->
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php
    }

    public function get_net_payable_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        // $result=$this->Cust_sale_report_model->get_terminal_total_sale_report($post_data['start_date'],$post_data['end_date'])->result_array();
        
        $result=$this->Cust_sale_report_model->get_terminal_total_sale_report($post_data['start_date'],$post_data['end_date'])->result_array();
        
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }



    public function get_terminal_list(){
        $result=$this->Cust_sale_report_model->fetch_all_terminal_user_id()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }


    public function get_stockist_list(){
        $result=$this->Cust_sale_report_model->fetch_all_stockist()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }

//    public function get_2d_draw_time(){
//        $result=$this->Report_terminal_model->get_all_2d_draw_time_list()->result_array();
//        $report_array['records']=$result;
//        echo json_encode($report_array,JSON_NUMERIC_CHECK);
//    }

//    public function get_card_draw_time(){
//        $result=$this->Report_terminal_model->get_card_draw_time_list()->result_array();
//        $report_array['records']=$result;
//        echo json_encode($report_array,JSON_NUMERIC_CHECK);
//    }


//    public function get_card_report_order_by_barcode(){
//        $post_data =json_decode(file_get_contents("php://input"), true);
//        $result=$this->Report_terminal_model->get_card_game_barcode_report_by_date($post_data['start_date'])->result_array();
//        $report_array['records']=$result;
//        echo json_encode($report_array,JSON_NUMERIC_CHECK);
//
//    }
//     public function insert_claimed_barcode_details(){
//        $post_data =json_decode(file_get_contents("php://input"), true);
//        $result=$this->Report_terminal_model->insert_claimed_barcode($post_data['barcode'],$post_data['game_id'],$post_data['prize_value']);
//        $report_array['records']=$result;
//        echo json_encode($report_array,JSON_NUMERIC_CHECK);
//
//    }




}
?>