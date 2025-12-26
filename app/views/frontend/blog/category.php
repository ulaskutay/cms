<?php
/**
 * Kategori Sayfası
 */
?>
<!-- Kategori Header -->
<section class="bg-gradient-to-r from-brand-purple to-purple-600 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <span class="inline-block px-4 py-1 bg-white/20 text-white text-sm font-medium rounded-full mb-4">
                Kategori
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white"><?php echo esc_html($category['name']); ?></h1>
            <?php if (!empty($category['description'])): ?>
            <p class="text-purple-100 mt-4 text-lg max-w-2xl mx-auto"><?php echo esc_html($category['description']); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Yazılar -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Ana İçerik -->
            <div class="flex-1">
                <?php if (empty($posts)): ?>
                <div class="bg-white rounded-xl p-12 text-center shadow-sm">
                    <span class="material-symbols-outlined text-gray-300 text-6xl">article</span>
                    <h2 class="text-xl font-semibold text-gray-600 mt-4">Bu kategoride yazı yok</h2>
                    <p class="text-gray-400 mt-2">Yakında yeni yazılar eklenecek.</p>
                    <a href="/blog" class="inline-flex items-center gap-2 mt-4 text-brand-purple font-medium hover:underline">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Tüm yazılara dön
                    </a>
                </div>
                <?php else: ?>
                
                <!-- Yazı Sayısı -->
                <p class="text-gray-500 mb-6"><?php echo count($posts); ?> yazı bulundu</p>
                
                <!-- Yazı Listesi -->
                <div class="grid gap-6">
                    <?php foreach ($posts as $post): ?>
                    <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                        <div class="flex flex-col md:flex-row">
                            <!-- Öne Çıkan Görsel -->
                            <?php if (!empty($post['featured_image'])): ?>
                            <a href="/blog/<?php echo esc_attr($post['slug']); ?>" class="md:w-64 flex-shrink-0">
                                <img src="<?php echo esc_url($post['featured_image']); ?>" 
                                     alt="<?php echo esc_attr($post['title']); ?>" 
                                     class="w-full h-48 md:h-full object-cover">
                            </a>
                            <?php endif; ?>
                            
                            <!-- İçerik -->
                            <div class="flex-1 p-6">
                                <h2 class="text-xl font-bold text-gray-800 mb-3">
                                    <a href="/blog/<?php echo esc_attr($post['slug']); ?>" class="hover:text-brand-purple transition-colors">
                                        <?php echo esc_html($post['title']); ?>
                                    </a>
                                </h2>
                                
                                <?php if (!empty($post['excerpt'])): ?>
                                <p class="text-gray-600 mb-4 line-clamp-2">
                                    <?php echo esc_html($post['excerpt']); ?>
                                </p>
                                <?php endif; ?>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">calendar_today</span>
                                        <?php echo date('d M Y', strtotime($post['published_at'] ?? $post['created_at'])); ?>
                                    </span>
                                    <a href="/blog/<?php echo esc_attr($post['slug']); ?>" 
                                       class="flex items-center gap-1 text-brand-purple font-medium hover:underline">
                                        Devamını Oku
                                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="w-full lg:w-80 flex-shrink-0 space-y-6">
                
                <!-- Kategoriler -->
                <?php if (!empty($categories)): ?>
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-purple">category</span>
                        Kategoriler
                    </h3>
                    <ul class="space-y-2">
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="/kategori/<?php echo esc_attr($cat['slug']); ?>" 
                               class="flex items-center justify-between py-1 transition-colors <?php echo $cat['id'] == $category['id'] ? 'text-brand-purple font-medium' : 'text-gray-600 hover:text-brand-purple'; ?>">
                                <span><?php echo esc_html($cat['name']); ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Son Yazılar -->
                <?php if (!empty($recentPosts)): ?>
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-purple">schedule</span>
                        Son Yazılar
                    </h3>
                    <ul class="space-y-4">
                        <?php foreach ($recentPosts as $recent): ?>
                        <li>
                            <a href="/blog/<?php echo esc_attr($recent['slug']); ?>" 
                               class="flex gap-3 group">
                                <?php if (!empty($recent['featured_image'])): ?>
                                <img src="<?php echo esc_url($recent['featured_image']); ?>" 
                                     alt="" 
                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0" loading="lazy">
                                <?php else: ?>
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-400">article</span>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800 group-hover:text-brand-purple transition-colors line-clamp-2">
                                        <?php echo esc_html($recent['title']); ?>
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <?php echo date('d M Y', strtotime($recent['published_at'] ?? $recent['created_at'])); ?>
                                    </p>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
            </aside>
        </div>
    </div>
</section>

