<?php
/**
 * Home View - PHP Component System
 * Section verileri veritabanından okunur, yoksa varsayılan değerler kullanılır
 */

// ThemeManager'ı yükle
if (!class_exists('ThemeManager')) {
    require_once dirname(dirname(dirname(__DIR__))) . '/core/ThemeManager.php';
}
$themeManager = ThemeManager::getInstance();

// Section verilerini veritabanından al
$sections = $themeManager->getPageSections('home');
$sectionsMap = [];
foreach ($sections as $s) {
    $sectionsMap[$s['section_id']] = $s;
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

// Section verisini al (veritabanı veya varsayılan)
function getSectionData($sectionId, $sectionsMap, $defaultSections) {
    $dbSection = $sectionsMap[$sectionId] ?? null;
    $default = $defaultSections[$sectionId] ?? [];
    
    return [
        'title' => !empty($dbSection['title']) ? $dbSection['title'] : ($default['title'] ?? ''),
        'subtitle' => !empty($dbSection['subtitle']) ? $dbSection['subtitle'] : ($default['subtitle'] ?? ''),
        'content' => !empty($dbSection['content']) ? $dbSection['content'] : ($default['content'] ?? ''),
        'settings' => !empty($dbSection['settings']) ? $dbSection['settings'] : ($default['settings'] ?? []),
        'items' => !empty($dbSection['items']) ? $dbSection['items'] : ($default['items'] ?? []),
        'is_active' => $dbSection['is_active'] ?? 1
    ];
}

// Section'ın görünür olup olmadığını kontrol et
function isSectionActive($sectionId, $sectionsMap) {
    if (isset($sectionsMap[$sectionId]) && isset($sectionsMap[$sectionId]['is_active'])) {
        return (bool)$sectionsMap[$sectionId]['is_active'];
    }
    return true; // Veritabanında kayıt yoksa veya is_active tanımlı değilse varsayılan olarak göster
}

// Content section başlat
$renderer->startSection('content');
?>

<!-- Slider Section -->
<section class="slider-section">
    <?php if (isset($slider) && !empty($slider['items'])): ?>
        <?php $renderer->component('slider', ['slider' => $slider]); ?>
    <?php endif; ?>
</section>

<?php 
// Hero Section - Tema dosyasından render ediliyor (themes/codetic/home.php)
// Burada duplicate render etmiyoruz, tema dosyası zaten hero'yu render ediyor
?>

<?php 
// Features Section
$featuresData = getSectionData('features', $sectionsMap, $defaultSections);
if (isSectionActive('features', $sectionsMap)):
?>
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($featuresData['title']); ?></h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto"><?php echo htmlspecialchars($featuresData['subtitle']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featuresData['items'] as $item): ?>
            <div class="bg-gray-50 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-3xl text-purple-600"><?php echo htmlspecialchars($item['icon'] ?? 'star'); ?></span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($item['description'] ?? ''); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
// About Section
$aboutData = getSectionData('about', $sectionsMap, $defaultSections);
if (isSectionActive('about', $sectionsMap)):
?>
<section class="py-20 bg-gradient-to-br from-purple-600 to-purple-800 text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-purple-200 font-medium"><?php echo htmlspecialchars($aboutData['subtitle']); ?></span>
                <h2 class="text-3xl lg:text-4xl font-bold mt-2 mb-6"><?php echo htmlspecialchars($aboutData['title']); ?></h2>
                <p class="text-purple-100 text-lg leading-relaxed"><?php echo nl2br(htmlspecialchars($aboutData['content'])); ?></p>
            </div>
            <div class="relative">
                <div class="aspect-square bg-white/10 backdrop-blur rounded-3xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-white/30" style="font-size: 12rem;">business</span>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
// Testimonials Section
$testimonialsData = getSectionData('testimonials', $sectionsMap, $defaultSections);
if (isSectionActive('testimonials', $sectionsMap)):
?>
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($testimonialsData['title']); ?></h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto"><?php echo htmlspecialchars($testimonialsData['subtitle']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($testimonialsData['items'] as $item): ?>
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-1 mb-4">
                    <?php for ($i = 0; $i < ($item['rating'] ?? 5); $i++): ?>
                    <span class="material-symbols-outlined text-yellow-400">star</span>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600 mb-6 italic">"<?php echo htmlspecialchars($item['content'] ?? ''); ?>"</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600">person</span>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['name'] ?? ''); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($item['role'] ?? ''); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
// CTA Section
$ctaData = getSectionData('cta', $sectionsMap, $defaultSections);
if (isSectionActive('cta', $sectionsMap)):
?>
<section class="py-20 bg-gradient-to-r from-purple-600 to-emerald-500">
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4"><?php echo htmlspecialchars($ctaData['title']); ?></h2>
        <p class="text-xl text-white/80 mb-8"><?php echo htmlspecialchars($ctaData['subtitle']); ?></p>
        <?php if (!empty($ctaData['settings']['button_text'])): ?>
        <a href="<?php echo htmlspecialchars($ctaData['settings']['button_link'] ?? '/contact'); ?>" class="inline-flex items-center gap-2 bg-white text-purple-600 font-semibold px-8 py-4 rounded-full hover:bg-gray-100 transition-all duration-200 transform hover:scale-105 shadow-lg">
            <?php echo htmlspecialchars($ctaData['settings']['button_text']); ?>
            <span class="material-symbols-outlined">arrow_forward</span>
        </a>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php
$renderer->endSection();

// Styles section başlat
$renderer->startSection('styles');
?>
<style>
    /* Slider Section */
    .slider-section {
        width: 100%;
        position: relative;
        margin-bottom: 0;
    }
</style>
<?php
$renderer->endSection();
?>
