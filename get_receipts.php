<?php

require_once('Config.php');

$uuid = $_GET['uuid'];

$con = mysqli_connect(DB_HOST ,DB_USER, DB_PASSWORD, DB_DATABASE) or die('Unable to Connect...');

//SQL query to fetch data of a range
$sql = "SELECT * from bankreceipts WHERE uuid = '$uuid'";

//Getting result
$result = mysqli_query($con,$sql);

//Adding results to an array
$res = array();

if($result){

    //SELECT `id`, `uuid`, `name`, `number`, `url`, `desc`, `date`, `cat`, `total` FROM `bankreceipts` WHERE 1
    while($row = mysqli_fetch_array($result)){
        array_push($res, array(
            "name"=>$row['name'],
            "number"=>$row['number'],
            "image"=>$row['url'],
            "desc" =>$row['desc'],
            "date" => $row['date'],
            "category" => $row['cat'],
            "uuid" => $row['uuid'],
            "total" =>$row['total'])
        );
    }
    //Displaying the array in json format
    echo json_encode($res);
}else {
    echo "error :" .mysqli_error($con);
}
