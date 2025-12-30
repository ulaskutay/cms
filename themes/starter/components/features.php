<?php
$title = $section['title'] ?? 'Neden Bizi Tercih Etmelisiniz?';
$subtitle = $section['subtitle'] ?? 'Müşterilerimize en iyi deneyimi sunmak için çalışıyoruz.';
$settings = $section['settings'] ?? [];
$columns = isset($settings['columns']) ? (string)$settings['columns'] : '3';
// Kolon sayısını integer'a çevir (güvenlik için)
$columnsInt = in_array($columns, ['2', '3', '4']) ? (int)$columns : 3;
$items = $section['items'] ?? [
    ['icon' => 'rocket_launch', 'title' => 'Hızlı Performans', 'description' => 'Optimize edilmiş kod yapısı ile yüksek performans.'],
    ['icon' => 'palette', 'title' => 'Modern Tasarım', 'description' => 'Güncel trendlere uygun şık ve modern görünüm.'],
    ['icon' => 'devices', 'title' => 'Responsive', 'description' => 'Tüm cihazlarda mükemmel görünüm.']
];
?>

<section id="features" class="py-24 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <?php if (!empty($title)): ?>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                <?php echo htmlspecialchars($title); ?>
            </h2>
            <?php endif; ?>
            <?php if (!empty($subtitle)): ?>
            <p class="text-lg text-muted">
                <?php echo htmlspecialchars($subtitle); ?>
            </p>
            <?php endif; ?>
        </div>
        
        <!-- Features Grid -->
        <style>
            #features-grid-<?php echo $columnsInt; ?> {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            @media (min-width: 768px) {
                #features-grid-<?php echo $columnsInt; ?> {
                    grid-template-columns: repeat(<?php echo min($columnsInt, 2); ?>, 1fr);
                }
            }
            @media (min-width: 1024px) {
                #features-grid-<?php echo $columnsInt; ?> {
                    grid-template-columns: repeat(<?php echo $columnsInt; ?>, 1fr);
                }
            }
        </style>
        <div id="features-grid-<?php echo $columnsInt; ?>">
            <?php foreach ($items as $item): 
                $hasLink = !empty($item['link']);
            ?>
            <?php if ($hasLink): ?>
            <a href="<?php echo esc_url($item['link']); ?>" class="group block">
            <?php else: ?>
            <div class="group">
            <?php endif; ?>
                <div class="p-8 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 h-full">
                    <?php if (!empty($item['icon'])): ?>
                    <div class="w-14 h-14 gradient-primary rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <?php 
                        // SVG icon mapping for common icons (PHP 7 compatible)
                        $iconMap = array(
                            'rocket_launch' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                            'palette' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>',
                            'devices' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                            'security' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                            'support' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
                            'speed' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                            'code' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>',
                            'settings' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
                        );
                        $defaultIcon = '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>';
                        echo isset($iconMap[$item['icon']]) ? $iconMap[$item['icon']] : $defaultIcon;
                        ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($item['title'])): ?>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        <?php echo htmlspecialchars($item['title']); ?>
                    </h3>
                    <?php endif; ?>
                    <?php if (!empty($item['description'])): ?>
                    <p class="text-muted leading-relaxed">
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($hasLink): ?>
                    <div class="mt-4 flex items-center text-primary font-medium group-hover:gap-2 transition-all">
                        <span>Daha Fazla</span>
                        <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                    <?php endif; ?>
                </div>
            <?php if ($hasLink): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

