<?php
/**
 * Tekil Yazı Sayfası
 */
?>
<!-- Yazı Header -->
<section class="bg-gradient-to-r from-brand-purple to-purple-600 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Kategori -->
            <?php if (!empty($post['category_name'])): ?>
            <a href="/kategori/<?php echo esc_attr($post['category_slug']); ?>" 
               class="inline-block px-4 py-1 bg-white/20 text-white text-sm font-medium rounded-full mb-4 hover:bg-white/30 transition-colors">
                <?php echo esc_html($post['category_name']); ?>
            </a>
            <?php endif; ?>
            
            <!-- Başlık -->
            <h1 class="text-3xl md:text-5xl font-bold text-white leading-tight">
                <?php echo esc_html($post['title']); ?>
            </h1>
            
            <!-- Meta Bilgileri -->
            <div class="flex items-center justify-center gap-6 mt-6 text-purple-100">
                <?php if (!empty($post['author_name'])): ?>
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined">person</span>
                    <?php echo esc_html($post['author_name']); ?>
                </span>
                <?php endif; ?>
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined">calendar_today</span>
                    <?php echo date('d F Y', strtotime($post['published_at'] ?? $post['created_at'])); ?>
                </span>
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined">visibility</span>
                    <?php echo number_format($post['views']); ?> görüntülenme
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Öne Çıkan Görsel -->
<?php if (!empty($post['featured_image'])): ?>
<div class="container mx-auto px-4 -mt-8">
    <div class="max-w-4xl mx-auto">
        <img src="<?php echo esc_url($post['featured_image']); ?>" 
             alt="<?php echo esc_attr($post['title']); ?>" 
             class="w-full h-auto max-h-[500px] object-cover rounded-xl shadow-2xl">
    </div>
</div>
<?php endif; ?>

<!-- İçerik -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Ana İçerik -->
            <article class="flex-1">
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <!-- İçerik -->
                    <div class="prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-600 prose-a:text-brand-purple prose-img:rounded-lg">
                        <?php echo $post['content']; ?>
                    </div>
                    
                    <!-- Paylaşım -->
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 font-medium">Bu yazıyı paylaş:</span>
                            <div class="flex gap-3">
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>" 
                                   target="_blank"
                                   class="w-10 h-10 flex items-center justify-center bg-[#1DA1F2] text-white rounded-full hover:opacity-90 transition-opacity">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post['slug']); ?>" 
                                   target="_blank"
                                   class="w-10 h-10 flex items-center justify-center bg-[#1877F2] text-white rounded-full hover:opacity-90 transition-opacity">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . '/blog/' . $post['slug']); ?>&title=<?php echo urlencode($post['title']); ?>" 
                                   target="_blank"
                                   class="w-10 h-10 flex items-center justify-center bg-[#0A66C2] text-white rounded-full hover:opacity-90 transition-opacity">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- İlgili Yazılar -->
                <?php if (!empty($relatedPosts)): ?>
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">İlgili Yazılar</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <?php foreach ($relatedPosts as $related): ?>
                        <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                            <?php if (!empty($related['featured_image'])): ?>
                            <a href="/blog/<?php echo esc_attr($related['slug']); ?>">
                                <img src="<?php echo esc_url($related['featured_image']); ?>" 
                                     alt="<?php echo esc_attr($related['title']); ?>" 
                                     class="w-full h-40 object-cover" loading="lazy">
                            </a>
                            <?php endif; ?>
                            <div class="p-5">
                                <h4 class="font-bold text-gray-800 mb-2">
                                    <a href="/blog/<?php echo esc_attr($related['slug']); ?>" class="hover:text-brand-purple transition-colors">
                                        <?php echo esc_html($related['title']); ?>
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-500">
                                    <?php echo date('d M Y', strtotime($related['published_at'] ?? $related['created_at'])); ?>
                                </p>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </article>
            
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
                               class="flex items-center justify-between text-gray-600 hover:text-brand-purple transition-colors py-1">
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

