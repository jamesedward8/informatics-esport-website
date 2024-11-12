<?php
require_once("dbparent.php");

class Member extends DBParent
{
    public function __construct(){
        parent::__construct();
    }

    public function addMember($arr_col){
        $sql = "INSERT INTO member(fname, lname, username, password, profile) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql); 
        $profile = "member";

        $hash_password = password_hash($arr_col['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $arr_col['fname'], $arr_col['lname'], $arr_col['username'], $hash_password, $profile);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
    
    public function checkAvailability($username){
        $sql = "SELECT username FROM member WHERE username = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM member WHERE username = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                return $row;  
            } else {
                return null;  
            }
        } 
        else {
            return null;  
        }
    }
}
