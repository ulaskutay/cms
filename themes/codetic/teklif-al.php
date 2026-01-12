<?php
/**
 * Codetic Theme - Teklif Alma Sayfası
 * Çok adımlı form ile teklif alma sayfası
 * Referans: https://www.pentayazilim.com/teklif-al/
 */

// ThemeLoader yükle
if (!class_exists('ThemeLoader')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/ThemeLoader.php';
}
$themeLoader = ThemeLoader::getInstance();

// Form model'ini yükle
if (!class_exists('Form')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Form.php';
}
if (!class_exists('FormField')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/FormField.php';
}

$formModel = new Form();
$form = null;

// Form ID veya slug - ÖNCE sayfa custom field'larından al (modülden kaydedilen)
$formId = $customFields['quote_form_id'] ?? null;
$formSlug = $customFields['quote_form_slug'] ?? null;

// Form'u bul
if ($formId) {
    $form = $formModel->findWithFields($formId);
} elseif ($formSlug) {
    $form = $formModel->findBySlugWithFields($formSlug);
}

// Form bulunamadıysa varsayılan form oluştur
if (!$form || ($form['status'] ?? '') !== 'active') {
    $form = [
        'id' => 0,
        'name' => 'Teklif Al',
        'slug' => 'teklif-al',
        'status' => 'active',
        'success_message' => 'Talebiniz başarıyla alındı. En kısa sürede size dönüş yapacağız.',
        'fields' => []
    ];
}

// Page title ve meta
$pageTitle = $page['title'] ?? $form['name'] ?? 'Teklif Al';
$pageDescription = $page['excerpt'] ?? $page['meta_description'] ?? $form['description'] ?? 'Projeniz için detaylı teklif alın';

// ÖZEL FORM YAPISI - 4 Adım (Codetic'e Özel)

// Adım 1: Müşteri Tipi (Kart Seçimi - Otomatik geçiş)
$step1 = [
    'title' => 'Merhaba! Sizi tanıyalım',
    'subtitle' => 'Projenizi kim için planlıyorsunuz?',
    'stepNumber' => 1,
    'options' => [
        [
            'value' => 'company',
            'label' => 'Kurumsal Proje',
            'description' => 'Şirket, marka veya işletme için dijital çözümler.',
            'icon' => '🏢'
        ],
        [
            'value' => 'personal',
            'label' => 'Bireysel Proje',
            'description' => 'Kişisel site, blog veya portfolyo projesi.',
            'icon' => '🚀'
        ]
    ]
];

// Adım 2: Proje Kapsamı (Kategorili Checkbox Grid)
$step2 = [
    'title' => 'Hangi hizmetlerle ilgileniyorsunuz?',
    'subtitle' => 'İhtiyacınız olan hizmetleri seçin (birden fazla seçebilirsiniz)',
    'stepNumber' => 2,
    'categories' => [
        'web' => [
            'name' => 'Web & Yazılım',
            'icon' => '💻',
            'color' => 'from-blue-500/20 to-blue-600/10',
            'services' => [
                ['value' => 'kurumsal_web_sitesi', 'label' => 'Kurumsal Web Sitesi'],
                ['value' => 'e_ticaret_sitesi', 'label' => 'E-Ticaret Sitesi'],
                ['value' => 'ozel_yazilim', 'label' => 'Özel Yazılım Geliştirme'],
                ['value' => 'mobil_uygulama', 'label' => 'Mobil Uygulama'],
                ['value' => 'landing_page', 'label' => 'Landing Page'],
                ['value' => 'cms_kurulum', 'label' => 'CMS Kurulumu (WordPress vb.)']
            ]
        ],
        'social' => [
            'name' => 'Sosyal Medya',
            'icon' => '📱',
            'color' => 'from-pink-500/20 to-pink-600/10',
            'services' => [
                ['value' => 'sosyal_medya_yonetimi', 'label' => 'Sosyal Medya Yönetimi'],
                ['value' => 'instagram_reklam', 'label' => 'Instagram Reklam Yönetimi'],
                ['value' => 'meta_reklam', 'label' => 'Meta (Facebook) Reklam Yönetimi'],
                ['value' => 'sosyal_medya_icerik', 'label' => 'Sosyal Medya İçerik Üretimi'],
                ['value' => 'influencer_marketing', 'label' => 'Influencer Marketing']
            ]
        ],
        'ads' => [
            'name' => 'Reklam & SEO',
            'icon' => '📈',
            'color' => 'from-green-500/20 to-green-600/10',
            'services' => [
                ['value' => 'google_ads', 'label' => 'Google Reklam Yönetimi'],
                ['value' => 'google_alisveris', 'label' => 'Google Alışveriş Reklamları'],
                ['value' => 'e_ticaret_reklamlari', 'label' => 'E-Ticaret Reklamları'],
                ['value' => 'seo', 'label' => 'SEO (Arama Motoru Optimizasyonu)'],
                ['value' => 'youtube_reklam', 'label' => 'YouTube Reklam Yönetimi']
            ]
        ],
        'design' => [
            'name' => 'Tasarım & Marka',
            'icon' => '🎨',
            'color' => 'from-purple-500/20 to-purple-600/10',
            'services' => [
                ['value' => 'logo_tasarim', 'label' => 'Logo Tasarımı'],
                ['value' => 'kurumsal_kimlik', 'label' => 'Kurumsal Kimlik'],
                ['value' => 'ui_ux_tasarim', 'label' => 'UI/UX Tasarım'],
                ['value' => 'grafik_tasarim', 'label' => 'Grafik Tasarım'],
                ['value' => 'video_produksiyon', 'label' => 'Video Prodüksiyon']
            ]
        ],
        'other' => [
            'name' => 'Diğer Hizmetler',
            'icon' => '⚙️',
            'color' => 'from-orange-500/20 to-orange-600/10',
            'services' => [
                ['value' => 'hosting_domain', 'label' => 'Hosting & Domain'],
                ['value' => 'teknik_destek', 'label' => 'Teknik Destek & Bakım'],
                ['value' => 'danismanlik', 'label' => 'Dijital Danışmanlık'],
                ['value' => 'email_pazarlama', 'label' => 'E-Mail Pazarlama'],
                ['value' => 'icerik_yazarligi', 'label' => 'İçerik Yazarlığı']
            ]
        ]
    ]
];

// Adım 3: Proje Detayları
$step3 = [
    'title' => 'Projenizi biraz anlatın',
    'subtitle' => 'Ne kadar detay verirseniz, o kadar doğru teklif hazırlarız',
    'stepNumber' => 3,
    'fields' => [
        ['name' => 'current_site', 'label' => 'Mevcut Siteniz (varsa)', 'type' => 'text', 'placeholder' => 'örn: www.mevcutsite.com', 'required' => false],
        ['name' => 'reference_sites', 'label' => 'Beğendiğiniz Referans Siteler', 'type' => 'text', 'placeholder' => 'Beğendiğiniz 1-2 site linki yazabilirsiniz', 'required' => false],
        ['name' => 'budget_range', 'label' => 'Tahmini Bütçe Aralığı', 'type' => 'select', 'placeholder' => 'Seçiniz', 'required' => false, 'options' => [
            '' => 'Henüz belirlemedim',
            '5000-15000' => '₺5.000 - ₺15.000',
            '15000-30000' => '₺15.000 - ₺30.000',
            '30000-50000' => '₺30.000 - ₺50.000',
            '50000+' => '₺50.000 ve üzeri'
        ]],
        ['name' => 'timeline', 'label' => 'Projenin Tamamlanma Süresi', 'type' => 'select', 'placeholder' => 'Seçiniz', 'required' => false, 'options' => [
            '' => 'Esnek',
            'acil' => 'Acil (1-2 hafta)',
            '1ay' => '1 ay içinde',
            '2-3ay' => '2-3 ay içinde',
            'belirsiz' => 'Belirli bir süre yok'
        ]],
        ['name' => 'project_details', 'label' => 'Proje Hakkında Notlarınız', 'type' => 'textarea', 'placeholder' => 'Projenizle ilgili eklemek istediğiniz detaylar, özel istekler veya sorularınız...', 'required' => false]
    ]
];

// Adım 4: İletişim Bilgileri + KVKK
$step4 = [
    'title' => 'Son adım! İletişim bilgileriniz',
    'subtitle' => 'Teklifinizi hazırlayıp size ulaşalım',
    'stepNumber' => 4,
    'company_fields' => [
        ['name' => 'company_name', 'label' => 'Şirket / Marka Adı', 'type' => 'text', 'placeholder' => 'Şirket veya marka adınız', 'required' => true],
        ['name' => 'sector', 'label' => 'Sektör', 'type' => 'text', 'placeholder' => 'örn: Teknoloji, E-ticaret, Sağlık...', 'required' => false]
    ],
    'personal_fields' => [
        ['name' => 'fullname', 'label' => 'Ad Soyad', 'type' => 'text', 'placeholder' => 'Adınız Soyadınız', 'required' => true, 'half' => false],
        ['name' => 'email', 'label' => 'E-posta Adresi', 'type' => 'email', 'placeholder' => 'ornek@email.com', 'required' => true, 'half' => true],
        ['name' => 'phone', 'label' => 'Telefon', 'type' => 'tel', 'placeholder' => '05XX XXX XX XX', 'required' => true, 'half' => true],
        ['name' => 'preferred_contact', 'label' => 'Tercih Ettiğiniz İletişim Yöntemi', 'type' => 'select', 'placeholder' => 'Seçiniz', 'required' => false, 'half' => false, 'options' => [
            'phone' => 'Telefon ile arayın',
            'whatsapp' => 'WhatsApp mesajı',
            'email' => 'E-posta ile dönüş yapın'
        ]]
    ]
];

// Tüm adımlar (4 adım)
$steps = [$step1, $step2, $step3, $step4];
$totalSteps = count($steps);

// Layout değişkenlerini ayarla
if (!isset($title)) {
    $title = $page['meta_title'] ?? $pageTitle;
}
if (!isset($meta_description)) {
    $meta_description = $page['meta_description'] ?? $pageDescription;
}
if (!isset($meta_keywords)) {
    $meta_keywords = $page['meta_keywords'] ?? '';
}
$current_page = 'quote-request';

// Layout'u kullan
$layoutPath = __DIR__ . '/layouts/default.php';

// Content'i yakala
ob_start();
?>

<!-- Özel Teklif Formu -->
<section class="relative min-h-screen py-16 md:py-24 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 left-1/4 w-[600px] h-[600px] bg-primary/10 rounded-full blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] animate-pulse-slow" style="animation-delay: 1s;"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(19,127,236,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(19,127,236,0.03)_1px,transparent_1px)] bg-[size:64px_64px]" style="mask-image: linear-gradient(180deg, transparent 0%, black 10%, black 90%, transparent 100%); -webkit-mask-image: linear-gradient(180deg, transparent 0%, black 10%, black 90%, transparent 100%);"></div>
    </div>

    <div class="container max-w-[900px] w-full px-6 md:px-10 relative z-10 mx-auto">
        <!-- Header -->
        <div class="text-center mb-12 relative">
            <!-- Watching Characters -->
            <div class="flex items-end justify-center gap-4 mb-8 relative h-32 md:h-40">
                <!-- Purple Character -->
                <div class="quote-character quote-character-purple w-12 h-24 md:w-16 md:h-32 bg-gradient-to-b from-purple-500 to-purple-600 rounded-2xl md:rounded-3xl flex items-start justify-center pt-3 md:pt-4 relative z-10 shadow-lg hover:shadow-purple-500/50">
                    <div class="flex gap-1.5 md:gap-2">
                        <div class="quote-eye w-2 h-2.5 md:w-3 md:h-4 bg-white rounded-full flex items-center justify-center overflow-hidden">
                            <div class="quote-pupil w-1 h-1 md:w-1.5 md:h-1.5 bg-black rounded-full"></div>
                        </div>
                        <div class="quote-eye w-2 h-2.5 md:w-3 md:h-4 bg-white rounded-full flex items-center justify-center overflow-hidden">
                            <div class="quote-pupil w-1 h-1 md:w-1.5 md:h-1.5 bg-black rounded-full"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Orange Character -->
                <div class="quote-character quote-character-orange w-16 h-12 md:w-20 md:h-16 bg-gradient-to-b from-orange-400 to-orange-500 rounded-t-2xl md:rounded-t-3xl flex items-start justify-center pt-3 md:pt-3.5 relative z-0 -ml-4 md:-ml-6 shadow-lg hover:shadow-orange-500/50">
                    <div class="flex gap-1 md:gap-1.5">
                        <div class="quote-eye w-1.5 h-1.5 md:w-2 md:h-2 bg-black rounded-full"></div>
                        <div class="quote-eye w-1.5 h-1.5 md:w-2 md:h-2 bg-black rounded-full"></div>
                    </div>
                </div>
                
                <!-- Black Character -->
                <div class="quote-character quote-character-black w-10 h-16 md:w-12 md:h-20 bg-gradient-to-b from-slate-700 to-slate-800 rounded-2xl md:rounded-3xl flex items-start justify-center pt-2.5 md:pt-3 relative z-5 -ml-3 md:-ml-4 shadow-lg hover:shadow-slate-500/50">
                    <div class="flex gap-1 md:gap-1.5">
                        <div class="quote-eye w-1.5 h-2 md:w-2 md:h-2.5 bg-white rounded-full flex items-center justify-center overflow-hidden">
                            <div class="quote-pupil w-0.5 h-0.5 md:w-1 md:h-1 bg-black rounded-full"></div>
                        </div>
                        <div class="quote-eye w-1.5 h-2 md:w-2 md:h-2.5 bg-white rounded-full flex items-center justify-center overflow-hidden">
                            <div class="quote-pupil w-0.5 h-0.5 md:w-1 md:h-1 bg-black rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                <?php echo esc_html($pageTitle); ?>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto">
                <?php echo esc_html($pageDescription); ?>
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <?php foreach ($steps as $index => $step): ?>
                    <div class="flex items-center flex-1 <?php echo $index < count($steps) - 1 ? 'mr-2' : ''; ?>">
                        <div class="flex items-center flex-1">
                            <div class="quote-step-indicator flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300 <?php echo $index === 0 ? 'active' : ''; ?>" data-step="<?php echo $step['stepNumber']; ?>">
                                <span class="step-number"><?php echo $step['stepNumber']; ?></span>
                                <svg class="step-check hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <?php if ($index < count($steps) - 1): ?>
                                <div class="flex-1 h-0.5 mx-2 bg-slate-700 quote-progress-line" data-line="<?php echo $index + 1; ?>"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-gradient-to-br from-slate-900/95 to-slate-800/95 rounded-3xl backdrop-blur-xl border border-slate-700/50 shadow-2xl p-8 md:p-12">
            <form id="quote-form" class="quote-multistep-form" method="POST" action="<?php echo site_url('forms/submit'); ?>" data-form-id="<?php echo esc_attr($form['id']); ?>">
                
                <input type="hidden" name="_form_id" value="<?php echo esc_attr($form['id']); ?>">
                <input type="hidden" name="form_type" value="quote-request">
                
                <!-- Honeypot -->
                <input type="text" name="website_url" value="" tabindex="-1" autocomplete="off" style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">

                <!-- ADIM 1: Proje Tipi - Seçince otomatik geçiş -->
                <div class="quote-step-content" data-step="1">
                    <div class="text-center mb-10">
                        <h2 class="text-white text-2xl md:text-3xl font-bold mb-3"><?php echo esc_html($step1['title']); ?></h2>
                        <p class="text-slate-400 text-base md:text-lg"><?php echo esc_html($step1['subtitle']); ?></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($step1['options'] as $option): ?>
                            <label class="quote-project-type-card group relative flex flex-col items-center justify-center p-10 md:p-14 rounded-2xl border-2 border-slate-700 bg-gradient-to-br from-slate-800/50 to-slate-900/50 cursor-pointer transition-all duration-300 hover:border-primary hover:shadow-xl hover:shadow-primary/20 hover:scale-[1.03]">
                                <input type="radio" 
                                       name="project_type" 
                                       value="<?php echo esc_attr($option['value']); ?>"
                                       class="absolute opacity-0 pointer-events-none quote-project-type-input"
                                       required
                                       data-project-type="<?php echo esc_attr($option['value']); ?>">
                                
                                <!-- Icon -->
                                <div class="mb-5 text-7xl md:text-8xl transition-transform duration-300 group-hover:scale-110">
                                    <span class="inline-block"><?php echo $option['icon']; ?></span>
                                </div>
                                
                                <!-- Label -->
                                <h3 class="text-white text-xl md:text-2xl font-bold mb-2 text-center">
                                    <?php echo esc_html($option['label']); ?>
                                </h3>
                                
                                <!-- Description -->
                                <p class="text-slate-400 text-sm md:text-base text-center">
                                    <?php echo esc_html($option['description']); ?>
                                </p>
                                
                                <!-- Check Icon -->
                                <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-primary flex items-center justify-center opacity-0 transition-all duration-300 check-icon">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <!-- Adım Göstergesi -->
                    <div class="text-center mt-10 pt-6 border-t border-slate-700/50">
                        <div class="text-slate-500 text-sm">Adım <span class="text-primary font-bold">1</span> / <?php echo $totalSteps; ?></div>
                    </div>
                </div>

                <!-- ADIM 2: Hizmet Seçimi - Yeni Tasarım -->
                <div class="quote-step-content hidden" data-step="2">
                    <div class="text-center mb-10">
                        <h2 class="text-white text-2xl md:text-3xl font-bold mb-3"><?php echo esc_html($step2['title']); ?></h2>
                        <p class="text-slate-400 text-base md:text-lg"><?php echo esc_html($step2['subtitle']); ?></p>
                    </div>

                    <!-- Hizmet Kategorileri - Accordion Style -->
                    <div class="space-y-4">
                        <?php foreach ($step2['categories'] as $catKey => $category): ?>
                            <div class="quote-service-category rounded-2xl border border-slate-700/50 overflow-hidden bg-gradient-to-br <?php echo $category['color']; ?>">
                                <!-- Kategori Header -->
                                <button type="button" class="quote-category-toggle w-full flex items-center justify-between p-5 text-left hover:bg-white/5 transition-colors" data-category="<?php echo $catKey; ?>">
                                    <div class="flex items-center gap-4">
                                        <span class="text-3xl"><?php echo $category['icon']; ?></span>
                                        <div>
                                            <h3 class="text-white text-lg font-semibold"><?php echo esc_html($category['name']); ?></h3>
                                            <p class="text-slate-400 text-sm"><?php echo count($category['services']); ?> hizmet</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="quote-category-count text-primary font-bold text-lg hidden">0</span>
                                        <svg class="quote-category-arrow w-6 h-6 text-slate-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>
                                
                                <!-- Kategori İçeriği -->
                                <div class="quote-category-content hidden px-5 pb-5">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <?php foreach ($category['services'] as $service): ?>
                                            <label class="quote-service-item flex items-center gap-3 p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 cursor-pointer transition-all duration-200 hover:bg-slate-800 hover:border-primary/50">
                                                <input type="checkbox" 
                                                       name="services[]" 
                                                       value="<?php echo esc_attr($service['value']); ?>"
                                                       class="quote-service-checkbox w-5 h-5 rounded border-slate-600 bg-slate-900 text-primary focus:ring-primary focus:ring-offset-0 cursor-pointer"
                                                       data-category="<?php echo $catKey; ?>">
                                                <span class="text-white font-medium select-none"><?php echo esc_html($service['label']); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Seçilen Hizmetler Özeti -->
                    <div id="selected-services-summary" class="hidden mt-6 p-4 rounded-xl bg-primary/10 border border-primary/30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-medium">Seçilen hizmetler:</span>
                            <span id="selected-services-count" class="text-primary font-bold">0</span>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between mt-10 pt-6 border-t border-slate-700/50">
                        <button type="button" class="quote-prev-btn px-6 py-3 rounded-xl bg-slate-700/50 hover:bg-slate-700 text-white transition-all duration-200 flex items-center gap-2 font-medium" data-step="2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Geri
                        </button>
                        <div class="text-slate-500 text-sm">Adım <span class="text-primary font-bold">2</span> / <?php echo $totalSteps; ?></div>
                        <button type="button" class="quote-next-btn px-8 py-3 rounded-xl bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 text-white transition-all duration-200 flex items-center gap-2 font-semibold shadow-lg shadow-primary/20 hover:shadow-primary/30" data-step="2" data-next-step="3">
                            İleri
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ADIM 3: Proje Detayları -->
                <div class="quote-step-content hidden" data-step="3">
                    <div class="text-center mb-10">
                        <h2 class="text-white text-2xl md:text-3xl font-bold mb-3"><?php echo esc_html($step3['title']); ?></h2>
                        <p class="text-slate-400 text-base md:text-lg"><?php echo esc_html($step3['subtitle']); ?></p>
                    </div>

                    <div class="space-y-6">
                        <?php foreach ($step3['fields'] as $field): ?>
                            <div class="quote-form-field">
                                <label class="block text-white font-medium mb-2" for="<?php echo esc_attr($field['name']); ?>">
                                    <?php echo esc_html($field['label']); ?>
                                    <?php if (!empty($field['required'])): ?>
                                        <span class="text-red-400">*</span>
                                    <?php endif; ?>
                                </label>
                                <?php if ($field['type'] === 'textarea'): ?>
                                    <textarea id="<?php echo esc_attr($field['name']); ?>"
                                              name="<?php echo esc_attr($field['name']); ?>" 
                                              placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                                              rows="4"
                                              class="w-full px-5 py-4 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"
                                              <?php echo !empty($field['required']) ? 'required' : ''; ?>></textarea>
                                <?php elseif ($field['type'] === 'select' && !empty($field['options'])): ?>
                                    <select id="<?php echo esc_attr($field['name']); ?>"
                                            name="<?php echo esc_attr($field['name']); ?>"
                                            class="w-full px-5 py-4 rounded-xl bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none cursor-pointer"
                                            style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%2394a3b8%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5em;"
                                            <?php echo !empty($field['required']) ? 'required' : ''; ?>>
                                        <?php foreach ($field['options'] as $value => $label): ?>
                                            <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input type="<?php echo esc_attr($field['type']); ?>" 
                                           id="<?php echo esc_attr($field['name']); ?>"
                                           name="<?php echo esc_attr($field['name']); ?>" 
                                           placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                                           class="w-full px-5 py-4 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                           <?php echo !empty($field['required']) ? 'required' : ''; ?>>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between mt-10 pt-6 border-t border-slate-700/50">
                        <button type="button" class="quote-prev-btn px-6 py-3 rounded-xl bg-slate-700/50 hover:bg-slate-700 text-white transition-all duration-200 flex items-center gap-2 font-medium" data-step="3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Geri
                        </button>
                        <div class="text-slate-500 text-sm">Adım <span class="text-primary font-bold">3</span> / <?php echo $totalSteps; ?></div>
                        <button type="button" class="quote-next-btn px-8 py-3 rounded-xl bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 text-white transition-all duration-200 flex items-center gap-2 font-semibold shadow-lg shadow-primary/20 hover:shadow-primary/30" data-step="3" data-next-step="4">
                            İleri
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ADIM 4: İletişim Bilgileri -->
                <div class="quote-step-content hidden" data-step="4">
                    <div class="text-center mb-10">
                        <h2 class="text-white text-2xl md:text-3xl font-bold mb-3"><?php echo esc_html($step4['title']); ?></h2>
                        <p class="text-slate-400 text-base md:text-lg"><?php echo esc_html($step4['subtitle']); ?></p>
                    </div>

                    <!-- Şirket Bilgileri (Şirket seçildiğinde görünür) -->
                    <div id="company-fields" class="hidden mb-8 p-6 rounded-2xl bg-slate-800/30 border border-slate-700/50">
                        <h3 class="text-white text-lg font-semibold mb-5 flex items-center gap-2">
                            <span class="text-2xl">🏢</span> Şirket Bilgileri
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($step4['company_fields'] as $field): ?>
                                <div class="quote-form-field">
                                    <label class="block text-white font-medium mb-2" for="<?php echo esc_attr($field['name']); ?>">
                                        <?php echo esc_html($field['label']); ?>
                                        <?php if ($field['required']): ?>
                                            <span class="text-red-400">*</span>
                                        <?php endif; ?>
                                    </label>
                                    <input type="<?php echo esc_attr($field['type']); ?>" 
                                           id="<?php echo esc_attr($field['name']); ?>"
                                           name="<?php echo esc_attr($field['name']); ?>" 
                                           placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                                           class="w-full px-5 py-4 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Kişisel Bilgiler -->
                    <div class="p-6 rounded-2xl bg-slate-800/30 border border-slate-700/50">
                        <h3 class="text-white text-lg font-semibold mb-5 flex items-center gap-2">
                            <span class="text-2xl">👤</span> İletişim Bilgileriniz
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($step4['personal_fields'] as $field): ?>
                                <div class="quote-form-field <?php echo isset($field['half']) && $field['half'] ? '' : 'md:col-span-2'; ?>">
                                    <label class="block text-white font-medium mb-2" for="<?php echo esc_attr($field['name']); ?>">
                                        <?php echo esc_html($field['label']); ?>
                                        <?php if (!empty($field['required'])): ?>
                                            <span class="text-red-400">*</span>
                                        <?php endif; ?>
                                    </label>
                                    <?php if ($field['type'] === 'select' && !empty($field['options'])): ?>
                                        <select id="<?php echo esc_attr($field['name']); ?>"
                                                name="<?php echo esc_attr($field['name']); ?>"
                                                class="w-full px-5 py-4 rounded-xl bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all appearance-none cursor-pointer"
                                                style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%2394a3b8%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.5em;"
                                                <?php echo !empty($field['required']) ? 'required' : ''; ?>>
                                            <option value=""><?php echo esc_html($field['placeholder']); ?></option>
                                            <?php foreach ($field['options'] as $value => $label): ?>
                                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <input type="<?php echo esc_attr($field['type']); ?>" 
                                               id="<?php echo esc_attr($field['name']); ?>"
                                               name="<?php echo esc_attr($field['name']); ?>" 
                                               placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                                               class="w-full px-5 py-4 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                               <?php echo !empty($field['required']) ? 'required' : ''; ?>>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- KVKK -->
                    <div class="mt-6">
                        <label class="flex items-start gap-4 p-5 rounded-xl bg-slate-800/30 border border-slate-700/50 hover:bg-slate-800/50 cursor-pointer transition-all group">
                            <div class="relative flex items-center justify-center mt-0.5">
                                <input type="checkbox" 
                                       name="kvkk" 
                                       value="1"
                                       class="peer sr-only"
                                       required>
                                <div class="w-6 h-6 rounded-lg border-2 border-slate-600 bg-slate-900 flex items-center justify-center transition-all peer-checked:bg-primary peer-checked:border-primary">
                                    <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-slate-300 text-sm leading-relaxed group-hover:text-white transition-colors">
                                <a href="/kvkk" target="_blank" class="text-primary hover:underline">KVKK Aydınlatma Metni</a>'ni okudum ve kişisel verilerimin işlenmesini kabul ediyorum.
                            </span>
                        </label>
                    </div>

                    <!-- Error Message -->
                    <div id="quote-form-error" class="hidden mt-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-center"></div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between mt-10 pt-6 border-t border-slate-700/50">
                        <button type="button" class="quote-prev-btn px-6 py-3 rounded-xl bg-slate-700/50 hover:bg-slate-700 text-white transition-all duration-200 flex items-center gap-2 font-medium" data-step="4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Geri
                        </button>
                        <div class="text-slate-500 text-sm">Adım <span class="text-primary font-bold">4</span> / <?php echo $totalSteps; ?></div>
                        <button type="submit" class="quote-submit-btn px-10 py-4 rounded-xl bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold transition-all duration-200 flex items-center gap-3 shadow-lg shadow-green-500/20 hover:shadow-green-500/30 text-lg">
                            <span class="submit-text">Teklif İste</span>
                            <span class="submit-loading hidden">
                                <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <svg class="w-6 h-6 submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Success Message - Form dışında -->
            <div id="quote-form-success" class="hidden text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-500/20 mb-8 animate-bounce-slow">
                    <svg class="w-12 h-12 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-white text-3xl font-bold mb-4">Talebiniz Alındı! 🎉</h3>
                <p class="text-slate-400 text-lg mb-8 max-w-md mx-auto">
                    <?php echo esc_html($form['success_message'] ?? 'En kısa sürede size geri dönüş yapacağız.'); ?>
                </p>
                <a href="/" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 text-white transition-all duration-200 font-semibold shadow-lg shadow-primary/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Anasayfaya Dön
                </a>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse-slow {
    0%, 100% { opacity: 0.4; }
    50% { opacity: 0.8; }
}

@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

/* Character Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes blink {
    0%, 90%, 100% { transform: scaleY(1); }
    95% { transform: scaleY(0.1); }
}

.quote-character {
    animation: float 3s ease-in-out infinite;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.quote-character:hover {
    transform: translateY(-5px) scale(1.05);
}

.quote-character-purple { animation-delay: 0s; }
.quote-character-orange { animation-delay: 0.5s; }
.quote-character-black { animation-delay: 1s; }

.quote-eye {
    animation: blink 4s ease-in-out infinite;
}

/* Step Indicator Styles */
.quote-step-indicator {
    background: transparent;
    border-color: rgb(71 85 105);
    color: rgb(148 163 184);
}

.quote-step-indicator.active {
    background: linear-gradient(135deg, var(--primary-color, #137fec), var(--primary-color-dark, #0d5bab));
    border-color: var(--primary-color, #137fec);
    color: white;
    box-shadow: 0 0 20px rgba(19, 127, 236, 0.4);
}

.quote-step-indicator.completed {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-color: #22c55e;
    color: white;
}

.quote-progress-line.completed {
    background: linear-gradient(90deg, #22c55e, var(--primary-color, #137fec));
}

/* Project Type Card Selection */
.quote-project-type-card:has(input:checked) {
    border-color: var(--primary-color, #137fec);
    background: linear-gradient(135deg, rgba(19, 127, 236, 0.15), rgba(19, 127, 236, 0.05));
    box-shadow: 0 10px 40px -10px rgba(19, 127, 236, 0.4);
    transform: scale(1.02);
}

.quote-project-type-card:has(input:checked) .check-icon {
    opacity: 1 !important;
}

/* Service Item Selection */
.quote-service-item:has(input:checked) {
    border-color: var(--primary-color, #137fec);
    background: rgba(19, 127, 236, 0.15);
}

.quote-service-item:has(input:checked) span:last-child {
    color: var(--primary-color, #137fec);
}

/* Checkbox Styling */
.quote-service-checkbox {
    appearance: none;
    -webkit-appearance: none;
    width: 22px;
    height: 22px;
    border: 2px solid rgb(71 85 105);
    border-radius: 6px;
    background: rgb(15 23 42);
    cursor: pointer;
    position: relative;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.quote-service-checkbox:hover {
    border-color: var(--primary-color, #137fec);
}

.quote-service-checkbox:checked {
    background: var(--primary-color, #137fec);
    border-color: var(--primary-color, #137fec);
}

.quote-service-checkbox:checked::after {
    content: '';
    position: absolute;
    left: 6px;
    top: 2px;
    width: 6px;
    height: 12px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.quote-service-checkbox:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(19, 127, 236, 0.3);
}

/* Category Toggle */
.quote-category-toggle[aria-expanded="true"] .quote-category-arrow {
    transform: rotate(180deg);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quote-form');
    if (!form) return;
    
    const stepContents = form.querySelectorAll('.quote-step-content');
    const indicators = document.querySelectorAll('.quote-step-indicator');
    const progressLines = document.querySelectorAll('.quote-progress-line');
    const successEl = document.getElementById('quote-form-success');
    const errorEl = document.getElementById('quote-form-error');
    
    let currentStep = 1;
    const totalSteps = stepContents.length;
    
    // İlk adımı göster
    showStep(1);
    
    // Proje tipi seçimi - Otomatik geçiş
    const projectTypeInputs = form.querySelectorAll('.quote-project-type-input');
    const companyFields = document.getElementById('company-fields');
    
    projectTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Şirket alanlarını göster/gizle
            if (this.value === 'company' && companyFields) {
                companyFields.classList.remove('hidden');
                companyFields.querySelector('input[name="company_name"]').required = true;
            } else if (companyFields) {
                companyFields.classList.add('hidden');
                companyFields.querySelector('input[name="company_name"]').required = false;
            }
            
            // Kart seçildiğinde animasyon
            const card = this.closest('.quote-project-type-card');
            card.classList.add('ring-2', 'ring-primary');
            
            // 500ms sonra otomatik 2. adıma geç
            setTimeout(() => {
                showStep(2);
            }, 500);
        });
    });
    
    // Kategori açma/kapama
    document.querySelectorAll('.quote-category-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Tüm kategorileri kapat
            document.querySelectorAll('.quote-category-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.quote-category-toggle').forEach(t => t.setAttribute('aria-expanded', 'false'));
            
            // Tıklanan kategoriyi aç/kapa
            if (!isExpanded) {
                content.classList.remove('hidden');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });
    
    // İlk kategoriyi varsayılan olarak aç
    const firstToggle = document.querySelector('.quote-category-toggle');
    if (firstToggle) {
        firstToggle.click();
    }
    
    // Hizmet seçimi sayacı
    const serviceCheckboxes = form.querySelectorAll('.quote-service-checkbox');
    const summaryEl = document.getElementById('selected-services-summary');
    const countEl = document.getElementById('selected-services-count');
    
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateServiceCount();
            updateCategoryCount(this.dataset.category);
        });
    });
    
    function updateServiceCount() {
        const checked = form.querySelectorAll('.quote-service-checkbox:checked').length;
        if (checked > 0) {
            summaryEl.classList.remove('hidden');
            countEl.textContent = checked;
        } else {
            summaryEl.classList.add('hidden');
        }
    }
    
    function updateCategoryCount(category) {
        const toggle = document.querySelector(`[data-category="${category}"]`);
        if (!toggle) return;
        
        const checked = form.querySelectorAll(`.quote-service-checkbox[data-category="${category}"]:checked`).length;
        const countSpan = toggle.querySelector('.quote-category-count');
        
        if (checked > 0) {
            countSpan.textContent = checked;
            countSpan.classList.remove('hidden');
        } else {
            countSpan.classList.add('hidden');
        }
    }
    
    // Step navigation
    function showStep(stepNumber) {
        stepContents.forEach(step => {
            step.classList.add('hidden');
        });
        
        const currentStepEl = form.querySelector(`[data-step="${stepNumber}"]`);
        if (currentStepEl) {
            currentStepEl.classList.remove('hidden');
        }
        
        indicators.forEach(indicator => {
            const stepNum = parseInt(indicator.dataset.step);
            indicator.classList.remove('active', 'completed');
            
            const stepNumber_el = indicator.querySelector('.step-number');
            const stepCheck = indicator.querySelector('.step-check');
            
            if (stepNum < stepNumber) {
                indicator.classList.add('completed');
                if (stepNumber_el) stepNumber_el.classList.add('hidden');
                if (stepCheck) stepCheck.classList.remove('hidden');
            } else if (stepNum === stepNumber) {
                indicator.classList.add('active');
                if (stepNumber_el) stepNumber_el.classList.remove('hidden');
                if (stepCheck) stepCheck.classList.add('hidden');
            } else {
                if (stepNumber_el) stepNumber_el.classList.remove('hidden');
                if (stepCheck) stepCheck.classList.add('hidden');
            }
        });
        
        progressLines.forEach((line, index) => {
            if (index + 1 < stepNumber) {
                line.classList.add('completed');
            } else {
                line.classList.remove('completed');
            }
        });
        
        currentStep = stepNumber;
        
        // Scroll to top smoothly
        const formContainer = document.querySelector('.bg-gradient-to-br.from-slate-900\\/95');
        if (formContainer) {
            formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    // Next button
    form.querySelectorAll('.quote-next-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const stepNumber = parseInt(this.dataset.step);
            const nextStep = parseInt(this.dataset.nextStep);
            
            if (nextStep <= totalSteps) {
                showStep(nextStep);
            }
        });
    });
    
    // Prev button
    form.querySelectorAll('.quote-prev-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const stepNumber = parseInt(this.dataset.step);
            const prevStep = stepNumber - 1;
            
            if (prevStep >= 1) {
                showStep(prevStep);
            }
        });
    });
    
    // Validate step
    function validateStep(stepNumber) {
        const stepContent = form.querySelector(`[data-step="${stepNumber}"]`);
        if (!stepContent) return true;
        
        const fields = stepContent.querySelectorAll('[required]');
        let isValid = true;
        
        fields.forEach(field => {
            field.classList.remove('border-red-500');
            
            if (field.type === 'checkbox') {
                if (!field.checked) {
                    isValid = false;
                    field.closest('label')?.classList.add('border-red-500');
                }
            } else if (field.type === 'radio') {
                const radios = stepContent.querySelectorAll(`input[name="${field.name}"]`);
                const checked = Array.from(radios).some(r => r.checked);
                if (!checked) {
                    isValid = false;
                    radios.forEach(r => r.closest('label')?.classList.add('border-red-500'));
                }
            } else if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            }
        });
        
        return isValid;
    }
    
    // Form submit
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate last step
        if (!validateStep(currentStep)) {
            errorEl.textContent = 'Lütfen tüm zorunlu alanları doldurun';
            errorEl.classList.remove('hidden');
            setTimeout(() => errorEl.classList.add('hidden'), 5000);
            return;
        }
        
        const submitBtn = form.querySelector('.quote-submit-btn');
        const submitText = submitBtn.querySelector('.submit-text');
        const submitLoading = submitBtn.querySelector('.submit-loading');
        const submitIcon = submitBtn.querySelector('.submit-icon');
        
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitIcon.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        errorEl.classList.add('hidden');
        
        try {
            const formData = new FormData(form);
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Sunucudan geçersiz yanıt alındı');
            }
            
            const result = await response.json();
            
            if (result.success) {
                form.classList.add('hidden');
                successEl.classList.remove('hidden');
            } else {
                throw new Error(result.message || 'Form gönderilirken bir hata oluştu');
            }
        } catch (error) {
            console.error('Form submit error:', error);
            errorEl.textContent = error.message || 'Form gönderilirken bir hata oluştu. Lütfen tekrar deneyin.';
            errorEl.classList.remove('hidden');
            
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitIcon.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        }
    });
    
    // Clear error styling on input
    form.querySelectorAll('input, textarea, select').forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('border-red-500');
            this.closest('label')?.classList.remove('border-red-500');
        });
        field.addEventListener('change', function() {
            this.classList.remove('border-red-500');
            this.closest('label')?.classList.remove('border-red-500');
        });
    });
});
</script>

<?php
$content = ob_get_clean();

// Layout'u include et
if (file_exists($layoutPath)) {
    include $layoutPath;
} else {
    echo $content;
}
?>
