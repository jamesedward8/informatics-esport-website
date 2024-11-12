<?php
require_once("dbparent.php");

class Game extends DBParent
{
    public function __construct(){
        parent::__construct();
    }

    public function getGame($offset=null, $limit=null) {
        $sql ="SELECT * FROM game";
        if (!is_null($offset) && !is_null($limit))  {
            $sql .= " LIMIT ?,?";
        }

        $stmt = $this->mysqli->prepare($sql);
        if(!is_null(value: $offset) && !is_null($limit)) 
            $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function addGame($arr_col){
        $sql = "INSERT INTO game (name, description) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare error: ' . $this->mysqli->error);
        }

        $stmt->bind_param('ss', $arr_col['name'], $arr_col['description']);
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }
    

    public function updateGame($arr_col){
        $sql = "UPDATE game SET name = ?, description = ? WHERE idgame = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare error: ' . $this->mysqli->error);
        }

        $stmt->bind_param('ssi', $arr_col['name'], $arr_col['description'], $arr_col['id']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }

    public function getGameById($idgame){
        $stmt = $this->mysqli->prepare("SELECT * FROM game WHERE idgame = ?");
        $stmt->bind_param("i", $idgame);
        $stmt->execute();
        $result = $stmt->get_result();
        $game = $result->fetch_assoc();
        $stmt->close();
        return $game;
    }

    public function deleteGame($idgame){
        $sql = "DELETE FROM game WHERE idgame = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare error: ' . $this->mysqli->error);
        }

        $stmt->bind_param('i', $idgame);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }

    public function getTotalGame(){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM game");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
}
