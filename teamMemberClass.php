<?php
require_once("dbparent.php");


class teamMember extends DBParent
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
    }
    public function getTeamMember($view_team_id,$offset = null, $limit = null)
    {
        $sql = "SELECT m.username, tm.description FROM team_members tm JOIN member m ON tm.idmember = m.idmember JOIN team t ON tm.idteam = t.idteam WHERE t.idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $view_team_id);

        if (!is_null($offset)) {
            $sql .= " LIMIT ?,?";
        }
        if (!is_null(value: $offset))
            $stmt->bind_param('ii', $offset, $limit);
        
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function updateGame($arr_col)
    {
        $sql = "UPDATE game SET name =?, description =? WHERE id =?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ssi', $arr_col['name'], $arr_col['description'], $arr_col['id']);
        $stmt->execute();
    }
    public function selectGamebyId($idgame)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM game WHERE idgame = ?");
        $stmt->bind_param("i", $idgame);
        $stmt->execute();
        $res = $stmt->get_result();
        $game = $res->fetch_assoc();
    }
    public function deleteGame($id)
    {
        $sql = "DELETE FROM game WHERE id =?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
