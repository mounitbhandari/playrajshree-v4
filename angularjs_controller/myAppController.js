//site_url='http://127.0.0.1/bigstar/index.php/';
// var app = angular.module("myApp", ["ui.bootstrap"]);
var url=location.href;
var urlAux = url.split('/');
		//FOR SERVER
// var base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/';
// var site_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/index.php/';

//FOR LOCAL
var base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/';
 var site_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/index.php/';
var intervalTime = -1;
var project_url=base_url;
var personCategotyId=0;
var isLogOut= false;

 //var headerPath='application/views/header.php';
// var stockistHeaderPath='application/views/stockistHeader.php';
// var displayHeader='';

var app = angular.module("myApp", ["ngRoute","angular-md5","timer","angular-barcode","smart-table"]);
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : site_url+"Play/angular_view_play",
            controller : "playCtrl"
        }).when("/cpanel", {
            templateUrl : site_url+"Admin/angular_view_welcome",
            controller : "adminCtrl"
        }).when("/stockist", {
            templateUrl : site_url+"Stockist/angular_view_stockist",
            controller : "stockistCtrl"
        }).when("/terminal", {
            templateUrl : site_url+"Terminal/angular_view_terminal",
            controller : "terminalCtrl"
        }).when("/stlim", {
            templateUrl : site_url+"StockistLimit/angular_view_limit",
            controller : "stockistLimitCtrl"
         }).when("/trlim", {
            templateUrl : site_url+"TerminalLimit/angular_view_limit",
            controller : "terminalLimitCtrl"
        }).when("/payout", {
            templateUrl : site_url+"payoutSettings/angular_view_set_payout",
            controller : "payoutSettingsCtrl"
        }).when("/manualresult", {
            templateUrl : site_url+"ManualResult/angular_view_set_manual_result",
            controller : "manualResultCtrl"
        }).when("/reportterm", {
            templateUrl : site_url+"ReportTerminal/angular_view_terminal_report",
            controller : "reportTerminalCtrl"
        }).when("/custSalesReportCtrl", {
            templateUrl : site_url+"CustomerSalesReport/angular_view_customer_sale_report",
            controller : "custSalesReportCtrl"
         }).when("/barcodereport", {
            templateUrl : site_url+"BarcodeReport/angular_view_terminal_report",
            controller : "barcodeReportCtrl"
        }).when("/termsession", {
            templateUrl : site_url+"SessionCloseTerminal/angular_view_close_session",
            controller : "sessionCloseTerm"
         }).when("/result", {
            templateUrl : site_url+"Result/angular_view_game_result",
            controller : "resultCtrl"
         }).when("/message", {
            templateUrl : site_url+"Message/angular_view_game_message",
        
         }).when("/drawreport", {
            templateUrl : site_url+"DrawReport/angular_view_draw_report",
            controller : "drawReportCtrl"
         }).when("/helpTerminal", {
            templateUrl : site_url+"IndirectPlayService/angular_view_game_input",
            controller : "indirectPlayCtrl"
         }).when("/addresult", {
            templateUrl : site_url+"EmergencyResult/angular_view_emergency_action",
            controller : "emergencyCtrl"
        });
});

app.directive('a', function() {
    return {
        restrict: 'E',
        link: function(scope, elem, attrs) {
            if(attrs.ngClick || attrs.href === '' || attrs.href === '#'){
                elem.on('click', function(e){
                    e.preventDefault();
                });
            }
        }
    };
});

//it will allow integer values
app.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9-]/g, '');
                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});

app.directive('currencyDecimalPlaces',function(){
    return {
        link:function(scope,ele,attrs){
            ele.bind('keypress',function(e){
                var newVal=$(this).val()+(e.charCode!==0?String.fromCharCode(e.charCode):'');
                if($(this).val().search(/(.*)\.[0-9][0-9]/)===0 && newVal.length>$(this).val().length){
                    e.preventDefault();
                }
            });
        }
    };
});


app.directive('allowDecimalNumbers', function () {  
    return {  
        restrict: 'A',  
        link: function (scope, elm, attrs, ctrl) {  
            elm.on('keydown', function (event) {  
                var $input = $(this);  
                var value = $input.val();  
                value = value.replace(/[^0-9\.]/g, '')  
                var findsDot = new RegExp(/\./g)  
                var containsDot = value.match(findsDot)  
                if (containsDot != null && ([46, 110, 190].indexOf(event.which) > -1)) {  
                    event.preventDefault();  
                    return false;  
                }  
                $input.val(value);  
                if (event.which == 64 || event.which == 16) {  
                    // numbers  
                    return false;  
                } if ([8, 13, 27, 37, 38, 39, 40, 110].indexOf(event.which) > -1) {  
                    // backspace, enter, escape, arrows  
                    return true;  
                } else if (event.which >= 48 && event.which <= 57) {  
                    // numbers  
                    return true;  
                } else if (event.which >= 96 && event.which <= 105) {  
                    // numpad number  
                    return true;  
                } else if ([46, 110, 190].indexOf(event.which) > -1) {  
                    // dot and numpad dot  
                    return true;  
                } else {  
                    event.preventDefault();  
                    return false;  
                }  
            });  
        }  
    }  
});


app.directive('disallowSpaces', function() {
    return {
      restrict: 'A',
  
      link: function($scope, $element) {
        $element.bind('input', function() {
          $(this).val($(this).val().replace(/ /g, ''));
        });
      }
    };
  });





app.filter('capitalize', function() {
    return function(input) {
        return (!!input) ? input.split(' ').map(function(wrd){return wrd.charAt(0).toUpperCase() + wrd.substr(1).toLowerCase();}).join(' ') : '';
    }
});
app.run(function($rootScope){
    $rootScope.CurrentDate = Date;
});
////Directive for input maxlength//
app.directive('myMaxlength', function() {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            var maxlength = Number(attrs.myMaxlength);
            function fromUser(text) {
                if (text.length > maxlength) {
                    var transformedInput = text.substring(0, maxlength);
                    ngModelCtrl.$setViewValue(transformedInput);
                    ngModelCtrl.$render();
                    return transformedInput;
                }
                return text;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});


app.directive('navigatable', function() {
    return function(scope, element, attr) {

        element.on('keypress.mynavigation', 'input[type="text"]', handleNavigation);


        function handleNavigation(e) {

            var arrow = {left: 37, up: 38, right: 39, down: 40};

            // select all on focus
            element.find('input').keydown(function(e) {

                // shortcut for key other than arrow keys
                if ($.inArray(e.which, [arrow.left, arrow.up, arrow.right, arrow.down]) < 0) {
                    return;
                }

                var input = e.target;
                var td = $(e.target).closest('td');
                var moveTo = null;

                switch (e.which) {

                    case arrow.left:
                    {
                        if (input.selectionStart == 0) {
                            moveTo = td.prev('td:has(input,textarea)');
                        }
                        break;
                    }
                    case arrow.right:
                    {
                        if (input.selectionEnd == input.value.length) {
                            moveTo = td.next('td:has(input,textarea)');
                        }
                        break;
                    }

                    case arrow.up:
                    case arrow.down:
                    {

                        var tr = td.closest('tr');
                        var pos = td[0].cellIndex;

                        var moveToRow = null;
                        if (e.which == arrow.down) {
                            moveToRow = tr.next('tr');
                        }
                        else if (e.which == arrow.up) {
                            moveToRow = tr.prev('tr');
                        }

                        if (moveToRow.length) {
                            moveTo = $(moveToRow[0].cells[pos]);
                        }

                        break;
                    }

                }

                if (moveTo && moveTo.length) {

                    e.preventDefault();

                    moveTo.find('input,textarea').each(function(i, input) {
                        input.focus();
                        input.select();
                    });

                }

            });


            var key = e.keyCode ? e.keyCode : e.which;
            if (key === 13) {
                var focusedElement = $(e.target);
                var nextElement = focusedElement.parent().next();
                if (nextElement.find('input').length > 0) {
                    nextElement.find('input').focus();
                } else {
                    nextElement = nextElement.parent().next().find('input').first();
                    nextElement.focus();
                }
            }
        }
    };
});

app.filter('formatDuration', function () {
    return function (input) {
        var totalHours, totalMinutes, totalSeconds, hours, minutes, seconds, result='';

        totalSeconds = input / 1000;
        totalMinutes = totalSeconds / 60;
        totalHours = totalMinutes / 60;

        seconds = Math.floor(totalSeconds) % 60;
        minutes = Math.floor(totalMinutes) % 60;
        hours = Math.floor(totalHours) % 60;

        if (hours !== 0) {
            result += hours+':';

            if (minutes.toString().length == 1) {
                minutes = '0'+minutes;
            }
        }

        result += minutes+':';

        if (seconds.toString().length == 1) {
            seconds = '0'+seconds;
        }

        result += seconds;

        return result;
    };
});

app.directive('hideZero', function() {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$formatters.push(function (inputValue) {
                if (inputValue == 0) {
                    return '';
                }
                return inputValue;
            });
            ngModel.$parsers.push(function (inputValue) {
                if (inputValue == 0) {
                    ngModel.$setViewValue('');
                    ngModel.$render();
                }
                return inputValue;
            });
        }
    };
})

app.run(function($rootScope){
    $rootScope.roundNumber=function(number, decimalPlaces){
        return parseFloat(parseFloat(number).toFixed(decimalPlaces));
    };
});


app.run(function($rootScope,$window){
    $rootScope.goToFrontPage=function(){
        $window.location.href = '#!';
        $window.location.reload()
    };
});


app.run(function($rootScope) {
    $rootScope.huiPrintDiv = function(printDetails,userCSSFile, numberOfCopies) {
    	
        var divContents=$('#'+printDetails).html();
        
      
        var printWindow = window.open('', '', 'height=400,width=800');

        printWindow.document.write('<!DOCTYPE html>');
        printWindow.document.write('\n<html>');
        printWindow.document.write('\n<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
        printWindow.document.write('\n<title>');
        //printWindow.document.write(docTitle);
        printWindow.document.write('</title>');
        printWindow.document.write('\n<link href="'+project_url+'bootstrap-4.0.0/dist/css/bootstrap.min.css" type="text/css" rel="stylesheet" media="all">\n');
        printWindow.document.write('\n<link href="'+project_url+'css/print_style/basic_print.css" type="text/css" rel="stylesheet" media="all">\n');
        printWindow.document.write('\n<script src="angularjs/angularjs_1.6.4_angular.min.js"></script>\n');
        printWindow.document.write('\n<link href="'+project_url+'css/print_style/');
        printWindow.document.write(userCSSFile);
        printWindow.document.write('?v='+ Math.random()+'" rel="stylesheet" type="text/css" media="all"/>');


        printWindow.document.write('\n</head>');
        printWindow.document.write('\n<body>');
        printWindow.document.write(divContents);
        if(numberOfCopies==2) {
            printWindow.document.write('\n<hr>');
            printWindow.document.write(divContents);
        }
        printWindow.document.write('\n</body>');
        printWindow.document.write('\n</html>');
        //printWindow.document.close();
       // printWindow.print();
       // printWindow.close();
    };
});

app.service('globalInfo', function () {
    var userTypeID = 0;
    return {
        getUserTypeID: function () {
            return userTypeID;
        },
        setUserTypeID: function(value) {
            userTypeID = value;
        }
    };
});


app.directive('inputRestrictor', [
    function() {
      return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attr, ngModelCtrl) {
          // Matches characters that aren't `0-9`, `.`, `+`, or `-`
          var pattern = /[^.0-9+-]/g; 
          
  
          function fromUser(text) {
            var rep = /[+]/g;  // find + symbol (globally)
            var rem = /[-]/g;  // find - symbol (globally)
            rep.exec(text);
            rem.exec(text);
  
            // Find last index of each sign
            // The most recently typed characters are last
            var indexp = rep.lastIndex; 
            var indexm = rem.lastIndex;
  
            // remove formatting, and add it back later
            text = text.replace(/[+.-]/g, '');
            if (indexp > 0 || indexm > 0) {// Are there signs?
              if (indexp > indexm){ // plus sign typed later?
                text = "+" + text;
              } else text = "-" + text;
            }
  
            var transformedInput = text.replace(pattern, '');
            // transformedInput = transformedInput.replace(
            //   /([0-9]{1,2}$)/, ".$1" // make last 1 or 2 digits the decimal
            // )
            ngModelCtrl.$setViewValue(transformedInput);
            ngModelCtrl.$render();
            return transformedInput;
          }
          ngModelCtrl.$parsers.push(fromUser);
        }
      };
    }
  ]);


app.directive('positiveIntegerOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, '');

                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }            
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});

app.run(function($rootScope){
    $rootScope.orgName='GoaStar';
})



















