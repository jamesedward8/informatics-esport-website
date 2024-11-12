<?php
define("SERVER_NAME", "localhost");
define("DB_NAME", "esport");
define("USER_NAME", "root");
define("PASSWORD", "");

class DBParent
{
    public $mysqli;
    public function __construct()
    {
        $this->mysqli = new mysqli(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
            exit();
        }
    }
}
