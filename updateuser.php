<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['uuid']) && isset($_POST['imageUrl'])) {

    // receiving the post params
    $name = $_POST['name'];
    $uid = $_POST['uuid'];
    $image = $_POST['imageUrl'];


    //update user...
    $user = $db->updateUser($uid,$name,$image);

    if ($user) {

        $response["error"] = FALSE;
        $response["message"] = "Your information was updated successfully..";

        echo json_encode($response);

    }else {

                $response["error"] = TRUE;
                $response["message"] = "Error in updating. Try again..";

                echo json_encode($response);
    }
    
}
