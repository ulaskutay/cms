<?php
/**
 * Eksik E-posta ve Telefon alanlarını ekler
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../app/models/Form.php';
require_once __DIR__ . '/../app/models/FormField.php';

echo "<h1>Eksik Alanlar Ekleniyor...</h1>";

try {
    $formModel = new Form();
    $fieldModel = new FormField();
    
    // Form ID 2
    $formId = 2;
    
    // E-posta alanı
    $emailField = [
        'form_id' => $formId,
        'type' => 'email',
        'label' => 'E-posta Adresi',
        'name' => 'email',
        'placeholder' => 'ornek@email.com',
        'required' => 1,
        'css_class' => 'step-4',
        'help_text' => '',
        'order' => 13
    ];
    
    $emailId = $fieldModel->createField($emailField);
    echo "<p style='color: green;'>✅ E-posta alanı oluşturuldu (ID: {$emailId})</p>";
    
    // Telefon alanı
    $phoneField = [
        'form_id' => $formId,
        'type' => 'tel',
        'label' => 'Telefon',
        'name' => 'phone',
        'placeholder' => '05XX XXX XX XX',
        'required' => 1,
        'css_class' => 'step-4',
        'help_text' => '',
        'order' => 14
    ];
    
    $phoneId = $fieldModel->createField($phoneField);
    echo "<p style='color: green;'>✅ Telefon alanı oluşturuldu (ID: {$phoneId})</p>";
    
    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Eksik alanlar başarıyla eklendi!</h2>";
    echo "<p><a href='/admin.php?page=forms&action=edit&id=2'>→ Formu Admin Panelde Görüntüle</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Hata!</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
