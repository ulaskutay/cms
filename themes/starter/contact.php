<?php
/**
 * Starter Theme - Contact Page
 * Şirket bilgileri ve sosyal medya linkleri panelden otomatik çekilir
 * Sayfa ayarları tema özelleştirme panelinden yönetilir
 */

// Sayfa ayarlarını veritabanından al
$pageSettings = $themeLoader->getAllPageSettings('contact');

// Varsayılan değerler
$heroTitle = $pageSettings['hero_title'] ?? 'Bizimle İletişime Geçin';
$heroSubtitle = $pageSettings['hero_subtitle'] ?? 'Sorularınız, önerileriniz veya işbirliği teklifleriniz için bize ulaşabilirsiniz. En kısa sürede size dönüş yapacağız.';
$showMap = ($pageSettings['show_map'] ?? '1') === '1';
$showSocial = ($pageSettings['show_social'] ?? '1') === '1';
$showHours = ($pageSettings['show_hours'] ?? '1') === '1';
$formId = $pageSettings['form_id'] ?? '';

// Site ve şirket bilgileri
$siteName = get_option('site_name', 'Site Adı');
$companyName = get_option('company_name', $siteName);
$companyEmail = get_option('company_email', get_option('contact_email', ''));
$companyPhone = get_option('company_phone', get_option('contact_phone', ''));
$companyAddress = get_option('company_address', get_option('contact_address', ''));
$companyCity = get_option('company_city', '');

// Sosyal medya linkleri
$socialLinks = [
    'facebook' => ['url' => get_option('social_facebook', ''), 'icon' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z', 'label' => 'Facebook'],
    'instagram' => ['url' => get_option('social_instagram', ''), 'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z', 'label' => 'Instagram'],
    'twitter' => ['url' => get_option('social_twitter', ''), 'icon' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z', 'label' => 'X'],
    'linkedin' => ['url' => get_option('social_linkedin', ''), 'icon' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z', 'label' => 'LinkedIn'],
    'youtube' => ['url' => get_option('social_youtube', ''), 'icon' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z', 'label' => 'YouTube'],
    'tiktok' => ['url' => get_option('social_tiktok', ''), 'icon' => 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z', 'label' => 'TikTok'],
];

// Aktif sosyal medya linklerini filtrele
$activeSocials = array_filter($socialLinks, fn($s) => !empty($s['url']));
?>

<section class="relative overflow-hidden">
    <!-- Background Gradient -->
    <div class="absolute inset-0 gradient-primary opacity-95"></div>
    
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-3xl"></div>
    </div>
    
    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm text-white text-sm font-medium mb-6">
                <span class="material-symbols-outlined text-lg">mail</span>
                İletişim
            </span>
            <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold text-white mb-6">
                <?php echo esc_html($heroTitle); ?>
            </h1>
            <p class="text-lg lg:text-xl text-white/90 leading-relaxed">
                <?php echo esc_html($heroSubtitle); ?>
            </p>
        </div>
    </div>
    
    <!-- Wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="currentColor" class="text-gray-50 dark:text-gray-900"/>
        </svg>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            
            <!-- İletişim Formu -->
            <div class="order-2 lg:order-1">
                <div class="bg-gray-800 dark:bg-gray-900 rounded-2xl shadow-xl p-8 lg:p-10">
                    <div class="mb-8">
                        <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2">
                            Mesaj Gönderin
                        </h2>
                        <p class="text-gray-300">
                            Formu doldurun, 24 saat içinde yanıt verelim.
                        </p>
                    </div>
                    
                    <div class="contact-form-wrapper">
                        <?php 
                        if ($formId) {
                            the_form_by_id($formId);
                        } else {
                            the_form('iletisim');
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- İletişim Bilgileri -->
            <div class="order-1 lg:order-2 space-y-6">
                
                <!-- İletişim Kartları -->
                <div class="space-y-4">
                    
                    <?php if ($companyEmail): ?>
                    <a href="mailto:<?php echo esc_attr($companyEmail); ?>" 
                       class="group flex items-center gap-5 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="w-14 h-14 rounded-xl gradient-primary flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-2xl text-white">mail</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">E-posta</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white truncate"><?php echo esc_html($companyEmail); ?></p>
                        </div>
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-primary group-hover:translate-x-1 transition-all duration-300">arrow_forward</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($companyPhone): ?>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $companyPhone)); ?>" 
                       class="group flex items-center gap-5 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-2xl text-white">call</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Telefon</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo esc_html($companyPhone); ?></p>
                        </div>
                        <span class="material-symbols-outlined text-gray-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all duration-300">arrow_forward</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($companyAddress): ?>
                    <div class="flex items-start gap-5 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl text-white">location_on</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Adres</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo esc_html($companyAddress); ?></p>
                            <?php if ($companyCity): ?>
                            <p class="text-gray-600 dark:text-gray-400 mt-1"><?php echo esc_html($companyCity); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Sosyal Medya -->
                <?php if ($showSocial && !empty($activeSocials)): ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sosyal Medya</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Bizi sosyal medyada takip edin</p>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($activeSocials as $key => $social): ?>
                        <a href="<?php echo esc_url($social['url']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           title="<?php echo esc_attr($social['label']); ?>"
                           class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-primary hover:text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="<?php echo $social['icon']; ?>"/>
                            </svg>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Çalışma Saatleri -->
                <?php if ($showHours): ?>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Çalışma Saatleri</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Pazartesi - Cuma</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">09:00 - 18:00</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Cumartesi</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">10:00 - 14:00</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="font-medium text-gray-700 dark:text-gray-300">Pazar</span>
                            <span class="font-semibold text-red-500">Kapalı</span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</section>

<!-- Harita Section -->
<?php if ($showMap && $companyAddress): ?>
<section class="relative">
    <div class="h-[400px] lg:h-[500px] w-full bg-gray-200 dark:bg-gray-800">
        <iframe 
            src="https://maps.google.com/maps?q=<?php echo urlencode($companyAddress . ' ' . $companyCity); ?>&output=embed"
            class="w-full h-full grayscale hover:grayscale-0 transition-all duration-500"
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</section>
<?php endif; ?>

<style>
/* Contact Form Styles - Modern & Clean */
.contact-form-wrapper .cms-form {
    color: #ffffff;
}

.contact-form-wrapper .cms-form .form-group {
    margin-bottom: 1.5rem;
}

.contact-form-wrapper .cms-form label {
    display: block;
    font-weight: 500;
    color: #ffffff;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.contact-form-wrapper .cms-form input,
.contact-form-wrapper .cms-form textarea,
.contact-form-wrapper .cms-form select {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.5rem;
    font-size: 0.9375rem;
    transition: all 0.2s;
    background: #ffffff;
    color: #111827;
}

.contact-form-wrapper .cms-form input:focus,
.contact-form-wrapper .cms-form textarea:focus,
.contact-form-wrapper .cms-form select:focus {
    border-color: var(--color-primary, #137fec);
    outline: none;
    box-shadow: 0 0 0 3px rgba(19, 127, 236, 0.1);
}

.contact-form-wrapper .cms-form input::placeholder,
.contact-form-wrapper .cms-form textarea::placeholder {
    color: #9ca3af;
}

.contact-form-wrapper .cms-form textarea {
    min-height: 120px;
    resize: vertical;
}

.contact-form-wrapper .cms-form button[type="submit"] {
    width: 100%;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, var(--color-primary, #137fec) 0%, #8b5cf6 100%);
    color: #ffffff;
    border: none;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 0.5rem;
}

.contact-form-wrapper .cms-form button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(19, 127, 236, 0.3);
}

.contact-form-wrapper .cms-form button[type="submit"]:active {
    transform: translateY(0);
}

.contact-form-wrapper .cms-form .form-description {
    color: #d1d5db;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
}

.contact-form-wrapper .cms-form .required-field::after {
    content: ' *';
    color: #ef4444;
}

/* Form başarı/hata mesajları */
.contact-form-wrapper .cms-form .form-success,
.contact-form-wrapper .cms-form .form-error {
    padding: 0.875rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    text-align: center;
    font-size: 0.875rem;
}

.contact-form-wrapper .cms-form .form-success {
    background: rgba(16, 185, 129, 0.2);
    color: #6ee7b7;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.contact-form-wrapper .cms-form .form-error {
    background: rgba(239, 68, 68, 0.2);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

/* Gradient helper */
.gradient-primary {
    background: linear-gradient(135deg, var(--color-primary, #137fec) 0%, #8b5cf6 50%, #a855f7 100%);
}
</style>
