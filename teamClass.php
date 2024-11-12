<?php
require_once("dbparent.php");

class Team extends DBParent
{
    public function __construct(){
        parent::__construct();
    }

    public function getTeam($offset = null, $limit = null){
        $sql = "SELECT t.name as namateam, g.name as namagame, t.idteam, g.idgame 
                                                    FROM team t 
                                                    JOIN game g ON t.idgame = g.idgame";
        if (!is_null($offset)) {
            $sql .= " LIMIT ?,?";
        }

        $stmt = $this->mysqli->prepare(query: $sql);
        if (!is_null($offset) && !is_null($limit))
            $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function addTeam($arr_col){
        $sql = "INSERT INTO team (idgame, name) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare error: ' . $this->mysqli->error);
        }
        $stmt->bind_param("is", $arr_col['idgame'], $arr_col['name']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }

    public function getTeamById($idteam){
        $sql = "SELECT * FROM team WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        $result = $stmt->get_result();
        $team = $result->fetch_assoc();
        $stmt->close();

        return $team;
    }

    public function getAllGames(){
        $sql = "SELECT * FROM game";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $games = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $games;
    }

    public function getAllTeams(){
        $sql = "SELECT * FROM team";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    

    public function updateTeam($arr_col){
        $sql = "UPDATE team SET idgame = ?,  name = ? WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare error: ' . $this->mysqli->error);
        }
        $stmt->bind_param("isi", $arr_col['idgame'], $arr_col['name'], $arr_col['idteam']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }

    public function deleteTeam($idteam) {
        $sql = "DELETE FROM team WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare error: ' . $this->mysqli->error);
        }
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }

    public function getTotalTeam(){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM team");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
}
