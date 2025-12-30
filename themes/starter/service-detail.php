<?php
/**
 * Hizmet Detay Sayfası Template
 * Modern, profesyonel tasarım - Tema sistemine entegre
 */

// ThemeLoader kontrolü
$hasThemeLoader = isset($themeLoader) && $themeLoader;

// Site bilgileri
$siteName = get_option('site_name', 'Site Adı');

// Özel alanları parse et
$heroSubtitle = $customFields['hero_subtitle'] ?? '';
$heroImage = $customFields['hero_image'] ?? $page['featured_image'] ?? '';

// Hizmet özellikleri (JSON)
$serviceFeatures = [];
if (!empty($customFields['service_features'])) {
    $serviceFeatures = json_decode($customFields['service_features'], true);
    if (!is_array($serviceFeatures)) $serviceFeatures = [];
}

// Süreç adımları (JSON)
$processSteps = [];
if (!empty($customFields['process_steps'])) {
    $processSteps = json_decode($customFields['process_steps'], true);
    if (!is_array($processSteps)) $processSteps = [];
}

// Avantajlar (JSON)
$advantages = [];
if (!empty($customFields['advantages'])) {
    $advantages = json_decode($customFields['advantages'], true);
    if (!is_array($advantages)) $advantages = [];
}

// SSS (JSON)
$faqs = [];
if (!empty($customFields['faqs'])) {
    $faqs = json_decode($customFields['faqs'], true);
    if (!is_array($faqs)) $faqs = [];
}

// CTA Ayarları
$ctaTitle = $customFields['cta_title'] ?? 'Projenizi Başlatalım';
$ctaDescription = $customFields['cta_description'] ?? 'Hemen iletişime geçin ve size özel çözümlerimizi keşfedin.';
$ctaButtonText = $customFields['cta_button_text'] ?? 'Teklif Al';
$ctaButtonLink = $customFields['cta_button_link'] ?? '/contact';

// İlgili hizmetler
$relatedServices = [];
if (!empty($customFields['related_services'])) {
    $relatedServices = json_decode($customFields['related_services'], true);
    if (!is_array($relatedServices)) $relatedServices = [];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo esc_html($title ?? $page['title']); ?> - <?php echo esc_html($siteName); ?></title>
    
    <?php if ($hasThemeLoader): ?>
        <?php 
        $favicon = $themeLoader->getFavicon();
        if ($favicon): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>">
        <?php endif; ?>
        <?php echo $themeLoader->getCssVariablesTag(); ?>
    <?php endif; ?>
    
    <!-- Inline Font Definitions (Only essential weights) -->
    <style>
        @font-face {
            font-family: 'Inter';
            src: url('<?php echo ViewRenderer::assetUrl('assets/fonts/inter/inter-400.woff2'); ?>') format('woff2');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Inter';
            src: url('<?php echo ViewRenderer::assetUrl('assets/fonts/inter/inter-600.woff2'); ?>') format('woff2');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('<?php echo ViewRenderer::assetUrl('assets/fonts/poppins/poppins-600.woff2'); ?>') format('woff2');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('<?php echo ViewRenderer::assetUrl('assets/fonts/poppins/poppins-700.woff2'); ?>') format('woff2');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--color-primary, #2563eb)',
                        secondary: 'var(--color-secondary, #7c3aed)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <style>
        :root {
            --color-primary: <?php echo $hasThemeLoader ? $themeLoader->getColor('primary', '#2563eb') : '#2563eb'; ?>;
            --color-secondary: <?php echo $hasThemeLoader ? $themeLoader->getColor('secondary', '#7c3aed') : '#7c3aed'; ?>;
        }
        
        html { scroll-behavior: smooth; }
        
        .gradient-text {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(37, 99, 235, 0.3);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
    
    <!-- Preload Tailwind CSS JS for faster loading -->
    <link rel="preload" href="<?php echo ViewRenderer::assetUrl('assets/js/tailwind.min.js'); ?>" as="script">
        
        .process-line::before {
            content: '';
            position: absolute;
            left: 24px;
            top: 60px;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--color-primary), transparent);
        }
        
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .faq-content.open {
            max-height: 500px;
        }
        
        <?php if ($hasThemeLoader): echo $themeLoader->getCustomCss(); endif; ?>
    </style>
    
    <?php if (!empty($meta_description ?? $page['meta_description'])): ?>
    <meta name="description" content="<?php echo esc_attr($meta_description ?? $page['meta_description']); ?>">
    <?php endif; ?>
    <?php if (!empty($meta_keywords ?? $page['meta_keywords'])): ?>
    <meta name="keywords" content="<?php echo esc_attr($meta_keywords ?? $page['meta_keywords']); ?>">
    <?php endif; ?>
</head>
<body class="font-sans text-gray-900 antialiased">
    
    <?php 
    // Header
    if ($hasThemeLoader) {
        echo $themeLoader->renderSnippet('header', [
            'title' => $title ?? $page['title'],
            'current_page' => 'page'
        ]);
    }
    ?>

    <main>
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-gray-50 via-white to-blue-50 py-20 lg:py-32">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-primary/5 to-transparent"></div>
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    <!-- Content -->
                    <div class="fade-up">
                        <?php if (!empty($heroSubtitle)): ?>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-primary/10 text-primary mb-6">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            <?php echo esc_html($heroSubtitle); ?>
                        </span>
                        <?php endif; ?>
                        
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
                            <?php echo esc_html($page['title']); ?>
                        </h1>
                        
                        <?php if (!empty($page['excerpt'])): ?>
                        <p class="text-lg lg:text-xl text-gray-600 leading-relaxed mb-8 max-w-xl">
                            <?php echo esc_html($page['excerpt']); ?>
                        </p>
                        <?php endif; ?>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo esc_url($ctaButtonLink); ?>" class="btn-primary inline-flex items-center justify-center px-8 py-4 rounded-xl text-white font-semibold text-lg">
                                <?php echo esc_html($ctaButtonText); ?>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            <a href="#features" class="inline-flex items-center justify-center px-8 py-4 rounded-xl border-2 border-gray-200 text-gray-700 font-semibold text-lg hover:border-primary hover:text-primary transition-all">
                                Detayları İncele
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Hero Image -->
                    <?php if (!empty($heroImage)): ?>
                    <div class="fade-up relative" style="animation-delay: 0.2s;">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                            <img src="<?php echo esc_url($heroImage); ?>" alt="<?php echo esc_attr($page['title']); ?>" class="w-full h-auto object-cover aspect-[4/3]">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-xl p-4 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Profesyonel Hizmet</p>
                                <p class="text-sm text-gray-500">Uzman Ekip</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <?php if (!empty($serviceFeatures)): ?>
        <section id="features" class="py-20 lg:py-28 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 fade-up">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Hizmet <span class="gradient-text">Özellikleri</span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Size sunduğumuz kapsamlı çözümlerimizi keşfedin.
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($serviceFeatures as $index => $feature): ?>
                    <div class="fade-up card-hover bg-gray-50 rounded-2xl p-8 border border-gray-100" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                        <?php if (!empty($feature['icon'])): ?>
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center mb-6">
                            <?php 
                            // SVG icon mapping (PHP 7 compatible)
                            $featureIconMap = array(
                                'code' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>',
                                'rocket_launch' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                                'security' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                                'palette' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>',
                                'devices' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                'support' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
                                'settings' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                'check_circle' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                            );
                            $defaultFeatureIcon = '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>';
                            echo isset($featureIconMap[$feature['icon']]) ? $featureIconMap[$feature['icon']] : $defaultFeatureIcon;
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($feature['title'])): ?>
                        <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo esc_html($feature['title']); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($feature['description'])): ?>
                        <p class="text-gray-600 leading-relaxed"><?php echo esc_html($feature['description']); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Content Section -->
        <?php if (!empty($page['content'])): ?>
        <section class="py-20 lg:py-28 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="fade-up prose prose-lg max-w-none">
                    <div class="bg-white rounded-2xl p-8 lg:p-12 shadow-sm border border-gray-100">
                        <?php echo $page['content']; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Process Section -->
        <?php if (!empty($processSteps)): ?>
        <section class="py-20 lg:py-28 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 fade-up">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Çalışma <span class="gradient-text">Sürecimiz</span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Projelerinizi nasıl hayata geçirdiğimizi adım adım inceleyin.
                    </p>
                </div>
                
                <div class="max-w-3xl mx-auto">
                    <?php foreach ($processSteps as $index => $step): ?>
                    <div class="fade-up relative flex gap-6 pb-12 <?php echo $index < count($processSteps) - 1 ? 'process-line' : ''; ?>" style="animation-delay: <?php echo $index * 0.15; ?>s;">
                        <!-- Step Number -->
                        <div class="flex-shrink-0 relative z-10">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                <?php echo $index + 1; ?>
                            </div>
                        </div>
                        
                        <!-- Step Content -->
                        <div class="flex-1 pt-1">
                            <?php if (!empty($step['title'])): ?>
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo esc_html($step['title']); ?></h3>
                            <?php endif; ?>
                            
                            <?php if (!empty($step['description'])): ?>
                            <p class="text-gray-600 leading-relaxed"><?php echo esc_html($step['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Advantages Section -->
        <?php if (!empty($advantages)): ?>
        <section class="py-20 lg:py-28 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 fade-up">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4">
                        Neden <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Bizi Tercih Etmelisiniz?</span>
                    </h2>
                    <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                        Farkımızı ortaya koyan özelliklerimiz.
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($advantages as $index => $advantage): ?>
                    <div class="fade-up text-center p-6 rounded-2xl bg-white/5 backdrop-blur border border-white/10 hover:bg-white/10 transition-all" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                        <?php if (!empty($advantage['icon'])): ?>
                        <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center mb-4">
                            <?php 
                            // SVG icon mapping (PHP 7 compatible)
                            $advantageIconMap = array(
                                'trending_up' => '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>',
                                'groups' => '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                                'star' => '<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>',
                                'verified' => '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>'
                            );
                            $defaultAdvantageIcon = '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>';
                            echo isset($advantageIconMap[$advantage['icon']]) ? $advantageIconMap[$advantage['icon']] : $defaultAdvantageIcon;
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($advantage['value'])): ?>
                        <p class="text-3xl font-bold text-white mb-1"><?php echo esc_html($advantage['value']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($advantage['label'])): ?>
                        <p class="text-gray-400"><?php echo esc_html($advantage['label']); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- FAQ Section -->
        <?php if (!empty($faqs)): ?>
        <section class="py-20 lg:py-28 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 fade-up">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Sıkça Sorulan <span class="gradient-text">Sorular</span>
                    </h2>
                    <p class="text-lg text-gray-600">
                        Merak ettiğiniz tüm sorulara cevap bulun.
                    </p>
                </div>
                
                <div class="space-y-4">
                    <?php foreach ($faqs as $index => $faq): ?>
                    <div class="fade-up faq-item border border-gray-200 rounded-xl overflow-hidden" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                        <button class="faq-toggle w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900 pr-4"><?php echo esc_html($faq['question'] ?? ''); ?></span>
                            <svg class="w-5 h-5 text-gray-400 faq-icon transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="faq-content">
                            <div class="px-6 pb-6 text-gray-600">
                                <?php echo esc_html($faq['answer'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Related Services -->
        <?php if (!empty($relatedServices)): ?>
        <section class="py-20 lg:py-28 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 fade-up">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        İlgili <span class="gradient-text">Hizmetler</span>
                    </h2>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($relatedServices as $index => $service): ?>
                    <a href="<?php echo esc_url($service['link'] ?? '#'); ?>" class="fade-up card-hover group block bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                        <?php if (!empty($service['image'])): ?>
                        <div class="aspect-video overflow-hidden">
                            <img src="<?php echo esc_url($service['image']); ?>" alt="<?php echo esc_attr($service['title'] ?? ''); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <?php if (!empty($service['title'])): ?>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors"><?php echo esc_html($service['title']); ?></h3>
                            <?php endif; ?>
                            <?php if (!empty($service['description'])): ?>
                            <p class="text-gray-600"><?php echo esc_html($service['description']); ?></p>
                            <?php endif; ?>
                            <span class="inline-flex items-center text-primary font-medium mt-4">
                                İncele
                                <svg class="w-5 h-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- CTA Section -->
        <section class="py-20 lg:py-28 bg-gradient-to-br from-primary to-secondary relative overflow-hidden">
            <!-- Decorative -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"white\"/></svg>'); background-size: 50px 50px;"></div>
            </div>
            
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="fade-up">
                    <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
                        <?php echo esc_html($ctaTitle); ?>
                    </h2>
                    <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
                        <?php echo esc_html($ctaDescription); ?>
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url($ctaButtonLink); ?>" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white text-primary font-semibold text-lg hover:bg-gray-100 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1">
                            <?php echo esc_html($ctaButtonText); ?>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', get_option('contact_phone', ''))); ?>" class="inline-flex items-center justify-center px-8 py-4 rounded-xl border-2 border-white/30 text-white font-semibold text-lg hover:bg-white/10 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Hemen Ara
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php 
    // Footer
    if ($hasThemeLoader) {
        echo $themeLoader->renderSnippet('footer');
    }
    ?>

    <?php if ($hasThemeLoader): ?>
        <?php if (file_exists($themeLoader->getThemePath() . '/assets/js/theme.js')): ?>
        <script src="<?php echo $themeLoader->getJsUrl(); ?>"></script>
        <?php endif; ?>
        <script><?php echo $themeLoader->getCustomJs(); ?></script>
    <?php endif; ?>

    <script>
    // Fade-up animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // FAQ Toggle
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            const isOpen = content.classList.contains('open');
            
            // Close all
            document.querySelectorAll('.faq-content').forEach(c => c.classList.remove('open'));
            document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = 'rotate(0deg)');
            
            // Open clicked if was closed
            if (!isOpen) {
                content.classList.add('open');
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });
    </script>
    
    <!-- Tailwind CSS - Load with defer to prevent render-blocking -->
    <script src="<?php echo ViewRenderer::assetUrl('assets/js/tailwind.min.js'); ?>" defer></script>
</body>
</html>
