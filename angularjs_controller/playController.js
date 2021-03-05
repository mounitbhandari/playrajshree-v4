app.controller("playCtrl", function ($scope,$http,$filter,$timeout,dateFilter,$interval,$rootScope,$window) {
    $scope.msg = "This is play controller";
    $scope.showResultModalFlag = false;
    var openmodalPopup = function(){
        $('#myModal').modal('show');
        $scope.counter = 10;
        console.log($scope.counter);
        var promise = $interval(function () {
            console.log($scope.counter);
            if($scope.counter > 0){
                $scope.counter = $scope.counter - 1;
            }else{
                $interval.cancel(promise);
                increaseCounter2();
                
            }
    
        },1000);
    }

    $scope.todayDate= new Date();
   $scope.showResultDiv=false;
   $scope.showReportDiv=false;
   $scope.intervalList=[];
   var request = $http({
        method: "get",
        url: site_url+"/Play/get_active_draw",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.t=response.data.intervalTime;
        $scope.intervalList = response.data.nextIntervalList;
        $scope.$emit("initialized");
        if($scope.t >=0){
         $interval(increaseCounter, $scope.t,1);   
        }
    });

    //.log($scope.intervalList,'outside');
    
    //$('#flipFlop').modal('show');
    $scope.isLogIn=true;
    $scope.isLogOut=false;

    $scope.loginModal=true;
    $scope.loginData={};
    $scope.login=function (loginData) {
        //var psw=md5.createHash($scope.loginData.user_password || '');
        var psw=loginData.user_password;
        var user_id=loginData.user_id;

        var request = $http({
            method: "post",
            url: site_url+"/base/validate_credential",
            data: {
                userId: user_id
                ,userPassword: psw
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.loginDatabaseResponse=response.data;
           personCategotyId= $scope.loginDatabaseResponse.person_cat_id;
            if($scope.loginDatabaseResponse.user_id){
                $scope.getUserData();
                $scope.getActiveTerminalBalance();
                if(personCategotyId == 3){
                    $scope.isLogOut=true;
                    $scope.isLogIn=false;
                }else if(personCategotyId == 1 || personCategotyId == 4){
                    $window.location.href = base_url+'#!cpanel';
                    $scope.isLogOut=true;
                    $scope.isLogIn=false;
                }else if($scope.loginDatabaseResponse.is_currently_loggedin==1){
                    alert("This account is already loggedin");
                }else{

                }
            }else{
                alert("Check User id or Password");
            }
        });
    };

    $scope.submitStatus=false;
    $scope.showResultSheet=false;

    $scope.tenDigitDrawTime=true;
    $scope.gameNumber=1;
    $scope.disable2d=false;

    $scope.huiSessionData={};
    $scope.getUserData=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_sessiondata",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.huiSessionData=response.data;
            // console.log('huiSessionData',$scope.huiSessionData);
            if($scope.huiSessionData.person_cat_id==3 && $scope.huiSessionData.person_id!=''){
            $scope.isLogIn=false;       //it indicates show login button
            $scope.isLogOut = true;     //It indicates show Logout button
            }

        });
    };
    $scope.getUserData();
    // active terminal balance
    $scope.getActiveTerminalBalance=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_active_terminal_balance",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.activeTerminalDetails=response.data.records;

        });
    };
    $scope.getDrawTimeName=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_all_draw_name_list",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.gameNameList=response.data.records;
        });
    };
    $scope.getDrawTimeName();
    //CREATE DATE//
    $scope.dd = new Date().getDate();
    $scope.mm = new Date().getMonth()+1;
    $scope.yy = new Date().getFullYear();
    $scope.day= ($scope.dd<10)? '0'+$scope.dd : $scope.dd;
    $scope.month= ($scope.mm<10)? '0'+$scope.mm : $scope.mm;
    $scope.gameStartingDate=($scope.day+"/"+$scope.month+"/"+$scope.yy);
    $scope.result_date=($scope.month+"/"+$scope.day+"/"+$scope.yy);

    $scope.changeDateFormat=function(userDate){
        return moment(userDate).format('YYYY-MM-DD');
    };

    //GET TIMER	GET TIMER	GET TIMER	GET TIMER	GET TIMER//

    $scope.getCurrentTime=function(){
        var request = $http({
            method: "get",
            url: site_url+"/Play/get_timestamp",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.show_time=response.data;
            $scope.theclock=$scope.show_time.substr(0,8);
        });
    };

    $scope.updateTime = function(){
        $timeout(function(){
            $scope.getCurrentTime();
            $scope.updateTime();
        },1000);
    };
    $scope.updateTime();

    //CONVERT CURRENT TIME INTO MILLISECONDS//
    $scope.getCurrentTimeIntoMilliseconds=function(){
        if($scope.theclock){
            $scope.holahour=parseInt($scope.theclock.substr(0,2));
            $scope.holamin=parseInt($scope.theclock.substr(3,2));
            $scope.holasec=parseInt($scope.theclock.substr(6,2));
            $scope.meridiem=$scope.show_time.substr(8,2);
            $scope.milliSec=(($scope.holahour * 60 + $scope.holamin) * 60 + $scope.holasec) * 1000;
            $scope.stopwatch=$scope.drawMilliSec-$scope.milliSec;
        }
        $scope.am_pm = new Date().getHours() >= 12 ? "PM" : "AM";
    };

    $scope.getCurrentTimeIntoMilliseconds();
    $interval(function () {
        $scope.getCurrentTimeIntoMilliseconds();

        if($scope.holahour>=10 && $scope.holamin>=30 &&$scope.meridiem=='PM' &&$scope.holahour!=12){
            $scope.remainingTime=(43200000 -$scope.milliSec)+$scope.drawMilliSec;
        }else if($scope.holahour>=11 && $scope.meridiem=='PM' &&$scope.holahour!=12){
            $scope.remainingTime=(43200000 -$scope.milliSec)+$scope.drawMilliSec;
        }else{
            $scope.remainingTime=$scope.drawMilliSec-$scope.milliSec;
        }       

    },1000);

    //END OF CURRENT TIME INTO MILLISECONDS and TIMER//

    $scope.noSeries = false;
    $scope.lastSum=0;
    $scope.show=0;
    $scope.lpValue="";
    $scope.ticketPrice=0.00;

    $scope.verticalBoxCss = {
        "color" : "black",
        "background-color" : "#993333"
    }

    $scope.horizontalBoxCss = {
        "color" : "black",
        "background-color" : "#336699"
    }

    $scope.checkList=[];
    $scope.checkList[0]={
        mrp: 1.1
        ,play_series_id: 1
        ,series_name: "A"
    };
    $scope.playInput=[];
    $scope.seriesOne=angular.copy($scope.playInput);
    $scope.seriesTwo=angular.copy($scope.playInput);
    $scope.seriesThree=angular.copy($scope.playInput);


    $scope.clearInputBox=function(){
        $scope.seriesOne=angular.copy($scope.playInput);
        $scope.seriesTwo=angular.copy($scope.playInput);
        $scope.seriesThree=angular.copy($scope.playInput);
    };

    $scope.row=10;
    $scope.coloumn=12;
    $scope.getRow = function(num) {
        return new Array(num);
    }
    $scope.getCol = function(num) {
        return new Array(num);
    }

    $scope.verticallyHorizontallyPushValue=function(index,x){
        var i=0;
        if(index==10) {
            for(i=0;i<10;i++){
                $scope.playInput[i][x]=$scope.playInput[x][10];
            }

        }
        if(index==11) {
            for(i=0;i<10;i++){
                $scope.playInput[x][i]=$scope.playInput[x][11];
            }

        }

    };
    $scope.generateTableColumn=function(){
        var cl=Math.floor((Math.random()*9)+1);
        return cl;
    };

    $scope.generateTableRow=function(){
        var r=Math.floor((Math.random()*9)+1);
        return r;
    };

    $scope.totalBoxSum1 = 0;
    $scope.totalBoxSum2 = 0;
    $scope.totalBoxSum3 = 0;
    $scope.sumOfBox = 0;

    $scope.totalTicketBuy1 = 0;
    $scope.totalTicketBuy2 = 0;
    $scope.totalTicketBuy3 = 0;
    $scope.sumOfTicketPurchased = 0;
   
    $scope.getTotalBuyTicket=function(playInput,srNo){
        var mrp=0;
        var sum=0;
        if(playInput!=undefined){
            for(var idx=0;idx<10;idx++){
                if(playInput[idx]!=undefined && playInput[idx]){
                    sum= sum + parseInt(playInput[idx]);
                }
            }
        }
       
        $scope.ticketPrice=( $scope.seriesList[srNo].mrp * 10 );
        if(srNo==0){
            $scope.totalBoxSum1=sum;
            $scope.totalTicketBuy1=$scope.totalBoxSum1 * $scope.ticketPrice;
        }else if(srNo==1){
            $scope.totalBoxSum2=sum;
            $scope.totalTicketBuy2=$scope.totalBoxSum2 * $scope.ticketPrice;
        }else if(srNo==2){
            $scope.totalBoxSum3=sum;
            $scope.totalTicketBuy3=$scope.totalBoxSum3 * $scope.ticketPrice;
        }

        $scope.sumOfBox = $scope.totalBoxSum1 + $scope.totalBoxSum2 + $scope.totalBoxSum3;
        $scope.sumOfTicketPurchased = $scope.totalTicketBuy1 + $scope.totalTicketBuy2 + $scope.totalTicketBuy3;
    };   

    $scope.$watch("seriesOne", function() {
        if($scope.seriesList != undefined){
            $scope.getTotalBuyTicket($scope.seriesOne,0);
        }
    }, true);

    $scope.$watch("seriesTwo", function() {
        if($scope.seriesList != undefined){
            $scope.getTotalBuyTicket($scope.seriesTwo,1);
        }
    }, true);

    $scope.$watch("seriesThree", function() {
        if($scope.seriesList != undefined){
            $scope.getTotalBuyTicket($scope.seriesThree,2);
        }
    }, true);

    //GET TOTAL TICKET PURCHASE IN CHANGE OF SERIES

    $scope.submitGameValues=function (seriesOne,seriesTwo,seriesThree) {
        if($scope.isLogIn){
            alert("Please Login");
            $scope.disable2d=false;
            return;
        }
        var masterData=[];
        for(var i=0;i<10;i++){
            if(seriesOne[i]){
                masterData.push({ "play_series_id": 1, "rowNum": i, "value": seriesOne[i]});
            }
        }
        for(var i=0;i<10;i++){
            if(seriesTwo[i]){
                masterData.push({ "play_series_id": 2, "rowNum": i, "value": seriesTwo[i]});
            }
        }
        for(var i=0;i<10;i++){
            if(seriesThree[i]){
                masterData.push({ "play_series_id": 3, "rowNum": i, "value": seriesThree[i]});
            }
        }
       
        if(masterData.length == 0){
            alert("Input is not valid");
            return;
        }

        if($scope.remainingTime<=60000 && $scope.remainingTime>=0){
            alert("Draw closed");
            return;
        }
        
        var balance=$scope.activeTerminalDetails.current_balance;
        
        // Check Terminal Balance
        if(!$scope.totalTicketBuy1)
            $scope.totalTicketBuy1=0;
        if(!$scope.totalTicketBuy2)
            $scope.totalTicketBuy2=0;  
        if(!$scope.totalTicketBuy3)
            $scope.totalTicketBuy3=0;  
        var purchasedTicket=$scope.totalTicketBuy1+$scope.totalTicketBuy2+$scope.totalTicketBuy3; 
        purchasedTicket=$rootScope.roundNumber(purchasedTicket,2);
        
        if(purchasedTicket > balance) {
            alert("Sorry low balance");
            $scope.disable2d=false;
            return;
        }
        var request = $http({
            method: "post",
            url: site_url+"/Play/inser_2d_play_input",
            data: {
                playDetails: masterData
                ,drawId: $scope.drawTimeList[0].draw_master_id
                ,purchasedTicket: purchasedTicket
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.reportArray=response.data.records;
            
            if($scope.reportArray.success==1){
                alert("Print Done");
                $scope.clearInputBox();
                $scope.getActiveTerminalBalance();
                $scope.disable2d=false;

                $scope.barcodeList=[];
                $scope.showSeries=[];

                $scope.totalticket_qty=0;
            }
            if($scope.reportArray.success==0){
                alert($scope.reportArray.message)
            }
        });


    };

    //GET SERIES DATA FROM DATABASE//
    var request = $http({
        method: "post",
        url: site_url+"/Play/get_all_play_series",
        data: {}
        ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(function(response){
        $scope.seriesList=response.data.records;

    });


    $scope.isDeactivate2D=false;
    $scope.isDeactivateCard=false;
    $scope.drawTimeList=[];
    $scope.getCurrentDrawTime=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_all_draw_time",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.drawTimeList=response.data.records;
            $scope.endTime=$scope.drawTimeList[0].end_time;
            $scope.serialNumber=$scope.drawTimeList[0].serial_number;

            // CONVERT DRAW TIME TO MILLISECOND//
            $scope.dateArray = $scope.endTime.split(":");
            $scope.myDate = new Date(1970, 0, 1, $scope.dateArray[0], $scope.dateArray[1], $scope.dateArray[2]);
            $scope.drawHour=$scope.myDate.getHours();
            $scope.drawMin=$scope.myDate.getMinutes();
            $scope.drawSec=$scope.myDate.getSeconds();
            if($scope.serialNumber==13){
                //$scope.drawMilliSec=$scope.drawMilliSec+43200000;
                $scope.drawHour=$scope.drawHour+12;
            }
            $scope.drawMilliSec=(($scope.drawHour * 60 + $scope.drawMin) * 60 + $scope.drawSec) * 1000;

            //****OLD CODE FOR TIMER***//

            if($scope.holahour==12 && $scope.meridiem=='AM' ){
                $scope.drawMilliSec=$scope.drawMilliSec+43200000;
            }
        });
    };

    $scope.previousDraw={};
    $scope.getCurrentDrawTime();

    $scope.getEachDrawTime=function(){
        if($scope.theclock==$scope.drawTimeList[0].end_time && $scope.am_pm==$scope.drawTimeList[0].meridiem){
            $scope.previousDraw=angular.copy($scope.drawTimeList);
            $scope.getCurrentDrawTime();
        }
    };

    $scope.savePatternResult=function(single,tripple){
        var request = $http({
            method: "post",
            url: site_url+"/Play/save_pattern_result",
            data: {
                single:single,
                tripple:tripple
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.seriesList=response.data.records;
    
        });
    };
        $scope.shuffleList=[
            {'first':0,'second':1,'third':2},
            {'first':0,'second':2,'third':1},
            {'first':1,'second':0,'third':2},
            {'first':1,'second':2,'third':0},
            {'first':2,'second':0,'third':1},
            {'first':2,'second':1,'third':0},
        ];

       
      $scope.getPreviousResult=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_previous_result",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            if(response.data){
                $scope.winningValue=response.data;
                $scope.sum = 0; $scope.ans = 0;
                angular.forEach($scope.winningValue, function(value, key) {
                    $scope.sum += value.row_number;
                });
                if($scope.sum > 9){
                    $scope.sum = $scope.sum %10;
                }  
            }else{
                $scope.winningValue={};
            }
        });
    };

    $scope.getPreviousResult(); 
    $scope.getAllResult=function () {
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_draw_result",
            data: {
                drawId: drawId
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.winningValue=response.data.records;
        });
    }; 
    //show all result


    //update draw_time by calling database every 1 second
    $interval(function () {
        $scope.hideDate=false;
        if($scope.theclock >= '09:00:00' && $scope.am_pm=='PM'){
            $scope.hideDate=true;
        }else{
            $scope.hideDate=false;
        }
    },1000);

    $scope.getResultListByDate=function(searchDate){
        var dt=$scope.changeDateFormat(searchDate);
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_result_by_date",
            data: {
                result_date: dt
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.resultData=response.data;
        });
    };


    var increaseCounter = function () {
        console.log('step one');
        $scope.x=-1;
        // increaseCounter2(); 
        openmodalPopup(); 
                                                                                                                                                                                                                                                                                                                                                                                  
    }
    
    var increaseCounter2=function(){
        $scope.showResultModalFlag = true;
        console.log($scope.x);
        console.log('step 2 func');
        $scope.x+=1;

        // $timeout( function(){
        //     $scope.getCurrentDrawTime();
        //     $scope.getPreviousResult();
        //     $scope.test();	
        // }, 1200);
        $scope.getCurrentDrawTime();
        $scope.getPreviousResult();
        $scope.test();	

            
        $timeout( function(){
            $(".modal-backdrop").hide();
            $('#myModal').modal('hide');
            $scope.showResultModalFlag = false;
        }, 5000);
        
    }

    $scope.$on('initialized', function () { 
       $scope.test=function(){
            var totalUpcomingDraw=$scope.intervalList.length;
            var timeDiff=$scope.intervalList[$scope.x].diff * 60 * 1000;
            timeDiff = timeDiff - 12000;
            // $interval(increaseCounter2,timeDiff,1);
            $interval(openmodalPopup,timeDiff,1);
        };
    });
  

    $scope.closeResultSheet=function(gameNumber){
        if(gameNumber==1) {
            $scope.showResultSheet = false;
        }else{
            $scope.defaultCardResult=true;
            $scope.selectCardGameDate=false;
        }
    };

    $scope.getNumber = function(num) {
        return new Array(num);
    };


    $scope.d = new Date($scope.yy, $scope.month, 0).getDate();
    $scope.month = new Date($scope.yy, $scope.month, 0).getMonth();
    $scope.showResSummary=false;
    $scope.showResSummary=false;
    $scope.select_game = '';
    $scope.select_year = $scope.yy;
    $scope.select_month=1;

    $scope.getResultSummary=function(){
        $scope.showResSummary=true;
    };


    $scope.resultSummaryByYearMonth=function(select_game,select_year,select_month){
        $scope.d = new Date(select_year, select_month, 0).getDate();
        var request = $http({
            method: "POST",
            url: site_url+"/Play/get_result_summary",
            data: {
                game: select_game
                ,year: select_year
                ,month: select_month
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.summary_data=response.data;
            $scope.datelist = $scope.summary_data.date_list;
            $scope.summaryData = $scope.summary_data.data;
        });

    };

    $scope.testFunc=function(x){
        $scope.myVar=x * 6;
        return ($scope.myVar);
    };

    $scope.gotToTerminalReportSection=function () {
        $window.location.href ='#!reportterm';
        $window.location.reload();
    };
    $scope.logoutCpanel=function () {

        var request = $http({
            method: "post",
            url: site_url+"/Play/logout_cpanel",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            alert('Successfully logged out');
            $scope.huiSessionData={};
            $window.location.href = base_url+'#!';
            $scope.isLogIn=true;
        });
    };




    // get message for scrolling
    $scope.getScrollingMessage=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Play/get_message",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.scrollingMsg=response.data.records[0];
        });
    };

    $scope.getScrollingMessage();


  $scope.hoverIn=function(){
    $scope.showAnimation=true;  
    $scope.gif1=true;
    $('#modal1').modal({
        show: true,
        backdrop: false
    })
    $timeout(function(){
        $scope.gif1=false;
       // $scope.gif2=true;
    },2000);
    
    $('.count').each(function () {
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            }, {
                duration: 4000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
    });
      $timeout(function() {
        $scope.showAnimation=false;
    }, 20000);

  };

});

;