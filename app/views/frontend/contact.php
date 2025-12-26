<?php
/**
 * İletişim Sayfası
 * Şirket bilgileri ve sosyal medya linkleri panelden otomatik çekilir
 */

// Site ve şirket bilgileri
$siteName = get_option('site_name', 'Site Adı');
$companyName = get_option('company_name', $siteName);
$companyEmail = get_option('company_email', get_option('contact_email', ''));
$companyPhone = get_option('company_phone', get_option('contact_phone', ''));
$companyAddress = get_option('company_address', get_option('contact_address', ''));
$companyCity = get_option('company_city', '');

// Sosyal medya linkleri
$socialLinks = [
    'facebook' => ['url' => get_option('social_facebook', ''), 'icon' => 'fab fa-facebook-f', 'label' => 'Facebook', 'color' => '#1877f2'],
    'instagram' => ['url' => get_option('social_instagram', ''), 'icon' => 'fab fa-instagram', 'label' => 'Instagram', 'color' => '#e4405f'],
    'twitter' => ['url' => get_option('social_twitter', ''), 'icon' => 'fab fa-x-twitter', 'label' => 'X (Twitter)', 'color' => '#000000'],
    'linkedin' => ['url' => get_option('social_linkedin', ''), 'icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn', 'color' => '#0a66c2'],
    'youtube' => ['url' => get_option('social_youtube', ''), 'icon' => 'fab fa-youtube', 'label' => 'YouTube', 'color' => '#ff0000'],
    'tiktok' => ['url' => get_option('social_tiktok', ''), 'icon' => 'fab fa-tiktok', 'label' => 'TikTok', 'color' => '#000000'],
    'pinterest' => ['url' => get_option('social_pinterest', ''), 'icon' => 'fab fa-pinterest-p', 'label' => 'Pinterest', 'color' => '#bd081c'],
];

// Aktif sosyal medya linklerini filtrele
$activeSocials = array_filter($socialLinks, fn($s) => !empty($s['url']));

// Google Maps embed URL (opsiyonel)
$mapEmbed = get_option('google_maps_embed', '');
?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="contact-page">
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">
                    <i class="fas fa-envelope"></i>
                    İletişim
                </span>
                <h1>Bizimle İletişime Geçin</h1>
                <p>Sorularınız, önerileriniz veya işbirliği teklifleriniz için bize ulaşabilirsiniz. En kısa sürede size dönüş yapacağız.</p>
            </div>
        </div>
        <div class="hero-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="contact-main">
        <div class="container">
            <div class="contact-grid">
                
                <!-- İletişim Formu -->
                <div class="contact-form-wrapper">
                    <div class="form-card">
                        <div class="form-header">
                            <h2>Mesaj Gönderin</h2>
                            <p>Formu doldurun, 24 saat içinde yanıt verelim.</p>
                        </div>
                        
                        <?php the_form('iletisim'); ?>
                    </div>
                </div>

                <!-- İletişim Bilgileri -->
                <div class="contact-info-wrapper">
                    
                    <!-- İletişim Kartları -->
                    <div class="info-cards">
                        
                        <?php if ($companyEmail): ?>
                        <a href="mailto:<?php echo esc_attr($companyEmail); ?>" class="info-card">
                            <div class="card-icon email-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-label">E-posta</span>
                                <span class="card-value"><?php echo esc_html($companyEmail); ?></span>
                            </div>
                            <i class="fas fa-arrow-right card-arrow"></i>
                        </a>
                        <?php endif; ?>

                        <?php if ($companyPhone): ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $companyPhone)); ?>" class="info-card">
                            <div class="card-icon phone-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-label">Telefon</span>
                                <span class="card-value"><?php echo esc_html($companyPhone); ?></span>
                            </div>
                            <i class="fas fa-arrow-right card-arrow"></i>
                        </a>
                        <?php endif; ?>

                        <?php if ($companyAddress): ?>
                        <div class="info-card address-card">
                            <div class="card-icon address-icon">
                                <i class="fas fa-location-dot"></i>
                            </div>
                            <div class="card-content">
                                <span class="card-label">Adres</span>
                                <span class="card-value"><?php echo esc_html($companyAddress); ?></span>
                                <?php if ($companyCity): ?>
                                <span class="card-city"><?php echo esc_html($companyCity); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>

                    <!-- Sosyal Medya -->
                    <?php if (!empty($activeSocials)): ?>
                    <div class="social-section">
                        <h3>Sosyal Medya</h3>
                        <p>Bizi sosyal medyada takip edin</p>
                        <div class="social-links">
                            <?php foreach ($activeSocials as $key => $social): ?>
                            <a href="<?php echo esc_url($social['url']); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="social-link social-<?php echo $key; ?>"
                               title="<?php echo esc_attr($social['label']); ?>"
                               style="--social-color: <?php echo $social['color']; ?>">
                                <i class="<?php echo $social['icon']; ?>"></i>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Çalışma Saatleri -->
                    <div class="hours-section">
                        <h3>Çalışma Saatleri</h3>
                        <div class="hours-list">
                            <div class="hours-item">
                                <span class="day">Pazartesi - Cuma</span>
                                <span class="time">09:00 - 18:00</span>
                            </div>
                            <div class="hours-item">
                                <span class="day">Cumartesi</span>
                                <span class="time">10:00 - 14:00</span>
                            </div>
                            <div class="hours-item weekend">
                                <span class="day">Pazar</span>
                                <span class="time">Kapalı</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Harita Section (Opsiyonel) -->
    <?php if ($mapEmbed): ?>
    <section class="map-section">
        <div class="map-wrapper">
            <?php echo $mapEmbed; ?>
        </div>
    </section>
    <?php elseif ($companyAddress): ?>
    <section class="map-section">
        <div class="map-wrapper">
            <iframe 
                src="https://maps.google.com/maps?q=<?php echo urlencode($companyAddress . ' ' . $companyCity); ?>&output=embed"
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>
    <?php endif; ?>

</div>

<style>
/* ================================
   Contact Page Styles
   ================================ */

:root {
    --contact-primary: #6366f1;
    --contact-primary-dark: #4f46e5;
    --contact-secondary: #10b981;
    --contact-accent: #f59e0b;
    --contact-dark: #1f2937;
    --contact-gray: #6b7280;
    --contact-light: #f3f4f6;
    --contact-white: #ffffff;
    --contact-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
}

.contact-page {
    background: var(--contact-light);
    min-height: 100vh;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* Hero Section */
.contact-hero {
    position: relative;
    background: var(--contact-gradient);
    padding: 5rem 0 8rem;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1.25rem;
    border-radius: 100px;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    margin-bottom: 1.5rem;
}

.contact-hero h1 {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 800;
    color: white;
    margin: 0 0 1rem;
    line-height: 1.2;
}

.contact-hero p {
    font-size: 1.125rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.7;
    margin: 0;
}

.hero-decoration {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.decoration-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
}

.circle-1 {
    width: 400px;
    height: 400px;
    top: -200px;
    right: -100px;
}

.circle-2 {
    width: 300px;
    height: 300px;
    bottom: -150px;
    left: -50px;
}

.circle-3 {
    width: 150px;
    height: 150px;
    top: 50%;
    left: 20%;
}

/* Main Content */
.contact-main {
    position: relative;
    margin-top: -4rem;
    padding-bottom: 5rem;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 1024px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}

/* Form Card */
.form-card {
    background: var(--contact-white);
    border-radius: 1.5rem;
    padding: 2.5rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
}

.form-header {
    margin-bottom: 2rem;
}

.form-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--contact-dark);
    margin: 0 0 0.5rem;
}

.form-header p {
    color: var(--contact-gray);
    margin: 0;
}

/* Override form styles */
.form-card .cms-form .form-group {
    margin-bottom: 1.25rem;
}

.form-card .cms-form input,
.form-card .cms-form textarea,
.form-card .cms-form select {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.2s;
    background: var(--contact-white);
}

.form-card .cms-form input:focus,
.form-card .cms-form textarea:focus,
.form-card .cms-form select:focus {
    border-color: var(--contact-primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    outline: none;
}

.form-card .cms-form label {
    display: block;
    font-weight: 600;
    color: var(--contact-dark);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-card .cms-form button[type="submit"] {
    width: 100%;
    padding: 1rem 2rem;
    background: var(--contact-gradient);
    color: white;
    border: none;
    border-radius: 0.75rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.form-card .cms-form button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
}

/* Info Cards */
.info-cards {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--contact-white);
    padding: 1.25rem 1.5rem;
    border-radius: 1rem;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

a.info-card:hover {
    transform: translateX(8px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.email-icon {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
}

.phone-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.address-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.card-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.card-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--contact-gray);
    margin-bottom: 0.25rem;
}

.card-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--contact-dark);
}

.card-city {
    font-size: 0.875rem;
    color: var(--contact-gray);
    margin-top: 0.25rem;
}

.card-arrow {
    color: var(--contact-gray);
    transition: all 0.3s;
}

a.info-card:hover .card-arrow {
    color: var(--contact-primary);
    transform: translateX(4px);
}

/* Social Section */
.social-section {
    background: var(--contact-white);
    padding: 1.5rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.social-section h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--contact-dark);
    margin: 0 0 0.25rem;
}

.social-section p {
    font-size: 0.875rem;
    color: var(--contact-gray);
    margin: 0 0 1rem;
}

.social-links {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.social-link {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    color: var(--contact-gray);
    background: var(--contact-light);
    transition: all 0.3s;
}

.social-link:hover {
    color: white;
    background: var(--social-color);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Hours Section */
.hours-section {
    background: var(--contact-white);
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.hours-section h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--contact-dark);
    margin: 0 0 1rem;
}

.hours-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.hours-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: var(--contact-light);
    border-radius: 0.5rem;
}

.hours-item .day {
    font-weight: 500;
    color: var(--contact-dark);
}

.hours-item .time {
    font-weight: 600;
    color: var(--contact-secondary);
}

.hours-item.weekend .time {
    color: #ef4444;
}

/* Map Section */
.map-section {
    background: var(--contact-dark);
    padding: 0;
}

.map-wrapper {
    height: 400px;
    width: 100%;
}

.map-wrapper iframe {
    display: block;
    width: 100%;
    height: 100%;
    filter: grayscale(20%);
}

/* Responsive */
@media (max-width: 768px) {
    .contact-hero {
        padding: 3rem 0 6rem;
    }
    
    .form-card {
        padding: 1.5rem;
    }
    
    .info-card {
        padding: 1rem;
    }
    
    .card-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .contact-page {
        background: #111827;
    }
    
    .form-card,
    .info-card,
    .social-section,
    .hours-section {
        background: #1f2937;
    }
    
    .form-header h2,
    .card-value,
    .social-section h3,
    .hours-section h3,
    .hours-item .day {
        color: white;
    }
    
    .form-header p,
    .card-label,
    .card-city,
    .social-section p {
        color: #9ca3af;
    }
    
    .form-card .cms-form input,
    .form-card .cms-form textarea,
    .form-card .cms-form select {
        background: #374151;
        border-color: #4b5563;
        color: white;
    }
    
    .hours-item {
        background: #374151;
    }
    
    .social-link {
        background: #374151;
        color: #9ca3af;
    }
}
</style>
