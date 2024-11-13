<?php
require_once("dbparent.php");

class Achievement extends DBParent
{
    public function __construct(){
        parent::__construct();
    }

    public function getAchievements($offset = null, $limit = null){
        $sql = "SELECT a.name as achievement_name, t.name as team_name, a.date, a.description, g.idgame, g.name as game_name, a.idachievement 
                FROM achievement a 
                JOIN team t ON a.idteam = t.idteam 
                JOIN game g ON t.idgame = g.idgame";

        if (!is_null($offset)) {
            $sql .= " LIMIT ?,?";
        }

        $stmt = $this->mysqli->prepare(query: $sql);
        if (!is_null($offset) && !is_null($limit)){
            $stmt->bind_param('ii', $offset, $limit);
            $stmt->execute();
            $res = $stmt->get_result();
            return $res;
        }
    }

    public function getAchievementsForMemberTeams($idmember) {
        $sql = "SELECT a.name AS achievement_name, t.name AS team_name, a.date, a.description, g.name AS game_name, a.  idachievement 
                FROM achievement a
                JOIN team t ON a.idteam = t.idteam
                JOIN game g ON t.idgame = g.idgame
                JOIN team_members tm ON tm.idteam = t.idteam
                WHERE tm.idmember = ?";
        
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $achievements = [];
        while ($row = $result->fetch_assoc()) {
            $achievements[] = $row;
        }
        
        $stmt->close();
        return $achievements;
    }

    public function getTotalAchievementsForMemberTeams($idmember) {
        $sql = "SELECT COUNT(*) AS total
                FROM achievement a
                JOIN team t ON a.idteam = t.idteam
                JOIN team_members tm ON tm.idteam = t.idteam
                WHERE tm.idmember = ?";
        
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    public function addAchievement($arr_col){
        $stmt = $this->mysqli->prepare("INSERT INTO achievement (idteam, name, date, description) VALUES (?,?,?,?)");
        $stmt->bind_param("isss", $arr_col['idteam'], $arr_col['name'], $arr_col['date'], $arr_col['description']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function getAchievementbyId($idachievement)
    {
        $sql = "SELECT achievement.*, team.idgame 
                FROM achievement JOIN team 
                ON achievement.idteam = team.idteam 
                WHERE achievement.idachievement = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idachievement);
        $stmt->execute();
        $result = $stmt->get_result();
        $achieve = $result->fetch_assoc();
        $stmt->close();
        return $achieve;
    }

    public function updateAchievements($arr_col){
        $stmt = $this->mysqli->prepare("UPDATE achievement SET idteam = ?,  name = ?, date = ?, description = ? WHERE idachievement = ?");
        $stmt->bind_param("isssi", $arr_col['idteam'], $arr_col['name'], $arr_col['date'], $arr_col['desc'], $arr_col['idachievement']);
        $stmt->execute(); 
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;   
    }

    public function deleteAchievement($idachievement){
        $stmt = $this->mysqli->prepare("DELETE FROM achievement WHERE idachievement =?");
        $stmt->bind_param("i", $idachievement);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function getTotalAchievement(){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM achievement");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
}
