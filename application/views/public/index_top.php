<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=1024">
	<link rel="shortcut icon" href="img/national_lottery_logo.png" type="image/x-icon">
    <?php
        if (file_exists('organisation.xml')) {
            $organisation = simplexml_load_file('organisation.xml');
        } else {
            exit('Failed to open test.xml.');
        }
    ?>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $organisation->title; ?></title>

    <!-- Bootstrap -->
    <link href="bootstrap-4.0.0/dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome-free-5.8.1-web/css/fontawesome.min.css">
    <!-- <link rel="stylesheet" href="css/animate.css"> -->
    <!-- <link rel="stylesheet" href="css/overwrite.css"> -->
    <!-- <link href="css/animate.min.css" rel="stylesheet"> -->
    <!-- <link href="css/style.css" rel="stylesheet" /> -->
    <!-- <link href="css/hui-color-style.css" rel="stylesheet" /> -->
    <link href="css/gradient-color.css" rel="stylesheet" />

<!--    <link href="bootstrap-select-1.13.2/dist/css/bootstrap-select.min.css" rel="stylesheet" />-->

    <script src="jquery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="fontawesome-free-5.0.13/svg-with-js/js/fontawesome-all.min.js" type="text/javascript"></script>
    <script src="fontawesome-free-5.8.1-web/js/fontawesome.min.js" type="text/javascript"></script>


<!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<!-- Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/css/mdb.min.css" rel="stylesheet">

    <script src="js/alasql/alasql.min.js"></script>

</head>

<style>
    .modal-header, h4, .close {
        background-color: #5cb85c;
        color:white !important;
        text-align: center;
        font-size: 30px;
    }
    .modal-footer {
        background-color: #f9f9f9;
    }
    body{
        /*background-color:coral;*/
    }
</style>
<body ng-app="myApp">




