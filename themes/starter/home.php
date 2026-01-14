<?php
/**
 * Starter Theme - Home Page
 * Section verileri veritabanından okunur, yoksa varsayılan değerler kullanılır
 */

// Section verilerini veritabanından al
$sections = $themeLoader->getPageSections('home');
$sectionsMap = [];
$seenSectionIds = []; // Duplicate kontrolü için
foreach ($sections as $s) {
    $sectionId = $s['section_id'] ?? '';
    // Sadece benzersiz section_id'leri ekle (duplicate'leri önle)
    if (!empty($sectionId) && !isset($seenSectionIds[$sectionId])) {
        $sectionsMap[$sectionId] = $s;
        $seenSectionIds[$sectionId] = true;
    }
}

// Varsayılan section verileri
$defaultSections = [
    'hero' => [
        'title' => 'Modern & Minimal Tasarım',
        'subtitle' => 'Web sitenizi profesyonel bir görünüme kavuşturun. Starter Theme ile kolayca özelleştirin ve yönetin.',
        'settings' => [
            'button_text' => 'Hemen Başla',
            'button_link' => '/contact',
            'secondary_button_text' => 'Keşfet',
            'secondary_button_link' => '#features'
        ]
    ],
    'features' => [
        'title' => 'Neden Bizi Tercih Etmelisiniz?',
        'subtitle' => 'Müşterilerimize en iyi deneyimi sunmak için çalışıyoruz.',
        'items' => [
            ['icon' => 'rocket_launch', 'title' => 'Hızlı Performans', 'description' => 'Optimize edilmiş kod yapısı ile yüksek performans.'],
            ['icon' => 'palette', 'title' => 'Modern Tasarım', 'description' => 'Güncel trendlere uygun şık ve modern görünüm.'],
            ['icon' => 'devices', 'title' => 'Responsive', 'description' => 'Tüm cihazlarda mükemmel görünüm.'],
            ['icon' => 'security', 'title' => 'Güvenli', 'description' => 'En güncel güvenlik standartları.'],
            ['icon' => 'support_agent', 'title' => '7/24 Destek', 'description' => 'Her zaman yanınızda olan destek ekibi.'],
            ['icon' => 'settings', 'title' => 'Kolay Yönetim', 'description' => 'Kullanıcı dostu admin paneli.']
        ]
    ],
    'about' => [
        'title' => 'Deneyim ve Kalite',
        'subtitle' => 'Hakkımızda',
        'content' => 'Yılların deneyimi ile müşterilerimize en kaliteli hizmeti sunuyoruz. Profesyonel ekibimiz ile projelerinizi hayata geçiriyoruz. Müşteri memnuniyeti bizim için en önemli önceliktir.'
    ],
    'testimonials' => [
        'title' => 'Müşterilerimiz Ne Diyor?',
        'subtitle' => 'Birlikte çalıştığımız müşterilerimizden geri bildirimler.',
        'items' => [
            [
                'name' => 'Ahmet Yılmaz',
                'role' => 'CEO, TechCorp',
                'content' => 'Harika bir deneyimdi. Profesyonel yaklaşımları ve kaliteli işleri ile beklentilerimizi aştılar.',
                'rating' => 5
            ],
            [
                'name' => 'Elif Demir',
                'role' => 'Marketing Manager',
                'content' => 'Projemiz zamanında ve bütçe dahilinde tamamlandı. Kesinlikle tekrar çalışmak isteriz.',
                'rating' => 5
            ],
            [
                'name' => 'Mehmet Kara',
                'role' => 'Founder, StartupX',
                'content' => 'İletişimleri çok güçlü. Her adımda bilgilendirildik ve sonuç mükemmel oldu.',
                'rating' => 5
            ]
        ]
    ],
    'cta' => [
        'title' => 'Projenizi Hayata Geçirelim',
        'subtitle' => 'Hemen iletişime geçin ve size özel çözümlerimizi keşfedin.',
        'settings' => [
            'button_text' => 'Bize Ulaşın',
            'button_link' => '/contact'
        ]
    ]
];

// Section render fonksiyonu
function renderHomeSection($sectionId, $sectionsMap, $defaultSections, $themeLoader) {
    // Veritabanında kayıtlı ve aktif değilse gösterme
    if (isset($sectionsMap[$sectionId]) && isset($sectionsMap[$sectionId]['is_active']) && !$sectionsMap[$sectionId]['is_active']) {
        return;
    }
    
    // Veritabanı verisini al, yoksa varsayılanı kullan
    $dbSection = $sectionsMap[$sectionId] ?? null;
    $default = $defaultSections[$sectionId] ?? [];
    
    // Veriyi birleştir
    $section = [
        'title' => !empty($dbSection['title']) ? $dbSection['title'] : ($default['title'] ?? ''),
        'subtitle' => !empty($dbSection['subtitle']) ? $dbSection['subtitle'] : ($default['subtitle'] ?? ''),
        'content' => !empty($dbSection['content']) ? $dbSection['content'] : ($default['content'] ?? ''),
        'settings' => !empty($dbSection['settings']) ? $dbSection['settings'] : ($default['settings'] ?? []),
        'items' => !empty($dbSection['items']) ? $dbSection['items'] : ($default['items'] ?? [])
    ];
    
    echo $themeLoader->renderComponent($sectionId, ['section' => $section]);
}

// Section'ları sıralı render et
$sectionOrder = ['hero', 'features', 'about', 'testimonials', 'cta'];

// Veritabanında sıralama varsa onu kullan
if (!empty($sections)) {
    $orderedSections = [];
    $seenInOrder = []; // Duplicate kontrolü için
    foreach ($sections as $s) {
        $sectionId = $s['section_id'] ?? '';
        if (!empty($sectionId) && isset($defaultSections[$sectionId]) && !isset($seenInOrder[$sectionId])) {
            $orderedSections[] = $sectionId;
            $seenInOrder[$sectionId] = true;
        }
    }
    // Eksik olanları ekle (duplicate kontrolü ile)
    foreach ($sectionOrder as $sid) {
        if (!isset($seenInOrder[$sid])) {
            $orderedSections[] = $sid;
            $seenInOrder[$sid] = true;
        }
    }
    $sectionOrder = $orderedSections;
}

// Section'ları render et (duplicate kontrolü ile)
$renderedSections = []; // Render edilen section'ları takip et
foreach ($sectionOrder as $sectionId) {
    // Her section sadece bir kez render edilsin
    if (!isset($renderedSections[$sectionId])) {
        renderHomeSection($sectionId, $sectionsMap, $defaultSections, $themeLoader);
        $renderedSections[$sectionId] = true;
    }
}
