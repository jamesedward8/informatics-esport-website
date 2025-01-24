<?php
require_once("dbparent.php");

class Proposal extends DBParent
{
    public function __construct(){
        parent::__construct();
    }

    public function getProposal($offset = null, $limit = null){
        $sql = "SELECT t.idteam, t.name as team_name, g.name as game_name, m.username, jp.description, jp.status, jp.idmember
                            FROM join_proposal jp 
                            INNER JOIN team t ON jp.idteam = t.idteam 
                            INNER JOIN game g ON t.idgame = g.idgame 
                            INNER JOIN member m ON jp.idmember = m.idmember
                            ORDER BY jp.status";
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

    public function addProposal($idmember, $idteam, $description){
        $sql = "INSERT INTO join_proposal (idmember, idteam, description, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare error: ' . $this->mysqli->error);
        }
        $status = "Waiting";

        $stmt->bind_param("iiss", $idmember, $idteam, $description, $status);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $affected_rows;
    }

    public function updateProposalStatus($idmember, $idteam, $result){
        $stmt = $this->mysqli->prepare("UPDATE join_proposal SET status = ? WHERE idteam = ? AND idmember = ?");
        $stmt->bind_param("sii", $result, $idteam, $idmember);
        return $stmt->execute();
    }

    public function updateAllOtherProposals($idmember, $idteam){
        $stmt1 = $this->mysqli->prepare("
            UPDATE join_proposal 
            SET status = 'rejected' 
            WHERE idmember = ? AND idteam != ? AND idteam IN (
                SELECT idteam FROM team WHERE idgame = (
                SELECT idgame FROM team WHERE idteam = ?)
            )");
        $stmt1->bind_param("iii", $idmember, $idteam, $idteam);
        return $stmt1->execute();
    }

    public function addTeamMember($idteam, $idmember, $chosen_role) {
        $stmt2 = $this->mysqli->prepare("INSERT INTO team_members (idteam, idmember, description) VALUES (?, ?, ?)");
        $stmt2->bind_param("iis", $idteam, $idmember, $chosen_role);
        return $stmt2->execute();
    }

    public function viewTeam($idmember){
        $sql = "SELECT t.idteam, t.name 
                FROM team_members tm 
                JOIN team t ON tm.idteam = t.idteam 
                JOIN join_proposal jp ON jp.idteam = tm.idteam AND jp.idmember = tm.idmember 
                WHERE tm.idmember = ? AND jp.status = 'approved'";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function viewTeamMembers($idteam){
        $sql = "SELECT m.username, tm.description 
                        FROM team_members tm 
                        JOIN member m ON tm.idmember = m.idmember 
                        JOIN team t ON tm.idteam = t.idteam 
                        WHERE t.idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function viewTeambyId($idmember){
        $sql = "SELECT tm.idteam, tm.idmember, tm.description, t.name as namateam, g.name as namagame 
                FROM team_members tm 
                JOIN team t ON tm.idteam = t.idteam 
                JOIN game g ON t.idgame = g.idgame 
                WHERE tm.idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getProposalsByMember($idmember, $offset, $limit){
        $sql = "SELECT t.idteam, t.name as team_name, g.name as game_name, jp.description, jp.status 
                FROM join_proposal jp
                INNER JOIN team t ON jp.idteam = t.idteam
                INNER JOIN game g ON t.idgame = g.idgame
                WHERE jp.idmember = ?
                LIMIT ?, ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iii", $idmember, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }

    public function getTotalProposals($idmember){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM join_proposal WHERE idmember = ?");
        $stmt->bind_param('i', $idmember);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    public function getTotalProposal(){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM join_proposal");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    public function getTotalTeamMembers($iduser){
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS total FROM team_members WHERE idmember = ?");
        $stmt->bind_param('i', $iduser);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    public function getApprovedTeam($idmember){
        $sql = "SELECT jp.idteam, t.idgame 
                FROM join_proposal jp 
                JOIN team t ON jp.idteam = t.idteam 
                WHERE jp.idmember = ? AND jp.status = 'approved'";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getWaitedTeam($idteam, $idmember){
        $sql = "SELECT jp.idteam, jp.idmember 
                FROM join_proposal jp 
                WHERE jp.idteam = ? AND jp.status = 'waiting' AND jp.idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $idteam, $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getStatusTeam($idteam, $idmember){
        $sql = "SELECT jp.status 
                FROM join_proposal jp 
                WHERE jp.idteam = ? AND jp.idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $idteam, $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}