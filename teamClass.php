<?php
require_once("dbparent.php");

class team extends DBParent
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addteam($arr_col)
    {
        $sql = "INSERT INTO team (idgame, name) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $arr_col['idgame'], $arr_col['name']);
        $stmt->execute();
    }
    public function getTeam($offset = null, $limit = null)
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

    public function updateTeam($arr_col)
    {
        $sql = "UPDATE team SET idgame = ?,  name = ? WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("isi", $arr_col['idgame'], $arr_col['name'], $arr_col['idteam']);
        $stmt->execute();
    }

    public function deleteGame($id)
    {
        $sql = "DELETE FROM team WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
    }
}
