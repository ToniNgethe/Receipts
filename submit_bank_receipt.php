<?php

if($_SERVER['REQUEST_METHOD']=='POST'){

    $uuid = $_POST['uuid'];
    $number = $_POST['number'];
    $desc = $_POST['desc'];
    $image = $_POST['image'];
    $name = $_POST['name'];
    $total = $_POST['total'];
    $cat = $_POST['category'];

    $response = array();

    require_once('Config.php');

    $con = mysqli_connect(DB_HOST ,DB_USER, DB_PASSWORD, DB_DATABASE) or die('Unable to Connect...');

    $sql ="SELECT id FROM bankreceipts ORDER BY id ASC";

    $res = mysqli_query($con,$sql);

    $id = 0;

    while($row = mysqli_fetch_array($res)){
        $id = $row['id'];
    }

    $server_ip = gethostbyname(gethostname());

    $upload_path = "bank_uploads/$id.png";

    $upload_url = 'http://192.168.43.201:80/Receipts/'.$upload_path;

    // $actualpath = "http://simplifiedcoding.16mb.com/PhotoUploadWithText/$path";

    $sql = "INSERT INTO `bankreceipts`(`id`, `uuid`, `name`, `number`, `url`, `desc`, `date`, `cat`, `total`)
    VALUES (NULL, '$uuid', '$name', '$number', '$upload_url', '$desc', NOW(), '$cat', '$total')";

    if(mysqli_query($con,$sql)){

        file_put_contents($upload_path,base64_decode($image));

        echo "Successfully Uploaded";

        // $response['error'] = FALSE;
        // $response['message'] = "Successfully added";
        //
        // echo json_encode($response);

    }else {

        echo "error :" .mysqli_error($con);
        //
        // $response['error'] = TRUE;
        // $response['message'] = "Error" .mysqli_error($con);
        // echo json_encode($response);

    }

    mysqli_close($con);
}else{
    echo "Error";
}
