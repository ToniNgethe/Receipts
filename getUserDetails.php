<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['email']) && isset($_POST['uuid'])) {

    // receiving the post params
    $email = $_POST['enail'];
    $uid = $_POST['uuid'];


    //update user...
    $user = $db->getUserByEmailAndUid($uid,$email);

    if ($user) {

        $response["error"] = FALSE;
        
        $response["user"]["name"] = $user["name"];
        $response["user"]["image"] = $user["image"];

        echo json_encode($response);

    }else {

                $response["error"] = TRUE;
                $response["message"] = "Error in updating. Try again..";

                echo json_encode($response);
    }
}
