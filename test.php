<?php
$servername = "playrajshreegoa.com";
$username = "srg2642_root";
$password = "v%w8z8Gs#}+4";
$dbname = "srg2642_playrajshreegoa_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$numbers = "select result_row from result_details where result_master_id in (select result_master_id from result_master where game_date='2019-11-28' and draw_master_id=7)";
$result2 = $conn->query($numbers);
$num_list=array();

if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
         array_push($num_list,$row["result_row"]);
    }
}
$num_list[0]=3;$num_list[1]=1;$num_list[2]=0;
$x = 1;
 for($i=0;$i<3;$i++){
     if($num_list[$i] == 0)
        $x*=10;
 }
$shuffle_number=0;
$flag=1;
while($flag==1){
    $flag=0;
    for($i=0;$i<2;$i++){
        if($num_list[$i] > $num_list[$i+1]){
            $temp =$num_list[$i];
            $num_list[$i]=$num_list[$i+1];
            $num_list[$i+1]=$temp;
            $flag=1;
        }
    }
}
print_r($num_list);
$shuffle_number= ($num_list[0]*10 + $num_list[1])*10 + $num_list[2];
$shuffle_number *= $x;

$sum= $num_list[0] + $num_list[1] + $num_list[2];
echo $shuffle_number;
if($sum > 9){
    $sum = $sum % 10;
}
$conn->close();
?> 