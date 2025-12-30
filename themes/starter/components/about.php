<?php
$title = $section['title'] ?? 'Hakkımızda';
$subtitle = $section['subtitle'] ?? 'Biz kimiz ve ne yapıyoruz?';
$content = $section['content'] ?? 'Yılların deneyimi ile müşterilerimize en kaliteli hizmeti sunuyoruz. Profesyonel ekibimiz ile projelerinizi hayata geçiriyoruz.';
$settings = $section['settings'] ?? [];
$image = $settings['image'] ?? '';
$badgeValue = $settings['badge_value'] ?? '10+';
$badgeLabel = $settings['badge_label'] ?? 'Yıllık Deneyim';
$buttonText = $settings['button_text'] ?? 'Daha Fazla Bilgi';
$buttonLink = $settings['button_link'] ?? '/about';

// Özellik listesi
$features = $section['items'] ?? [
    ['text' => 'Profesyonel Ekip'],
    ['text' => 'Müşteri Odaklı Yaklaşım'],
    ['text' => 'Kaliteli ve Hızlı Hizmet']
];
?>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Image -->
            <div class="relative">
                <?php if ($image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="rounded-2xl shadow-2xl w-full" loading="lazy">
                <?php else: ?>
                <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <?php endif; ?>
                
                <!-- Floating Card -->
                <?php if ($badgeValue || $badgeLabel): ?>
                <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-xl shadow-xl max-w-[200px]">
                    <div class="text-4xl font-bold text-primary mb-1"><?php echo htmlspecialchars($badgeValue); ?></div>
                    <div class="text-sm text-muted"><?php echo htmlspecialchars($badgeLabel); ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Content -->
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?php echo htmlspecialchars($subtitle); ?>
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    <?php echo htmlspecialchars($title); ?>
                </h2>
                
                <div class="prose prose-lg text-muted mb-8">
                    <?php echo nl2br(htmlspecialchars($content)); ?>
                </div>
                
                <!-- Features List -->
                <?php if (!empty($features)): ?>
                <div class="space-y-4 mb-8">
                    <?php foreach ($features as $feature): ?>
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 bg-accent/20 text-accent rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-gray-700"><?php echo htmlspecialchars($feature['text'] ?? ''); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($buttonText): ?>
                <a href="<?php echo htmlspecialchars($buttonLink); ?>" class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium">
                    <?php echo htmlspecialchars($buttonText); ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
