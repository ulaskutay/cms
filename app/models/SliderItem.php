<?php
/**
 * Slider Item Model
 * Slider item işlemleri için model sınıfı
 */

class SliderItem extends Model {
    protected $table = 'slider_items';
    
    /**
     * Slider ID'ye göre aktif item'ları getirir (sıralı)
     */
    public function getBySliderId($sliderId) {
        return $this->db->fetchAll(
            "SELECT * FROM `{$this->table}` WHERE `slider_id` = ? AND `status` = 'active' ORDER BY `order` ASC",
            [$sliderId]
        );
    }
    
    /**
     * Slider ID'ye göre tüm item'ları getirir (sıralı)
     */
    public function getAllBySliderId($sliderId) {
        return $this->db->fetchAll(
            "SELECT * FROM `{$this->table}` WHERE `slider_id` = ? ORDER BY `order` ASC",
            [$sliderId]
        );
    }
    
    /**
     * Item'ları toplu olarak sıralama günceller
     */
    public function updateOrder($items) {
        foreach ($items as $order => $itemId) {
            $this->update($itemId, ['order' => $order + 1]);
        }
    }
    
    /**
     * Yeni item ekler
     */
    public function createItem($data) {
        // Eğer order belirtilmemişse, en sona ekle
        if (!isset($data['order']) || $data['order'] === null) {
            $lastOrder = $this->db->fetch(
                "SELECT MAX(`order`) as max_order FROM `{$this->table}` WHERE `slider_id` = ?",
                [$data['slider_id']]
            );
            $data['order'] = ($lastOrder['max_order'] ?? 0) + 1;
        }
        
        return $this->create($data);
    }
}
