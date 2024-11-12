<?php
require_once("dbparent.php");


class achivement extends DBParent
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAchievements($arr_col)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM achivement");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }
    public function updateAchievements($arr_col){
        $stmt = $this->mysqli->prepare("UPDATE achievement SET idteam = ?,  name = ?, date = ?, description = ? WHERE idachievement = ?");
        $stmt->bind_param("isssi", $arr_col['idteam'], $arr_col['name'], $date, $desc, $idachievement);
        $stmt->execute();    }

    public function deleteAchievement($arr_col){
        $stmt = $this->mysqli->prepare("DELETE FROM achivement WHERE idachievement =?");
        $stmt->bind_param("i", $idachievement);
        $stmt->execute();
    }

}
