<?php

class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {

    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $uuid, $image) {

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, image) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss",$uuid, $name, $email,$image);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    //update user image and name...

    public function updateUser($uuid, $uri, $name){

        $stmt = $this->conn->prepare("UPDATE users SET name = ? AND image = ? WHERE unique_id = ?");
        $stmt->bind_param("sss",$name, $uri, $uuid);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {

            // $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            // $stmt->bind_param("s", $email);
            // $stmt->execute();
            // $user = $stmt->get_result()->fetch_assoc();
            // $stmt->close();

            return true;
        }else{
            return false;
        }

    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndUid($email, $uuid) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? AND unique_id = ?");

        $stmt->bind_param("ss", $email,$uuid);

        if ($stmt->execute()) {

            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();


            return $user;

        } else {

            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }


    public function inserBankImage( ){


    }

}

?>
