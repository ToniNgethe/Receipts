<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['uuid']) && isset($_POST['imageUrl'])) {

    // receiving the post params
    $name = $_POST['name'];
    $email = $_POST['email'];
    $uid = $_POST['uuid'];
    $image = $_POST['imageUrl'];

    // check if user is already existed with the same email
    if ($db->isUserExisted($email)) {
        // user already existed
        $response["error"] = FALSE;
        $response["status"] = 0;
        $response["error_msg"] = "User already existed with " . $email;
        echo json_encode($response);

    } else {
        // create a new user
        $user = $db->storeUser($name, $email, $uid, $image);

        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["status"] = 1;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            // $response["user"]["created_at"] = $user["created_at"];
            // $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["status"] = 2;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, uuid, imageUtl, email or password) is missing!";
    echo json_encode($response);
}
