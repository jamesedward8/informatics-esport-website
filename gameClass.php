<?php
require_once("dbparent.php");


class game extends DBParent
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addGame($arr_col)
    {
        $sql = "INSERT INTO game (name, description) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ss', $arr_col['name'], $arr_col['description']);
        $stmt->execute();

        // echo "<script>
        //         alert('Data added successfully!');
        //         window.location.href='game.php?result=added';
        //     </script>";
    }
    public function getGame($offset=null, $limit=null) {
        $sql ="SELECT * FROM game";
        if(!is_null($offset)) {
            $sql .= " LIMIT ?,?";
        }

        $stmt = $this->mysqli->prepare($sql);
        if(!is_null(value: $offset)) 
            $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function updateGame($arr_col){
        $sql = "UPDATE game SET name =?, description =? WHERE id =?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ssi', $arr_col['name'], $arr_col['description'], $arr_col['id']);
        $stmt->execute();
    }
    public function selectGamebyId($idgame){
        $stmt = $this->mysqli->prepare("SELECT * FROM game WHERE idgame = ?");
        $stmt->bind_param("i", $idgame);
        $stmt->execute();
        $res = $stmt->get_result();
        $game = $res->fetch_assoc();
    }
    public function deleteGame($id){
        $sql = "DELETE FROM game WHERE id =?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

}
