<?php
/**
 * Codetic Theme - Home Page
 * Section verileri veritabanından okunur, yoksa varsayılan değerler kullanılır
 */

// Section verilerini veritabanından al
$sections = $themeLoader->getPageSections('home');
$sectionsMap = [];
foreach ($sections as $s) {
    $sectionsMap[$s['section_id']] = $s;
}

// Varsayılan section verileri
$defaultSections = [
    'hero' => [
        'title' => __('Dijital Dönüşümde Öncü'),
        'subtitle' => __('Yenilikçi çözümlerimizle işletmenizi dijital dünyada bir adım öne taşıyın. Profesyonel ekibimiz ile projelerinizi hayata geçirin.'),
        'settings' => [
            'button_text' => __('Hemen Başla'),
            'button_link' => '/contact',
            'secondary_button_text' => __('Daha Fazla Bilgi'),
            'secondary_button_link' => '/about'
        ]
    ],
    'glowing-features' => [
        'title' => __('Özelliklerimiz'),
        'subtitle' => __('Yenilikçi çözümlerimizle işletmenizi dijital dünyada bir adım öne taşıyın.'),
        'settings' => [
            'badge' => __('Neden Biz?')
        ],
        'items' => [
            [
                'icon' => 'rocket',
                'title' => __('Hızlı Geliştirme'),
                'description' => __('Modern araçlar ve metodolojilerle projelerinizi hızla hayata geçiriyoruz. Agile yaklaşımımızla sürekli değer üretiyoruz.'),
                'gradient' => 'from-violet-500 to-purple-600'
            ],
            [
                'icon' => 'shield',
                'title' => __('Güvenli Altyapı'),
                'description' => __('En güncel güvenlik standartları ve best practice\'ler ile verilerinizi koruyoruz. SSL, şifreleme ve düzenli güvenlik taramaları.'),
                'gradient' => 'from-emerald-500 to-teal-600'
            ],
            [
                'icon' => 'code',
                'title' => __('Temiz Kod'),
                'description' => __('Okunabilir, sürdürülebilir ve ölçeklenebilir kod yazıyoruz. SOLID prensipleri ve modern mimari desenler kullanıyoruz.'),
                'gradient' => 'from-blue-500 to-cyan-600'
            ],
            [
                'icon' => 'zap',
                'title' => __('Yüksek Performans'),
                'description' => __('Optimize edilmiş kod, CDN entegrasyonu ve caching stratejileri ile maksimum hız sağlıyoruz.'),
                'gradient' => 'from-amber-500 to-orange-600'
            ],
            [
                'icon' => 'users',
                'title' => __('7/24 Destek'),
                'description' => __('Uzman ekibimiz her zaman yanınızda. Teknik destek, danışmanlık ve eğitim hizmetleri sunuyoruz.'),
                'gradient' => 'from-pink-500 to-rose-600'
            ]
        ]
    ],
    'dashboard-showcase' => [
        'title' => __('Güçlü Yönetim Paneli'),
        'subtitle' => __('Tek bir yerden tüm içeriklerinizi yönetin'),
        'description' => __('Modern ve kullanıcı dostu arayüzümüz ile web sitenizi, içeriklerinizi ve müşterilerinizi kolayca yönetin. Gerçek zamanlı istatistikler, kolay içerik düzenleme ve güçlü SEO araçları.'),
        'settings' => [
            'badge' => __('Yönetim Paneli')
        ],
        'features' => [
            __('Sürükle-bırak içerik düzenleme'),
            __('Gerçek zamanlı analitik'),
            __('SEO optimizasyon araçları'),
            __('Çoklu dil desteği'),
            __('Otomatik yedekleme')
        ]
    ],
    'lamp' => [
        'title' => __('Fikirlerinizi Hayata Geçirelim'),
        'subtitle' => __('"Yapay Zeka Destekli Yenilikçi" çözümlerimizle işletmenizi dijital dünyada bir adım öne taşıyın.'),
        'settings' => []
    ],
    'feature-tabs' => [
        'title' => __('Yapay Zeka Destekli, Ölçeklenebilir Web Altyapısı'),
        'subtitle' => __('Codetic altyapısı; yapay zeka destekli optimizasyon, yüksek performanslı kod yapısı ve esnek mimarisiyle uzun vadeli dijital çözümler sunar.'),
        'settings' => [
            'badge' => 'codetic.co'
        ],
        'tabs' => [
            [
                'value' => 'tab-1',
                'icon' => 'zap',
                'label' => __('Yapay Zeka Destekli'),
                'content' => [
                    'badge' => __('Modern Web Tasarım Altyapısı'),
                    'title' => __('Tema ve Modül Ekleme-Geliştirme Özelliği'),
                    'description' => __('SEO ve performans süreçleri akıllı sistemlerle optimize edilir.'),
                    'buttonText' => __('Planları Gör'),
                    'buttonLink' => '#',
                    'imageSrc' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&h=600&fit=crop&q=80',
                    'imageAlt' => __('Yapay Zeka Destekli Modül ve Tema Geliştirme')
                ]
            ],
            [
                'value' => 'tab-2',
                'icon' => 'pointer',
                'label' => __('100% Responsive'),
                'content' => [
                    'badge' => __('Mobil Uyumlu Panel ve Web Sitesi'),
                    'title' => __('Tüm cihazlarda yüksek performanslı şekilde kullanın'),
                    'description' => __('Tüm cihazlarda kusursuz deneyim: mobil, tablet ve masaüstü.'),
                    'buttonText' => __('Detayları İncele'),
                    'buttonLink' => '#',
                    'imageSrc' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&h=600&fit=crop&q=80',
                    'imageAlt' => __('Responsive Web Altyapıları - Mobil, Tablet ve Masaüstü Uyumluluk')
                ]
            ],
            [
                'value' => 'tab-3',
                'icon' => 'layout',
                'label' => __('Hafif & Geliştirilebilir'),
                'content' => [
                    'badge' => __('Temiz Mimari'),
                    'title' => __('Dilediğiniz şekilde geliştirilebilir ve özelleştirilebilir.'),
                    'description' => __('Modül ve tema yapısı ile her sektöre uygun şekilde geliştirilebilir.'),
                    'buttonText' => __('Detayları İncele'),
                    'buttonLink' => '#',
                    'imageSrc' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=600&fit=crop&q=80',
                    'imageAlt' => __('Geliştirilebilir ve Özelleştirilebilir Web Altyapısı')
                ]
            ]
        ]
    ],
    'section-with-mockup' => [
        'title' => __('Sektöre göre geliştirilebilir yapı'),
        'description' => __('Her sektöre uygun olacak şekilde şekillendirilebilir, yapay zeka destekli, ölçeklenebilir web tasarım projeleri'),
        'primary_image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=1200&fit=crop&q=80',
        'secondary_image' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=800&h=1200&fit=crop&q=80',
        'settings' => [
            'reverse_layout' => false
        ]
    ],
    'pricing' => [
        'title' => __('Paketlerimiz'),
        'subtitle' => __('İhtiyacınıza uygun paketi seçin ve dijital dönüşümünüze başlayın.'),
        'settings' => [
            'badge' => __('Fiyatlandırma')
        ],
        'packages' => [
            [
                'name' => __('Başlangıç'),
                'price' => '₺2.500',
                'period' => __('/ay'),
                'description' => __('Küçük işletmeler ve kişisel projeler için ideal başlangıç paketi.'),
                'features' => [
                    __('5 Sayfa'),
                    __('Temel SEO'),
                    __('E-posta Desteği'),
                    __('SSL Sertifikası'),
                    __('Mobil Uyumlu Tasarım')
                ],
                'button_text' => __('Başla'),
                'button_link' => '/contact',
                'popular' => false,
                'gradient' => 'from-slate-500 to-slate-600'
            ],
            [
                'name' => __('Profesyonel'),
                'price' => '₺5.000',
                'period' => __('/ay'),
                'description' => __('Büyüyen işletmeler için gelişmiş özellikler ve destek.'),
                'features' => [
                    __('15 Sayfa'),
                    __('Gelişmiş SEO'),
                    __('Öncelikli Destek'),
                    __('SSL Sertifikası'),
                    __('Mobil Uyumlu Tasarım'),
                    __('Sosyal Medya Entegrasyonu'),
                    __('Analytics Entegrasyonu')
                ],
                'button_text' => __('Başla'),
                'button_link' => '/contact',
                'popular' => true,
                'gradient' => 'from-blue-500 to-purple-600'
            ],
            [
                'name' => __('Kurumsal'),
                'price' => '₺10.000',
                'period' => __('/ay'),
                'description' => __('Büyük işletmeler için özel çözümler ve özel destek.'),
                'features' => [
                    __('Sınırsız Sayfa'),
                    __('Premium SEO'),
                    __('7/24 Öncelikli Destek'),
                    __('SSL Sertifikası'),
                    __('Mobil Uyumlu Tasarım'),
                    __('Sosyal Medya Entegrasyonu'),
                    __('Analytics Entegrasyonu'),
                    __('Özel Tasarım'),
                    __('API Entegrasyonları')
                ],
                'button_text' => __('Başla'),
                'button_link' => '/contact',
                'popular' => false,
                'gradient' => 'from-violet-500 to-purple-600'
            ],
            [
                'name' => __('Özel Çözüm'),
                'price' => __('Özel Fiyat'),
                'period' => '',
                'description' => __('Özel ihtiyaçlarınız için özelleştirilmiş çözümler.'),
                'features' => [
                    __('Tam Özelleştirme'),
                    __('Özel Geliştirme'),
                    __('Dedike Destek'),
                    __('Tüm Özellikler'),
                    __('Özel Entegrasyonlar'),
                    __('Danışmanlık Hizmeti'),
                    __('Öncelikli Güncellemeler')
                ],
                'button_text' => __('İletişime Geç'),
                'button_link' => '/contact',
                'popular' => false,
                'gradient' => 'from-amber-500 to-orange-600'
            ]
        ]
    ]
];

// Global flag - duplicate render'ı önlemek için
if (!isset($GLOBALS['codetic_home_sections_rendered'])) {
    $GLOBALS['codetic_home_sections_rendered'] = false;
}

// Duplicate render kontrolü - eğer zaten render edilmişse, hiçbir şey yapma
if ($GLOBALS['codetic_home_sections_rendered']) {
    return;
}

// Flag'i hemen set et (render başlamadan önce)
$GLOBALS['codetic_home_sections_rendered'] = true;

// Section render fonksiyonu
function renderHomeSection($sectionId, $sectionsMap, $defaultSections, $themeLoader) {
    // Sadece mevcut component'leri render et
    $availableSections = ['hero', 'glowing-features', 'dashboard-showcase', 'lamp', 'feature-tabs', 'section-with-mockup', 'pricing'];
    if (!in_array($sectionId, $availableSections)) {
        return;
    }
    
    // Veritabanında kayıtlı ve aktif değilse gösterme
    if (isset($sectionsMap[$sectionId]) && isset($sectionsMap[$sectionId]['is_active']) && !$sectionsMap[$sectionId]['is_active']) {
        return;
    }
    
    // Veritabanı verisini al, yoksa varsayılanı kullan
    $dbSection = $sectionsMap[$sectionId] ?? null;
    $default = $defaultSections[$sectionId] ?? [];
    
    // items JSON alanını decode et (packages, tabs, vb. için)
    $dbItems = [];
    if (!empty($dbSection['items'])) {
        if (is_string($dbSection['items'])) {
            $dbItems = json_decode($dbSection['items'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $dbItems = [];
            }
        } elseif (is_array($dbSection['items'])) {
            $dbItems = $dbSection['items'];
        }
    }
    
    // settings JSON alanını decode et
    $dbSettings = [];
    if (!empty($dbSection['settings'])) {
        if (is_string($dbSection['settings'])) {
            $dbSettings = json_decode($dbSection['settings'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $dbSettings = [];
            }
        } elseif (is_array($dbSection['settings'])) {
            $dbSettings = $dbSection['settings'];
        }
    }
    
    // Section ID'ye göre items'ı doğru alana atama
    $dbPackages = [];
    $dbTabs = [];
    if ($sectionId === 'pricing' && !empty($dbItems)) {
        $dbPackages = $dbItems; // Pricing için items = packages
    } elseif ($sectionId === 'feature-tabs' && !empty($dbItems)) {
        $dbTabs = $dbItems; // Feature-tabs için items = tabs
    }
    
    // Veriyi birleştir
    $section = [
        'title' => !empty($dbSection['title']) ? $dbSection['title'] : ($default['title'] ?? ''),
        'subtitle' => !empty($dbSection['subtitle']) ? $dbSection['subtitle'] : ($default['subtitle'] ?? ''),
        'content' => !empty($dbSection['content']) ? $dbSection['content'] : ($default['content'] ?? ''),
        'text' => !empty($dbSection['text']) ? $dbSection['text'] : ($default['text'] ?? ''),
        'description' => !empty($dbSection['description']) ? $dbSection['description'] : ($default['description'] ?? ''),
        'features' => !empty($dbSection['features']) ? $dbSection['features'] : ($default['features'] ?? []),
        'show_card' => isset($dbSection['show_card']) ? $dbSection['show_card'] : ($default['show_card'] ?? true),
        'settings' => !empty($dbSettings) ? $dbSettings : ($default['settings'] ?? []),
        'items' => !empty($dbItems) ? $dbItems : ($default['items'] ?? []),
        'tabs' => !empty($dbTabs) ? $dbTabs : ($default['tabs'] ?? []),
        'packages' => !empty($dbPackages) ? $dbPackages : ($default['packages'] ?? []),
        'primary_image' => !empty($dbSection['primary_image']) ? $dbSection['primary_image'] : ($default['primary_image'] ?? ''),
        'secondary_image' => !empty($dbSection['secondary_image']) ? $dbSection['secondary_image'] : ($default['secondary_image'] ?? ''),
        'image_url' => !empty($dbSection['image_url']) ? $dbSection['image_url'] : ($default['image_url'] ?? ''),
        'image_alt' => !empty($dbSection['image_alt']) ? $dbSection['image_alt'] : ($default['image_alt'] ?? '')
    ];
    
    // Çeviri filter'larını uygula (geriye dönük uyumluluk için) ve __() helper fonksiyonunu da kullan
    if (function_exists('apply_filters')) {
        if (!empty($section['title'])) {
            $section['title'] = apply_filters('section_title', __($section['title']));
        }
        if (!empty($section['subtitle'])) {
            $section['subtitle'] = apply_filters('section_subtitle', __($section['subtitle']));
        }
        if (!empty($section['content'])) {
            $section['content'] = apply_filters('section_content', __($section['content']));
        }
        if (!empty($section['description'])) {
            $section['description'] = apply_filters('section_content', __($section['description']));
        }
        
        // Settings içindeki metinleri çevir
        if (is_array($section['settings'])) {
            foreach ($section['settings'] as $key => $value) {
                if (is_string($value) && !empty($value) && (strpos($key, 'text') !== false || strpos($key, 'label') !== false || strpos($key, 'badge') !== false)) {
                    $section['settings'][$key] = apply_filters('section_setting_text', __($value));
                }
            }
        }
        
        // Items içindeki metinleri çevir
        if (is_array($section['items'])) {
            foreach ($section['items'] as &$item) {
                if (is_array($item)) {
                    if (!empty($item['title'])) {
                        $item['title'] = apply_filters('section_item_title', __($item['title']));
                    }
                    if (!empty($item['description'])) {
                        $item['description'] = apply_filters('section_item_description', __($item['description']));
                    }
                    if (!empty($item['subtitle'])) {
                        $item['subtitle'] = apply_filters('section_subtitle', __($item['subtitle']));
                    }
                    if (!empty($item['content'])) {
                        if (is_string($item['content'])) {
                            $item['content'] = apply_filters('section_content', __($item['content']));
                        } elseif (is_array($item['content'])) {
                            // Tabs içindeki metinleri de çevir
                            foreach ($item['content'] as $contentKey => $contentValue) {
                                if (is_string($contentValue) && !empty($contentValue) && (strpos($contentKey, 'title') !== false || strpos($contentKey, 'description') !== false || strpos($contentKey, 'text') !== false)) {
                                    $item['content'][$contentKey] = apply_filters('section_item_description', __($contentValue));
                                }
                            }
                        }
                    }
                    if (!empty($item['label'])) {
                        $item['label'] = apply_filters('section_item_title', __($item['label']));
                    }
                }
            }
            unset($item);
        }
        
        // Tabs içindeki metinleri çevir
        if (is_array($section['tabs'])) {
            foreach ($section['tabs'] as &$tab) {
                if (is_array($tab)) {
                    if (!empty($tab['label'])) {
                        $tab['label'] = apply_filters('section_item_title', __($tab['label']));
                    }
                    if (isset($tab['content']) && is_array($tab['content'])) {
                        foreach ($tab['content'] as $contentKey => $contentValue) {
                            if (is_string($contentValue) && !empty($contentValue) && (strpos($contentKey, 'title') !== false || strpos($contentKey, 'description') !== false || strpos($contentKey, 'text') !== false)) {
                                $tab['content'][$contentKey] = apply_filters('section_item_description', __($contentValue));
                            }
                        }
                    }
                }
            }
            unset($tab);
        }
        
        // Packages içindeki metinleri çevir
        if (is_array($section['packages'])) {
            foreach ($section['packages'] as &$package) {
                if (is_array($package)) {
                    if (!empty($package['title'])) {
                        $package['title'] = apply_filters('section_item_title', __($package['title']));
                    }
                    if (!empty($package['description'])) {
                        $package['description'] = apply_filters('section_item_description', __($package['description']));
                    }
                    if (isset($package['features']) && is_array($package['features'])) {
                        foreach ($package['features'] as &$feature) {
                            if (is_string($feature)) {
                                $feature = apply_filters('section_item_description', __($feature));
                            }
                        }
                        unset($feature);
                    }
                }
            }
            unset($package);
        }
    }
    
    echo $themeLoader->renderComponent($sectionId, ['section' => $section]);
}

// Section'ları sıralı render et - Sadece mevcut component'ler kullanılıyor
$sectionOrder = ['hero', 'glowing-features', 'dashboard-showcase', 'section-with-mockup', 'feature-tabs', 'lamp', 'pricing'];

// Veritabanında sıralama varsa onu kullan (sadece mevcut component'ler için)
if (!empty($sections)) {
    // Veritabanından gelen section'ları map'e çevir (sort_order'a göre değil, bizim sıramıza göre)
    $dbSectionsMap = [];
    $availableSections = ['hero', 'glowing-features', 'dashboard-showcase', 'lamp', 'feature-tabs', 'section-with-mockup', 'pricing']; // Mevcut component'ler
    
    foreach ($sections as $s) {
        if (in_array($s['section_id'], $availableSections) && isset($defaultSections[$s['section_id']])) {
            $dbSectionsMap[$s['section_id']] = $s;
        }
    }
    
    // Bizim sıralamamıza göre section'ları düzenle
    $orderedSections = [];
    foreach ($sectionOrder as $sid) {
        if (isset($dbSectionsMap[$sid]) || isset($defaultSections[$sid])) {
            $orderedSections[] = $sid;
        }
    }
    
    $sectionOrder = $orderedSections;
}

// Section'ları render et
foreach ($sectionOrder as $sectionId) {
    renderHomeSection($sectionId, $sectionsMap, $defaultSections, $themeLoader);
}

