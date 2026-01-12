<?php
/**
 * Teklif Al Formu Oluşturma Script'i
 * 
 * Bu script, Teklif Al sayfası için gerekli form ve alanları oluşturur.
 * Çalıştırmak için: /install/create_quote_form.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../app/models/Form.php';
require_once __DIR__ . '/../app/models/FormField.php';

echo "<h1>Teklif Al Formu Oluşturuluyor...</h1>";

try {
    $formModel = new Form();
    $fieldModel = new FormField();
    
    // Form verilerini hazırla
    $formData = [
        'name' => 'Teklif Al',
        'slug' => 'teklif-al',
        'description' => 'Proje teklifi almak için form',
        'success_message' => 'Talebiniz başarıyla alındı! En kısa sürede sizinle iletişime geçeceğiz.',
        'notification_email' => 'info@codetic.co',
        'status' => 'active'
    ];
    
    // Mevcut formu kontrol et
    $existingForm = $formModel->getBySlug('teklif-al');
    
    if ($existingForm) {
        echo "<p>⚠️ 'teklif-al' slug'ına sahip form zaten mevcut (ID: {$existingForm['id']}). Güncelleniyor...</p>";
        $formId = $existingForm['id'];
        
        // Mevcut alanları sil
        $db = Database::getInstance();
        $db->query("DELETE FROM form_fields WHERE form_id = ?", [$formId]);
        echo "<p>Mevcut alanlar silindi.</p>";
    } else {
        // Yeni form oluştur
        $formId = $formModel->create($formData);
        echo "<p>✅ Yeni form oluşturuldu (ID: {$formId})</p>";
    }
    
    // Form alanlarını tanımla - Etic Ajans Hizmetleri
    $fields = [
        // Adım 1: Proje Tipi
        [
            'form_id' => $formId,
            'type' => 'radio',
            'label' => 'Proje Tipi',
            'name' => 'project_type',
            'required' => 1,
            'css_class' => 'step-1',
            'help_text' => 'Bu projeyi kimin için yapacağız?',
            'options' => json_encode([
                ['value' => 'personal', 'label' => 'Kendim için'],
                ['value' => 'company', 'label' => 'Şirketim için']
            ])
        ],
        
        // Adım 2: Hizmetler - Web & Yazılım
        [
            'form_id' => $formId,
            'type' => 'checkbox',
            'label' => 'Web & Yazılım Hizmetleri',
            'name' => 'services_web',
            'required' => 0,
            'css_class' => 'step-2 category-web',
            'help_text' => '',
            'options' => json_encode([
                ['value' => 'kurumsal_web_sitesi', 'label' => 'Kurumsal Web Sitesi'],
                ['value' => 'e_ticaret_sitesi', 'label' => 'E-Ticaret Sitesi'],
                ['value' => 'ozel_yazilim', 'label' => 'Özel Yazılım Geliştirme'],
                ['value' => 'mobil_uygulama', 'label' => 'Mobil Uygulama'],
                ['value' => 'landing_page', 'label' => 'Landing Page'],
                ['value' => 'cms_kurulum', 'label' => 'CMS Kurulumu (WordPress vb.)']
            ])
        ],
        
        // Adım 2: Hizmetler - Sosyal Medya
        [
            'form_id' => $formId,
            'type' => 'checkbox',
            'label' => 'Sosyal Medya Hizmetleri',
            'name' => 'services_social',
            'required' => 0,
            'css_class' => 'step-2 category-social',
            'help_text' => '',
            'options' => json_encode([
                ['value' => 'sosyal_medya_yonetimi', 'label' => 'Sosyal Medya Yönetimi'],
                ['value' => 'instagram_reklam', 'label' => 'Instagram Reklam Yönetimi'],
                ['value' => 'meta_reklam', 'label' => 'Meta (Facebook) Reklam Yönetimi'],
                ['value' => 'sosyal_medya_icerik', 'label' => 'Sosyal Medya İçerik Üretimi'],
                ['value' => 'influencer_marketing', 'label' => 'Influencer Marketing']
            ])
        ],
        
        // Adım 2: Hizmetler - Reklam & SEO
        [
            'form_id' => $formId,
            'type' => 'checkbox',
            'label' => 'Reklam & SEO Hizmetleri',
            'name' => 'services_ads',
            'required' => 0,
            'css_class' => 'step-2 category-ads',
            'help_text' => '',
            'options' => json_encode([
                ['value' => 'google_ads', 'label' => 'Google Reklam Yönetimi'],
                ['value' => 'google_alisveris', 'label' => 'Google Alışveriş Reklamları'],
                ['value' => 'e_ticaret_reklamlari', 'label' => 'E-Ticaret Reklamları'],
                ['value' => 'seo', 'label' => 'SEO (Arama Motoru Optimizasyonu)'],
                ['value' => 'youtube_reklam', 'label' => 'YouTube Reklam Yönetimi']
            ])
        ],
        
        // Adım 2: Hizmetler - Tasarım & Marka
        [
            'form_id' => $formId,
            'type' => 'checkbox',
            'label' => 'Tasarım & Marka Hizmetleri',
            'name' => 'services_design',
            'required' => 0,
            'css_class' => 'step-2 category-design',
            'help_text' => '',
            'options' => json_encode([
                ['value' => 'logo_tasarim', 'label' => 'Logo Tasarımı'],
                ['value' => 'kurumsal_kimlik', 'label' => 'Kurumsal Kimlik'],
                ['value' => 'ui_ux_tasarim', 'label' => 'UI/UX Tasarım'],
                ['value' => 'grafik_tasarim', 'label' => 'Grafik Tasarım'],
                ['value' => 'video_produksiyon', 'label' => 'Video Prodüksiyon']
            ])
        ],
        
        // Adım 2: Hizmetler - Diğer
        [
            'form_id' => $formId,
            'type' => 'checkbox',
            'label' => 'Diğer Hizmetler',
            'name' => 'services_other',
            'required' => 0,
            'css_class' => 'step-2 category-other',
            'help_text' => '',
            'options' => json_encode([
                ['value' => 'hosting_domain', 'label' => 'Hosting & Domain'],
                ['value' => 'teknik_destek', 'label' => 'Teknik Destek & Bakım'],
                ['value' => 'danismanlik', 'label' => 'Dijital Danışmanlık'],
                ['value' => 'email_pazarlama', 'label' => 'E-Mail Pazarlama'],
                ['value' => 'icerik_yazarligi', 'label' => 'İçerik Yazarlığı']
            ])
        ],
        
        // Adım 3: Proje Detayları
        [
            'form_id' => $formId,
            'type' => 'text',
            'label' => 'Mevcut Siteniz',
            'name' => 'current_website',
            'placeholder' => 'https://example.com (varsa)',
            'required' => 0,
            'css_class' => 'step-3',
            'help_text' => ''
        ],
        [
            'form_id' => $formId,
            'type' => 'textarea',
            'label' => 'Beğendiğiniz Referans Siteler',
            'name' => 'reference_sites',
            'placeholder' => 'Beğendiğiniz web sitelerinin linklerini yazın...',
            'required' => 0,
            'css_class' => 'step-3',
            'help_text' => ''
        ],
        [
            'form_id' => $formId,
            'type' => 'select',
            'label' => 'Tahmini Bütçe Aralığı',
            'name' => 'budget',
            'required' => 0,
            'css_class' => 'step-3',
            'help_text' => '',
            'options' => json_encode([
                ['value' => '', 'label' => 'Seçiniz'],
                ['value' => '5000-15000', 'label' => '5.000₺ - 15.000₺'],
                ['value' => '15000-30000', 'label' => '15.000₺ - 30.000₺'],
                ['value' => '30000-50000', 'label' => '30.000₺ - 50.000₺'],
                ['value' => '50000-100000', 'label' => '50.000₺ - 100.000₺'],
                ['value' => '100000+', 'label' => '100.000₺ ve üzeri'],
                ['value' => 'undecided', 'label' => 'Henüz belirlemedim']
            ])
        ],
        [
            'form_id' => $formId,
            'type' => 'select',
            'label' => 'Projenin Tamamlanma Süresi',
            'name' => 'timeline',
            'required' => 0,
            'css_class' => 'step-3',
            'help_text' => '',
            'options' => json_encode([
                ['value' => '', 'label' => 'Seçiniz'],
                ['value' => 'urgent', 'label' => 'Acil (1-2 hafta)'],
                ['value' => '1month', 'label' => '1 Ay içinde'],
                ['value' => '2-3months', 'label' => '2-3 Ay içinde'],
                ['value' => '3-6months', 'label' => '3-6 Ay içinde'],
                ['value' => 'flexible', 'label' => 'Esnek']
            ])
        ],
        [
            'form_id' => $formId,
            'type' => 'textarea',
            'label' => 'Proje Hakkında Notlarınız',
            'name' => 'project_notes',
            'placeholder' => 'Projeniz hakkında eklemek istediğiniz detayları yazın...',
            'required' => 0,
            'css_class' => 'step-3',
            'help_text' => ''
        ],
        
        // Adım 4: İletişim Bilgileri
        [
            'form_id' => $formId,
            'type' => 'text',
            'label' => 'Şirket / Marka Adı',
            'name' => 'company_name',
            'placeholder' => 'Şirket veya marka adınız',
            'required' => 0,
            'css_class' => 'step-4',
            'help_text' => ''
        ],
        [
            'form_id' => $formId,
            'type' => 'select',
            'label' => 'Sektör',
            'name' => 'industry',
            'required' => 0,
            'css_class' => 'step-4',
            'help_text' => '',
            'options' => json_encode([
                ['value' => '', 'label' => 'Seçiniz'],
                ['value' => 'e-commerce', 'label' => 'E-Ticaret'],
                ['value' => 'technology', 'label' => 'Teknoloji'],
                ['value' => 'healthcare', 'label' => 'Sağlık'],
                ['value' => 'education', 'label' => 'Eğitim'],
                ['value' => 'finance', 'label' => 'Finans'],
                ['value' => 'food', 'label' => 'Yiyecek & İçecek'],
                ['value' => 'fashion', 'label' => 'Moda & Tekstil'],
                ['value' => 'real-estate', 'label' => 'Gayrimenkul'],
                ['value' => 'tourism', 'label' => 'Turizm'],
                ['value' => 'manufacturing', 'label' => 'Üretim'],
                ['value' => 'consulting', 'label' => 'Danışmanlık'],
                ['value' => 'other', 'label' => 'Diğer']
            ])
        ],
        [
            'form_id' => $formId,
            'type' => 'text',
            'label' => 'Ad Soyad',
            'name' => 'full_name',
            'placeholder' => 'Adınız ve soyadınız',
            'required' => 1,
            'css_class' => 'step-4',
            'help_text' => ''
        ],
        [
            'form_id' => $formId,
            'type' => 'email',
            'label' => 'E-posta Adresi',
            'name' => 'email',
            'placeholder' => 'ornek@email.com',
            'required' => 1,
            'css_class' => 'step-4',
            'help_text' => ''
        ],
        [
            'form_id' => $formId,
            'type' => 'tel',
            'label' => 'Telefon',
            'name' => 'phone',
            'placeholder' => '05XX XXX XX XX',
            'required' => 1,
            'css_class' => 'step-4',
            'help_text' => ''
        ],
        [
            'form_id' => $formId,
            'type' => 'select',
            'label' => 'Tercih Ettiğiniz İletişim Yöntemi',
            'name' => 'contact_preference',
            'required' => 0,
            'css_class' => 'step-4',
            'help_text' => '',
            'options' => json_encode([
                ['value' => 'phone', 'label' => 'Telefon'],
                ['value' => 'email', 'label' => 'E-posta'],
                ['value' => 'whatsapp', 'label' => 'WhatsApp'],
                ['value' => 'any', 'label' => 'Farketmez']
            ])
        ],
        [
            'form_id' => $formId,
            'type' => 'checkbox',
            'label' => 'KVKK Onayı',
            'name' => 'kvkk_consent',
            'required' => 1,
            'css_class' => 'step-4',
            'help_text' => 'Kişisel verilerimin işlenmesine onay veriyorum.',
            'options' => json_encode([
                ['value' => '1', 'label' => 'KVKK metnini okudum ve kabul ediyorum.']
            ])
        ]
    ];
    
    // Alanları oluştur
    $order = 0;
    foreach ($fields as $field) {
        $order++;
        $field['order'] = $order;
        
        try {
            $fieldId = $fieldModel->createField($field);
            echo "<p style='color: green;'>✅ Alan oluşturuldu: {$field['label']} (ID: {$fieldId})</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Alan oluşturulamadı: {$field['label']} - " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Teklif Al formu başarıyla oluşturuldu!</h2>";
    echo "<p><strong>Form ID:</strong> {$formId}</p>";
    echo "<p><strong>Form Slug:</strong> teklif-al</p>";
    echo "<p><strong>Toplam Alan:</strong> " . count($fields) . "</p>";
    echo "<p><a href='/admin.php?page=forms&action=edit&id={$formId}'>→ Formu Admin Panelde Düzenle</a></p>";
    echo "<p><a href='/public/teklif-al'>→ Teklif Al Sayfasını Görüntüle</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Hata!</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
