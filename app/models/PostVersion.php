<?php
/**
 * PostVersion Model - Yazı Versiyon Geçmişi
 */

class PostVersion extends Model {
    protected $table = 'post_versions';
    
    /**
     * Yazının versiyonlarını getirir
     */
    public function getByPostId($postId) {
        $sql = "SELECT pv.*, u.username as author_name
                FROM `{$this->table}` pv
                LEFT JOIN `users` u ON pv.created_by = u.id
                WHERE pv.post_id = ?
                ORDER BY pv.version_number DESC";
        return $this->db->fetchAll($sql, [$postId]);
    }
    
    /**
     * Versiyonu detaylarıyla getirir
     */
    public function findWithDetails($id) {
        $sql = "SELECT pv.*, u.username as author_name
                FROM `{$this->table}` pv
                LEFT JOIN `users` u ON pv.created_by = u.id
                WHERE pv.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Yeni versiyon oluşturur
     */
    public function createVersion($postId, $data, $userId = null) {
        // Mevcut en son versiyon numarasını al
        $sql = "SELECT MAX(version_number) as max_version FROM `{$this->table}` WHERE post_id = ?";
        $result = $this->db->fetch($sql, [$postId]);
        $nextVersion = ($result['max_version'] ?? 0) + 1;
        
        $versionData = [
            'post_id' => $postId,
            'version_number' => $nextVersion,
            'title' => $data['title'] ?? '',
            'slug' => $data['slug'] ?? '',
            'excerpt' => $data['excerpt'] ?? '',
            'content' => $data['content'] ?? '',
            'featured_image' => $data['featured_image'] ?? null,
            'meta_title' => $data['meta_title'] ?? '',
            'meta_description' => $data['meta_description'] ?? '',
            'meta_keywords' => $data['meta_keywords'] ?? '',
            'created_by' => $userId
        ];
        
        return $this->create($versionData);
    }
    
    /**
     * Yazının versiyon sayısını getirir
     */
    public function getVersionCount($postId) {
        $sql = "SELECT COUNT(*) as count FROM `{$this->table}` WHERE post_id = ?";
        $result = $this->db->fetch($sql, [$postId]);
        return $result['count'] ?? 0;
    }
    
    /**
     * Eski versiyonları temizler (belirli sayıdan fazlasını siler)
     */
    public function cleanOldVersions($postId, $keepCount = 20) {
        $sql = "DELETE FROM `{$this->table}` 
                WHERE post_id = ? 
                AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM `{$this->table}` 
                        WHERE post_id = ? 
                        ORDER BY version_number DESC 
                        LIMIT {$keepCount}
                    ) as keep_versions
                )";
        return $this->db->query($sql, [$postId, $postId]);
    }
}

