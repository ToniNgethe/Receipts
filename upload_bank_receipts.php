<?php

//importing dbDetails file
require_once 'Config.php';

//this is our upload folder
$upload_path = 'bank_uploads/';

//Getting the server ip
$server_ip = gethostbyname(gethostname());

//creating the upload url
$upload_url = 'http://'.$server_ip.'/Receipts/'.$upload_path;

//response array
$response = array();


if($_SERVER['REQUEST_METHOD']=='POST'){

    //checking the required parameters from the request
    if( isset($_POST['uuid'])
            && isset($_POST['name']) 
            && isset(['number'])
                    && isset(['desc']) && isset($_FILES['image']['uuid']) ){

        //connecting to the database
        $con = mysqli_connect(DB_HOST ,DB_USER, DB_PASSWORD, DB_DATABASE) or die('Unable to Connect...');

        //getting params from request
        $uuid = $_POST['uuid'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $desc = $_POST['desc'];

        //getting file info from the request
        $fileinfo = pathinfo($_FILES['image']['uuid']);

        //getting the file extension
        $extension = $fileinfo['extension'];

        //file url to store in the database
        $file_url = $upload_url . getFileName() . '.' . $extension;

        //file path to upload in the server
        $file_path = $upload_path . getFileName() . '.'. $extension;

        //trying to save the file in the directory
        try{
            //saving the file
            move_uploaded_file($_FILES['image']['tmp_name'],$file_path);
            $sql = "INSERT INTO `receipt`.`bankreceipts` (`id`,`uuid`, `name`,`number`,`desc`,`url`, `date`) VALUES (NULL, '$uuid', `$name`,`$number`,`$desc`,'$file_url', NOW());";

            //adding the path and name to database
            if(mysqli_query($con,$sql)){

                //filling response array with values
                $response['error'] = false;
                $response['url'] = $file_url;
                $response['name'] = $name;
            }else {
                $response['error'] = true;
                $response['message'] =  mysqli_error($con);
            }
            //if some error occurred
        }catch(Exception $e){
            $response['error']=true;
            $response['message']=$e->getMessage();
        }
        //displaying the response
        echo json_encode($response);

        //closing the connection
        mysqli_close($con);
    }else{
        $response['error']=true;
        $response['message']='Please choose a file';
        echo json_encode($response);
    }
}

/*
We are generating the file name
so this method will return a file name for the image to be upload
*/
function getFileName(){
    $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die('Unable to Connect...');
    $sql = "SELECT max(id) as id FROM bankreceipts";

    $result = mysqli_fetch_array(mysqli_query($con,$sql));

    if (!$result) {
        printf("Error: %s\n", mysqli_error($con));
        exit();
    }

    mysqli_close($con);
    if($result['id']==null)
    return 1;
    else
    return ++$result['id'];
}
