<?php
require_once 'BaseModel.php';

class RankModel extends BaseModel {
    
    public function getAllRanks() {
        $sql = "SELECT * FROM police_ranks WHERE status = 'active' ORDER BY display_order ASC";
        return $this->fetchAll($sql);
    }
    
    public function getRankById($id) {
        $sql = "SELECT * FROM police_ranks WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function createRank($data) {
        $sql = "INSERT INTO police_ranks (rank_name_full, rank_name_short, rank_level, display_order) 
                VALUES (?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['rank_name_full'],
            $data['rank_name_short'],
            $data['rank_level'] ?? 0,
            $data['display_order'] ?? 0
        ]);
    }
    
    public function updateRank($id, $data) {
        $sql = "UPDATE police_ranks SET rank_name_full = ?, rank_name_short = ?, 
                rank_level = ?, display_order = ? WHERE id = ?";
        return $this->execute($sql, [
            $data['rank_name_full'],
            $data['rank_name_short'],
            $data['rank_level'],
            $data['display_order'],
            $id
        ]);
    }
    
    public function deleteRank($id) {
        $sql = "UPDATE police_ranks SET status = 'inactive' WHERE id = ?";
        return $this->execute($sql, [$id]);
    }
    
    public function checkRankExists($rankNameFull, $rankNameShort, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM police_ranks 
                WHERE (rank_name_full = ? OR rank_name_short = ?) AND status = 'active'";
        $params = [$rankNameFull, $rankNameShort];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->fetch($sql, $params);
        return $result['count'] > 0;
    }
}
