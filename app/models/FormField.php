<?php
/**
 * FormField Model
 * Form alanları yönetimi için model sınıfı
 */

class FormField extends Model {
    protected $table = 'form_fields';
    
    /**
     * Form ID'sine göre tüm alanları getirir
     */
    public function getAllByFormId($formId) {
        $sql = "SELECT * FROM `{$this->table}` WHERE `form_id` = ? ORDER BY `order` ASC";
        $fields = $this->db->fetchAll($sql, [$formId]);
        
        // JSON alanlarını decode et
        foreach ($fields as &$field) {
            $field = $this->decodeJsonFields($field);
        }
        
        return $fields;
    }
    
    /**
     * Aktif alanları getirir
     */
    public function getActiveByFormId($formId) {
        $sql = "SELECT * FROM `{$this->table}` WHERE `form_id` = ? AND `status` = 'active' ORDER BY `order` ASC";
        $fields = $this->db->fetchAll($sql, [$formId]);
        
        // JSON alanlarını decode et
        foreach ($fields as &$field) {
            $field = $this->decodeJsonFields($field);
        }
        
        return $fields;
    }
    
    /**
     * Tekil alan getirir (JSON decoded)
     */
    public function findDecoded($id) {
        $field = $this->find($id);
        if ($field) {
            $field = $this->decodeJsonFields($field);
        }
        return $field;
    }
    
    /**
     * Alan oluşturur
     */
    public function createField($data) {
        // Varsayılan sıralama
        if (!isset($data['order'])) {
            $data['order'] = $this->getNextOrder($data['form_id']);
        }
        
        // Alan adını oluştur
        if (empty($data['name'])) {
            $data['name'] = $this->generateFieldName($data['label']);
        }
        
        // JSON alanlarını encode et
        $data = $this->encodeJsonFields($data);
        
        return $this->create($data);
    }
    
    /**
     * Alan günceller
     */
    public function updateField($id, $data) {
        // JSON alanlarını encode et
        $data = $this->encodeJsonFields($data);
        
        return $this->update($id, $data);
    }
    
    /**
     * Sıralamayı günceller
     */
    public function updateOrder($fields) {
        foreach ($fields as $index => $fieldId) {
            $this->update($fieldId, ['order' => $index]);
        }
        return true;
    }
    
    /**
     * Form ID'sine göre tüm alanları siler
     */
    public function deleteByFormId($formId) {
        $sql = "DELETE FROM `{$this->table}` WHERE `form_id` = ?";
        return $this->db->query($sql, [$formId]);
    }
    
    /**
     * Sonraki sıra numarasını getirir
     */
    private function getNextOrder($formId) {
        $sql = "SELECT MAX(`order`) as max_order FROM `{$this->table}` WHERE `form_id` = ?";
        $result = $this->db->fetch($sql, [$formId]);
        return ($result['max_order'] ?? -1) + 1;
    }
    
    /**
     * Alan adı oluşturur
     */
    private function generateFieldName($label) {
        // Türkçe karakterleri dönüştür
        $turkishChars = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
        $latinChars = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
        $name = str_replace($turkishChars, $latinChars, $label);
        
        // Küçük harfe çevir
        $name = mb_strtolower($name, 'UTF-8');
        
        // Alfanumerik olmayan karakterleri alt çizgi ile değiştir
        $name = preg_replace('/[^a-z0-9]+/', '_', $name);
        
        // Başındaki ve sonundaki alt çizgileri kaldır
        $name = trim($name, '_');
        
        // Boşsa varsayılan ad ver
        if (empty($name)) {
            $name = 'field_' . time();
        }
        
        return $name;
    }
    
    /**
     * JSON alanlarını encode eder
     */
    private function encodeJsonFields($data) {
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options'], JSON_UNESCAPED_UNICODE);
        }
        if (isset($data['validation']) && is_array($data['validation'])) {
            $data['validation'] = json_encode($data['validation'], JSON_UNESCAPED_UNICODE);
        }
        return $data;
    }
    
    /**
     * JSON alanlarını decode eder
     */
    private function decodeJsonFields($field) {
        if (!empty($field['options'])) {
            $decoded = json_decode($field['options'], true);
            $field['options'] = $decoded !== null ? $decoded : [];
        } else {
            $field['options'] = [];
        }
        
        if (!empty($field['validation'])) {
            $decoded = json_decode($field['validation'], true);
            $field['validation'] = $decoded !== null ? $decoded : [];
        } else {
            $field['validation'] = [];
        }
        
        return $field;
    }
    
    /**
     * Alan tiplerini getirir
     */
    public static function getFieldTypes() {
        return [
            'text' => [
                'label' => 'Metin',
                'icon' => 'text_fields',
                'category' => 'input'
            ],
            'email' => [
                'label' => 'E-posta',
                'icon' => 'mail',
                'category' => 'input'
            ],
            'phone' => [
                'label' => 'Telefon',
                'icon' => 'phone',
                'category' => 'input'
            ],
            'number' => [
                'label' => 'Sayı',
                'icon' => 'pin',
                'category' => 'input'
            ],
            'textarea' => [
                'label' => 'Uzun Metin',
                'icon' => 'notes',
                'category' => 'input'
            ],
            'select' => [
                'label' => 'Açılır Liste',
                'icon' => 'arrow_drop_down_circle',
                'category' => 'choice'
            ],
            'checkbox' => [
                'label' => 'Onay Kutuları',
                'icon' => 'check_box',
                'category' => 'choice'
            ],
            'radio' => [
                'label' => 'Radyo Butonları',
                'icon' => 'radio_button_checked',
                'category' => 'choice'
            ],
            'file' => [
                'label' => 'Dosya Yükleme',
                'icon' => 'upload_file',
                'category' => 'input'
            ],
            'date' => [
                'label' => 'Tarih',
                'icon' => 'calendar_today',
                'category' => 'input'
            ],
            'time' => [
                'label' => 'Saat',
                'icon' => 'schedule',
                'category' => 'input'
            ],
            'datetime' => [
                'label' => 'Tarih & Saat',
                'icon' => 'event',
                'category' => 'input'
            ],
            'hidden' => [
                'label' => 'Gizli Alan',
                'icon' => 'visibility_off',
                'category' => 'special'
            ],
            'heading' => [
                'label' => 'Başlık',
                'icon' => 'title',
                'category' => 'layout'
            ],
            'paragraph' => [
                'label' => 'Paragraf',
                'icon' => 'article',
                'category' => 'layout'
            ],
            'divider' => [
                'label' => 'Ayırıcı',
                'icon' => 'horizontal_rule',
                'category' => 'layout'
            ]
        ];
    }
}

