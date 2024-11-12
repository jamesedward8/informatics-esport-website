<?php
require_once("dbparent.php");


class event extends DBParent
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addEvent($arr_col)
    {
        $sql = "INSERT INTO event (name, date, description) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sss", $arr_col['name'], $arr_col['date'], $arr_col['desc']);
        $stmt->execute();
    }
    public function getEvent($offset = null, $limit = null)
    {
        $sql = "SELECT * FROM event LIMIT ? OFFSET ?";
        if (!is_null($offset)) {
            $sql .= " LIMIT ?,?";
        }

        $stmt = $this->mysqli->prepare($sql);
        if (!is_null(value: $offset))
            $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function updateEvent($arr_col)
    {
        $sql = "UPDATE event SET name = ?, date = ?, description = ? WHERE idevent = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sssi", $arr_col['name'], $arr_col['date'], $arr_col['desc'], $arr_col['idevent']);
        $stmt->execute();
    }
    public function selectEvenetbyId($idevent)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM event WHERE idevent = ?");
        $stmt->bind_param("i", $idevent);
        $stmt->execute();
        $res = $stmt->get_result();
        $game = $res->fetch_assoc();
    }
    public function deleteGame($idevent)
    {
        $sql = "DELETE FROM event WHERE idevent = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $idevent);
        $stmt->execute();
    }

    public function addEventTeam($arr_col){
        $sql = "INSERT INTO event_teams (idevent, idteam) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $arr_col['idevent'], $arr_col['idteam']);
        $stmt->execute();
    }
}
