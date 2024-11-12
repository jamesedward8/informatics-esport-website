<?php
require_once("dbparent.php");

class member extends DBParent
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addMember($arr_col)
    {
        $sql = "INSERT INTO member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $hash_password = password_hash($arr_col['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $arr_col['fname'], $arr_col['lname'], $arr_col['username'], $hash_password, $arr_col['profile']);
        $stmt->execute();
    }
    public function getMember($offset = null, $limit = null)
    {
        $sql = "SELECT t.name as namateam, g.name as namagame, t.idteam, g.idgame 
                                                    FROM team t 
                                                    JOIN game g ON t.idgame = g.idgame 
                                                    LIMIT ? OFFSET ?";
        if (!is_null($offset)) {
            $sql .= " LIMIT ?,?";
        }

        $stmt = $this->mysqli->prepare(query: $sql);
        if (!is_null(value: $offset))
            $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }
    
    public function checkMember($arr_col)
    {
        $sql = "SELECT username FROM member WHERE username = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $arr_col['$username']);
        $stmt->execute();
    }
}
