<?php
require_once("dbparent.php");

class Event extends DBParent
{
    public function __construct(){
        parent::__construct();
    }

    public function getEvent($offset = null, $limit = null){
        $sql = "SELECT * FROM event";
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

    public function getEventForJoinedTeam($idmember) {
        $sql = "SELECT DISTINCT e.idevent, e.name, e.date, e.description 
                FROM event e 
                JOIN event_teams et ON e.idevent = et.idevent 
                JOIN team_members tm ON tm.idteam = et.idteam 
                WHERE tm.idmember = ?";
                
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch all matching events
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        
        $stmt->close();
        return $events;
    }

    /*
    public function getEventForJoinedTeam($idmember, $idevent, $idteam) {
        $sql = "SELECT e.name, e.date, e.description FROM event e JOIN event_teams et ON e.idevent = et.idevent JOIN team t ON t.idteam = et.idteam JOIN team_members tm ON t.idteam = tm.idteam JOIN member m ON m.idmember = tm.idmember WHERE tm.idmember = ? AND et.idevent = ? AND et.idteam = ?;";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iii", $idmember, $idevent, $idteam);
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }
    */

    public function addEvent($arr_col){
        $sql = "INSERT INTO event (name, date, description) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sss", $arr_col['name'], $arr_col['date'], $arr_col['desc']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function updateEvent($arr_col){
        $sql = "UPDATE event SET name = ?, date = ?, description = ? WHERE idevent = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sssi", $arr_col['name'], $arr_col['date'], $arr_col['desc'], $arr_col['idevent']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function getEventbyId($idevent){
        $stmt = $this->mysqli->prepare("SELECT * FROM event WHERE idevent = ?");
        $stmt->bind_param("i", $idevent);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        $stmt->close();
        return $event;
    }

    public function getTeamIdsForEvent($idevent) {
        $sql = "SELECT idteam FROM event_teams WHERE idevent = ?";

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $idevent);
        $stmt->execute();
        $res = $stmt->get_result();

        $teamIds = [];
        while ($row = $res->fetch_assoc()) {
            $teamIds[] = $row['idteam'];
        }

        return $teamIds;
    }

    public function deleteEvent($idevent){
        $sql = "DELETE FROM event WHERE idevent = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $idevent);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function getResultTeam($idevent){
        $stmt = $this->mysqli->prepare("SELECT t.idteam, t.name 
                                        FROM team t 
                                        LEFT JOIN event_teams et ON t.idteam = et.idteam AND et.idevent = ? 
                                        WHERE et.idteam IS NULL");
        $stmt->bind_param("i", $idevent);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getEventTeams($idevent, $offset=null, $limit=null){
        $sql = "SELECT t.name, t.idteam, et.idevent 
                                        FROM event_teams et 
                                        JOIN team t ON et.idteam = t.idteam 
                                        WHERE et.idevent = ?";
        if (!is_null($offset) && !is_null($limit)) {
            $sql .= " LIMIT ?,?";
        }

        $stmt = $this->mysqli->prepare($sql);
        if (!is_null(value: $offset)&& !is_null($limit))
            $stmt->bind_param('iii', $idevent,$offset, $limit);
        else {
            $stmt->bind_param('i', $idevent);
        }
        $stmt->execute();
        $res = $stmt->get_result(); 
        return $res;
    }

    public function addEventTeams($arr_col){
        $sql = "INSERT INTO event_teams (idevent, idteam) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $arr_col['idevent'], $arr_col['idteam']);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }

    public function deleteEventTeams($idevent, $idteam){
        $sql = "DELETE FROM event_teams WHERE idevent = ? and idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ii', $idevent, $idteam);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;
    }
    
    public function getTotalEvent(){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM event");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    public function getTotalJoinEvent($idevent){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM event_teams WHERE idevent = ?");
        $stmt->bind_param('i', $idevent);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
}
