app.controller("reportTerminalCtrl", function ($scope,$http,$filter,$rootScope,dateFilter,$timeout,$interval) {
    $scope.msg = "This is report controller";
	
    $scope.tab = 1;
    $scope.sort = {
        active: '',
        descending: undefined
    };



    $scope.changeSorting = function(column) {
        var sort = $scope.sort;

        if (sort.active == column) {
            sort.descending = !sort.descending;
        }
        else {
            sort.active = column;
            sort.descending = false;
        }
    };
    $scope.getIcon = function(column) {
        var sort = $scope.sort;

        if (sort.active == column) {
            return sort.descending
                ? 'glyphicon-chevron-up'
                : 'glyphicon-chevron-down';
        }

        return 'glyphicon-star';
    };

    $scope.setTab = function(newTab){
        $scope.tab = newTab;
    };
    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
    };

    $scope.selectedTab = {
        "color" : "white",
        "background-color" : "#655D5D",
        "font-size" : "15px",
        "padding" : "5px"
    };

    $scope.gameReportStyle = {
        "background-color" : "#69a709"
    };

    $scope.selectDate=true;
    $scope.winning_date=$filter('date')(new Date(), 'dd.MM.yyyy');
    $scope.start_date=new Date();
    $scope.end_date=new Date();
    $scope.barcode_report_date=new Date();
    $scope.changeDateFormat=function(userDate){
        return moment(userDate).format('YYYY-MM-DD');
    };

    $scope.isLoading=false;
    $scope.isLoading2=false;

    // get total sale report for 2d game
    $scope.alertMsg=true;
    $scope.alertMsg2=true;
    $scope.alertMsgCard=true;

    //GET SERIES DATA FROM DATABASE//
    var request = $http({
        method: "post",
        url: site_url+"/Play/get_all_play_series",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.seriesList=response.data.records;

    });



    
    $scope.getNetPayableDetailsByDate=function (start_date,end_date) {
        $scope.saleReport = [];
        $scope.isLoading=true;
        $scope.alertMsg=false;
        $scope.alertMsg2=true;
        $scope.alertMsgCard=false;
        var start_date=$scope.changeDateFormat(start_date);
        var end_date=$scope.changeDateFormat(end_date);
        if(start_date > end_date){
            var temp=start_date;
            start_date=end_date;
            end_date=temp;
        }

        var request = $http({
            method: "post",
            url: site_url+"/ReportTerminal/get_net_payable_by_date",
            data: {
                start_date: start_date
                ,end_date: end_date
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.saleReport=response.data.records;
            $scope.isLoading=false;
            if($scope.saleReport.length==0){
                $scope.alertMsg=true;
            }else{
                $scope.alertMsg=false;
            }
        });
    };

    //$scope.getNetPayableDetailsByDate($scope.start_date,$scope.end_date);



    $scope.$watch("saleReport", function(newValue, oldValue){

        if(newValue != oldValue){
            var result=alasql('SELECT sum(amount) as total_amount,sum(commision) as total_commision,sum(prize_value) as total_prize_value,sum(net_payable) as total_net_payable  from ? ',[newValue]);
            $scope.saleReportFooter=result[0];
        }
    });


    $scope.gameList = [
        {id : 1, name : "2D"},
        {id : 2, name : "Card"}
    ];
    $scope.select_game=$scope.gameList[0];

    // get two digit draw time list
    $scope.getDrawList=function (gameNo) {
        if(gameNo==1){
            var request = $http({
                method: "post",
                url: site_url+"/ReportTerminal/get_2d_draw_time",
                data: {}
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.drawTime=response.data.records;
            });
        }
        if(gameNo==2){
            var request = $http({
                method: "post",
                url: site_url+"/ReportTerminal/get_card_draw_time",
                data: {}
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.drawTime=response.data.records;
            });
        }
    };
    $scope.getDrawList($scope.select_game.id);
    $scope.select_draw_time=0;


    $scope.barcodeType = [
        {id : 1, type : "All barcode"},
        {id : 2, type : "Winning barcode"}
    ];
    $scope.select_barcode_type=$scope.barcodeType[0];




    // get terminal report order by barcode
    $scope.showbarcodeReport=[];
    $scope.getAllBarcodeDetailsByDate=function (start_date,barcode_report_end_date,barcode_type,select_draw_time) {

        $scope.isLoading2=true;
        var start_date=$scope.changeDateFormat(start_date);
        var end_date=$scope.changeDateFormat(barcode_report_end_date);
        $scope.x=select_draw_time;
        var address="/ReportTerminal/get_2d_report_order_by_barcode";
        
        
        var request = $http({
            method: "post",
            url: site_url + address,
            data: {
                start_date: start_date,
                end_date: end_date
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.barcodeWiseReport=response.data.records;
            $scope.isLoading2=false;
            var winBarcodeDetails=alasql('SELECT *  from ?  where prize_value > 0',[$scope.barcodeWiseReport]);
            if(barcode_type==1){
                $scope.showbarcodeReport=angular.copy($scope.barcodeWiseReport);
            }else{
                $scope.showbarcodeReport=angular.copy(winBarcodeDetails);
            }

            if(select_draw_time>0){
                $scope.x=parseInt($scope.x);
                $scope.showbarcodeReport=alasql("SELECT *  from ? where draw_master_id=?",[$scope.showbarcodeReport,$scope.x]);
            }
            // checking for data
            if($scope.showbarcodeReport.length==0){
                $scope.alertMsg2=true;
            }else{
                $scope.alertMsg2=false;
            }

        });

    };


    $scope.closingPoint=0;
    $scope.transactionReportArray=[];
    $scope.getTransactionReportOfTerminal=function (transaction_start_date,transaction_end_date) {
        $scope.closingPoint=0;
        $scope.isLoading2=true;
        var start_date=$scope.changeDateFormat(transaction_start_date);
        var end_date=$scope.changeDateFormat(transaction_end_date);
        var address="/ReportTerminal/get_terminal_transaction_report";       
        
        var request = $http({
            method: "post",
            url: site_url + address,
            data: {
                start_date: start_date,
                end_date: end_date
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.transactionReportArray=response.data.records;
            $scope.isLoading2=false;
            angular.forEach($scope.transactionReportArray, function (value, key) { 
                if(value.transaction_type=='opening point'){
                    $scope.closingPoint= $scope.closingPoint+value.total;
                }
                if(value.transaction_type=='Ticket sale'){
                    $scope.closingPoint= $scope.closingPoint-value.total;
                }
                if(value.transaction_type=='Winning amount updated'){
                    $scope.closingPoint= $scope.closingPoint+value.total;
                }
                if(value.transaction_type=='Points limit updated'){
                    $scope.closingPoint= $scope.closingPoint+value.total;
                }
                
            }); 
        });

    };



   
    $scope.showParticulars=function (target,barcode) {
        $scope.particularsNote = '';
        $scope.target=target;
        var request = $http({
            method: "post",
            url: site_url+"/CommonFunction/get_particulars_by_barcode_id",
            data: {
                barcode: barcode
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.particularsDetails=response.data.records;
            $scope.particularsDetails.forEach(function (val, idx) {
                $scope.particularsNote += val.series_name + ' ' + val.particulars;
            });
            $scope.barcodeWiseReport[target].particulars = $scope.particularsNote;  
            $scope.showbarcodeReport[target].particulars = $scope.particularsNote;                      
        });
    };

    $scope.claimedBarcodeForPrize=function (barcodeDetails,game_id) {
        var request = $http({
            method: "post",
            url: site_url+"/ReportTerminal/insert_claimed_barcode_details",
            data: {
                barcode: barcodeDetails.barcode
                ,game_id:game_id
                ,prize_value:barcodeDetails.prize_value
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.claimReport=response.data.records;
            if($scope.claimReport.success==1){
                alert("Thanks for the  claim");
                barcodeDetails.is_claimed=1;
            }
        });
    };

    $scope.fullGameDetailsReport = [];
    $scope.showFullGameInputReport=function (seriesId,drawDate) {
        var drawDate=$scope.changeDateFormat(drawDate);
        $scope.fullGameDetailsReport = [];
        var request = $http({
            method: "post",
            url: site_url+"/ReportTerminal/get_game_full_details_report_game_wise",
            data: {
                seriesId: seriesId,
                drawDate: drawDate
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.fullGameDetailsReport =response.data.records;                   ;
        });
    };

});

