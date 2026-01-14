<?php
/**
 * Codetic Theme - Lamp Component
 * React component'inin PHP/JavaScript versiyonu
 * Framer Motion animasyonları vanilla JavaScript ve CSS ile implement edildi
 */

$section = $section ?? [];
$settings = $section['settings'] ?? [];

// Lamp ayarları - section verisinden al, yoksa varsayılan değerler - __() helper fonksiyonu kullanılıyor
$lampTitle = __(!empty($section['title']) ? $section['title'] : 'Fikirlerinizi hayata geçirin');
$lampSubtitle = __(!empty($section['subtitle']) ? $section['subtitle'] : '');
$lampId = 'lamp-section-' . uniqid();
?>

<section class="lamp-section relative flex min-h-[60vh] md:min-h-screen flex-col items-center justify-center overflow-hidden bg-slate-950 w-full z-0" id="<?php echo esc_attr($lampId); ?>">
    <!-- Top Mask - Üst section'dan geçiş -->
    <div class="lamp-top-mask absolute top-0 left-0 right-0 h-24 md:h-64 pointer-events-none z-[10]" style="background: linear-gradient(180deg, rgba(10,10,15,1) 0%, rgba(10,10,15,0.95) 20%, rgba(10,10,15,0.8) 40%, rgba(10,10,15,0.5) 60%, rgba(10,10,15,0.2) 80%, transparent 100%); will-change: auto; transform: translateZ(0);"></div>
    
    <div class="relative flex w-full flex-1 items-center justify-center isolate z-[1] lamp-container" style="min-height: 50vh;">
        
        <!-- Blur Effects -->
        <div class="lamp-blur-1 absolute top-1/2 h-48 w-full translate-y-12 scale-x-150 bg-slate-950 blur-3xl z-[1]"></div>
        <div class="absolute top-1/2 h-48 w-full bg-transparent opacity-10 backdrop-blur-xl z-[3]"></div>
        <div class="lamp-glow-center absolute inset-auto h-36 w-[28rem] -translate-y-1/2 rounded-full opacity-0 blur-[80px] z-[4]" style="background: linear-gradient(to right, rgba(59, 130, 246, 0.8), rgba(147, 51, 234, 0.8), rgba(6, 182, 212, 0.8));"></div>
        
        <!-- Animated Glow Elements - Initially hidden, animated on scroll -->
        <div class="lamp-glow-1 absolute top-1/2 left-1/2 -translate-x-1/2 h-36 w-64 -translate-y-[4rem] rounded-full opacity-0 blur-3xl z-[3]" style="background: linear-gradient(to right, rgba(96, 165, 250, 1), rgba(139, 92, 246, 0.9), rgba(103, 232, 249, 0.9));"></div>
        
        <!-- Lamp Bulb - Modern Minimal Design -->
        <div class="lamp-bulb-wrapper absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-[12rem] md:-translate-y-[14rem] z-[5] opacity-0">
            <!-- Multi-layer Glow Effects -->
            <div class="lamp-glow-layer-1 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full blur-3xl opacity-0" style="background: radial-gradient(circle, rgba(96, 165, 250, 0.6) 0%, rgba(139, 92, 246, 0.4) 50%, transparent 70%);"></div>
            <div class="lamp-glow-layer-2 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 rounded-full blur-2xl opacity-0" style="background: radial-gradient(circle, rgba(59, 130, 246, 0.7) 0%, rgba(103, 232, 249, 0.5) 50%, transparent 70%);"></div>
            
            <!-- Modern Minimal Lightbulb -->
            <div class="lamp-bulb-container relative">
                <svg class="lamp-bulb" width="160" height="160" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="bulbGradient" x1="12" y1="2" x2="12" y2="22">
                            <stop offset="0%" stop-color="#60a5fa" stop-opacity="0.9"/>
                            <stop offset="50%" stop-color="#8b5cf6" stop-opacity="0.8"/>
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.7"/>
                        </linearGradient>
                        <radialGradient id="bulbInnerLight" cx="50%" cy="45%">
                            <stop offset="0%" stop-color="#ffffff" stop-opacity="0.7"/>
                            <stop offset="30%" stop-color="#60a5fa" stop-opacity="0.5"/>
                            <stop offset="60%" stop-color="#8b5cf6" stop-opacity="0.35"/>
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.2"/>
                        </radialGradient>
                        <radialGradient id="bulbInnerLightOff" cx="50%" cy="45%">
                            <stop offset="0%" stop-color="#475569" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#1e293b" stop-opacity="0.5"/>
                        </radialGradient>
                    </defs>
                    <!-- Base -->
                    <rect x="9" y="20" width="6" height="2" rx="1" fill="url(#bulbGradient)" opacity="0.8" class="bulb-base"/>
                    <!-- Bulb - Başlangıçta kapalı -->
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.87-3.13-7-7-7z" 
                          stroke="url(#bulbGradient)" 
                          stroke-width="1.2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          fill="url(#bulbInnerLightOff)"
                          class="bulb-glass"
                          opacity="0.3"/>
                    <!-- Filament - Başlangıçta kapalı -->
                    <circle cx="12" cy="10" r="1.5" fill="#475569" opacity="0.5" class="bulb-filament"/>
                    <path d="M10 9l2 2 2-2" stroke="#475569" stroke-width="0.8" stroke-linecap="round" fill="none" opacity="0.4" class="bulb-filament-path"/>
                </svg>
            </div>
        </div>
        
    </div>

    <!-- Content - Başlık ampulün altında -->
    <div class="relative z-[10] flex flex-col items-center px-4 md:px-5 lamp-content">
        <?php if (!empty($lampTitle)): ?>
        <h1 class="lamp-title mt-32 md:mt-40 mb-4 md:mb-6 bg-gradient-to-br from-slate-300 to-slate-500 py-4 bg-clip-text text-center text-3xl sm:text-4xl md:text-7xl font-medium tracking-tight text-transparent">
            <?php echo $lampTitle; ?>
        </h1>
        <?php endif; ?>
    </div>
</section>

<style>
/* Lamp Section Styles */
#<?php echo esc_attr($lampId); ?> {
    position: relative;
    background: #0a0a0f;
    overflow: hidden;
    will-change: auto;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
}

/* Mobilde lamp section'ı tamamen gizle */
@media (max-width: 767px) {
    #<?php echo esc_attr($lampId); ?> {
        display: none !important;
    }
}

/* Fix z-index layering */
#<?php echo esc_attr($lampId); ?> > div:first-of-type {
    position: relative;
    z-index: 1;
}

/* Container scaling - only on desktop */
@media (min-width: 768px) {
    #<?php echo esc_attr($lampId); ?> .lamp-container {
        scale: 1 1.25;
    }
}

/* Initial States - Hidden, animated on scroll */

#<?php echo esc_attr($lampId); ?> .lamp-title {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

#<?php echo esc_attr($lampId); ?> .lamp-title.visible {
    opacity: 1;
    transform: translateY(0);
}


/* Animated States - Scroll ile animasyonlu */

#<?php echo esc_attr($lampId); ?> .lamp-glow-1 {
    width: 16rem;
    opacity: 0;
    transition: opacity 3s ease-out 1.5s, transform 3s ease-out 1.5s, width 3s ease-out 1.5s;
}

#<?php echo esc_attr($lampId); ?> .lamp-glow-1.visible {
    opacity: 0.9;
    width: 16rem;
}

/* Lamp Bulb Styles */
#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper {
    transform: translate(-50%, -50%) scale(0.8);
    transition: opacity 2.5s ease-out 0.4s, transform 2.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
    animation: bulbFloat 5s ease-in-out infinite;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb-container {
    position: relative;
    filter: drop-shadow(0 0 10px rgba(71, 85, 105, 0.2));
    transition: filter 2.5s ease-out;
}

/* Ampul yakma efekti - Başlangıçta kapalı */
#<?php echo esc_attr($lampId); ?> .bulb-glass {
    transition: fill 2.5s ease-out 0.5s, opacity 2.5s ease-out 0.5s, stroke-opacity 2.5s ease-out 0.5s;
}

#<?php echo esc_attr($lampId); ?> .bulb-filament,
#<?php echo esc_attr($lampId); ?> .bulb-filament-path {
    transition: fill 2s ease-out 0.8s, stroke 2s ease-out 0.8s, opacity 2s ease-out 0.8s;
}

/* Ampul yandığında */
#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .bulb-glass {
    fill: url(#bulbInnerLight);
    opacity: 1;
    stroke-opacity: 1;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .bulb-filament {
    fill: url(#bulbGradient);
    opacity: 0.95;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .bulb-filament-path {
    stroke: url(#bulbGradient);
    opacity: 0.8;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .lamp-bulb-container {
    filter: drop-shadow(0 0 60px rgba(96, 165, 250, 1)) 
            drop-shadow(0 0 120px rgba(139, 92, 246, 0.8))
            drop-shadow(0 0 180px rgba(103, 232, 249, 0.6))
            drop-shadow(0 0 240px rgba(96, 165, 250, 0.4));
    animation: bulbPulse 4s ease-in-out infinite;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb {
    width: 160px;
    height: 160px;
    transition: all 0.3s ease-out;
}

/* Glow Layers */
#<?php echo esc_attr($lampId); ?> .lamp-glow-layer-1,
#<?php echo esc_attr($lampId); ?> .lamp-glow-layer-2 {
    transition: opacity 1.5s ease-out 0.6s, transform 1.2s ease-out 0.6s;
    transform: translate(-50%, -50%) scale(0.8);
}

#<?php echo esc_attr($lampId); ?> .lamp-glow-layer-1 {
    transition: opacity 3s ease-out 1.2s, transform 3s ease-out 1.2s;
}

#<?php echo esc_attr($lampId); ?> .lamp-glow-layer-2 {
    transition: opacity 3s ease-out 1.5s, transform 3s ease-out 1.5s;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .lamp-glow-layer-1 {
    opacity: 0.85;
    transform: translate(-50%, -50%) scale(1.4);
    animation: glowPulse1 5s ease-in-out infinite;
}

#<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .lamp-glow-layer-2 {
    opacity: 0.75;
    transform: translate(-50%, -50%) scale(1.2);
    animation: glowPulse2 4s ease-in-out infinite;
}

/* Bulb Animations */
@keyframes bulbFloat {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1) translateY(0);
    }
    50% {
        transform: translate(-50%, -50%) scale(1.01) translateY(-2px);
    }
}

@keyframes bulbPulse {
    0%, 100% {
        filter: drop-shadow(0 0 60px rgba(96, 165, 250, 1)) 
                drop-shadow(0 0 120px rgba(139, 92, 246, 0.8))
                drop-shadow(0 0 180px rgba(103, 232, 249, 0.6))
                drop-shadow(0 0 240px rgba(96, 165, 250, 0.4));
    }
    50% {
        filter: drop-shadow(0 0 80px rgba(96, 165, 250, 1)) 
                drop-shadow(0 0 160px rgba(139, 92, 246, 0.9))
                drop-shadow(0 0 240px rgba(103, 232, 249, 0.7))
                drop-shadow(0 0 320px rgba(96, 165, 250, 0.5));
    }
}

@keyframes glowPulse1 {
    0%, 100% {
        opacity: 0.85;
        transform: translate(-50%, -50%) scale(1.4);
    }
    50% {
        opacity: 0.95;
        transform: translate(-50%, -50%) scale(1.5);
    }
}

@keyframes glowPulse2 {
    0%, 100% {
        opacity: 0.75;
        transform: translate(-50%, -50%) scale(1.2);
    }
    50% {
        opacity: 0.85;
        transform: translate(-50%, -50%) scale(1.3);
    }
}

@media (max-width: 767px) {
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper {
        transform: translate(-50%, -50%) scale(0.75);
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-bulb {
        width: 120px;
        height: 120px;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-glow-layer-1 {
        width: 12rem;
        height: 12rem;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-glow-layer-2 {
        width: 8rem;
        height: 8rem;
    }
}

#<?php echo esc_attr($lampId); ?> .lamp-glow-center {
    opacity: 0;
    transition: opacity 1s ease-out 0.5s, transform 0.3s ease-out;
}

#<?php echo esc_attr($lampId); ?> .lamp-glow-center {
    transition: opacity 3s ease-out 1.8s, transform 3s ease-out 1.8s;
}

#<?php echo esc_attr($lampId); ?> .lamp-glow-center.visible {
    opacity: 0.8;
}

/* Responsive Adjustments */
@media (max-width: 767px) {
    #<?php echo esc_attr($lampId); ?> {
        min-height: 60vh !important;
        max-height: 100vh;
        padding: 2rem 0 3rem;
        overflow: visible !important;
        position: relative;
    }
    
    #<?php echo esc_attr($lampId); ?> > div:first-of-type {
        scale: 1 !important;
        transform: none !important;
        min-height: 50vh;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-container {
        min-height: 50vh;
        overflow: visible;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-content {
        transform: translateY(0) !important;
        padding-top: 1rem;
        margin-top: 6rem;
        width: 100%;
        position: relative;
        z-index: 10;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-title {
        margin-top: 4rem !important;
        font-size: 1.875rem !important;
        line-height: 1.2;
        padding: 0.75rem 1rem;
        word-break: break-word;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper {
        transform: translate(-50%, -50%) scale(0.7) !important;
        top: 30% !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible {
        transform: translate(-50%, -50%) scale(0.75) !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-glow-1 {
        width: 8rem !important;
        height: 5rem !important;
        transform: translate(-50%, -50%) !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-glow-1.visible {
        width: 8rem !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-glow-center {
        width: 16rem !important;
        height: 7rem !important;
        transform: translate(-50%, -50%) !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-glow-layer-1,
    #<?php echo esc_attr($lampId); ?> .lamp-glow-layer-2 {
        transform: translate(-50%, -50%) scale(0.8) !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-top-mask {
        height: 2rem !important;
    }
    
    /* Mobilde animasyonları basitleştir ve ağır efektleri kaldır */
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper,
    #<?php echo esc_attr($lampId); ?> .lamp-glow-1,
    #<?php echo esc_attr($lampId); ?> .lamp-glow-center,
    #<?php echo esc_attr($lampId); ?> .lamp-title {
        transition-duration: 0.8s !important;
        transition-delay: 0s !important;
    }
    
    /* Mobilde ağır blur efektlerini kaldır */
    #<?php echo esc_attr($lampId); ?> .lamp-blur-1 {
        display: none !important;
    }
    
    /* Mobilde glow layer'ları devre dışı bırak */
    #<?php echo esc_attr($lampId); ?> .lamp-glow-layer-1,
    #<?php echo esc_attr($lampId); ?> .lamp-glow-layer-2 {
        display: none !important;
    }
    
    /* Mobilde drop-shadow efektlerini kaldır */
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .lamp-bulb-container {
        filter: drop-shadow(0 0 20px rgba(96, 165, 250, 0.6)) !important;
        animation: none !important;
    }
    
    /* Mobilde infinite animasyonları durdur */
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible {
        animation: none !important;
    }
    
    /* Mobilde glow efektlerini basitleştir */
    #<?php echo esc_attr($lampId); ?> .lamp-glow-1 {
        opacity: 0.3 !important;
        filter: blur(40px) !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-glow-center {
        opacity: 0.2 !important;
        filter: blur(30px) !important;
    }
    
    /* Backdrop-blur'u kaldır - 3. div elementi */
    #<?php echo esc_attr($lampId); ?> .lamp-container > div:nth-child(3) {
        display: none !important;
    }
    
    /* Will-change optimizasyonu */
    #<?php echo esc_attr($lampId); ?> * {
        will-change: auto !important;
    }
}

/* Kullanıcı azaltılmış hareket tercih ediyorsa animasyonları durdur */
@media (prefers-reduced-motion: reduce) {
    #<?php echo esc_attr($lampId); ?> * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible {
        animation: none !important;
    }
    
    #<?php echo esc_attr($lampId); ?> .lamp-bulb-wrapper.visible .lamp-bulb-container {
        animation: none !important;
    }
}

@media (min-width: 768px) {
    #<?php echo esc_attr($lampId); ?> .lamp-content {
        transform: translateY(0);
        margin-top: 0;
        padding-top: 0;
        padding-bottom: 4rem;
    }
}

</style>

<script>
(function() {
    'use strict';
    
    // Global hata yakalama
    try {
        // DOM yüklenene kadar bekle
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLampSection);
        } else {
            // DOM zaten yüklü, hemen çalıştır
            initLampSection();
        }
    } catch (e) {
        console.error('Lamp section initialization error:', e);
    }
    
    function initLampSection() {
        try {
            const lampSection = document.getElementById('<?php echo esc_js($lampId); ?>');
            if (!lampSection) {
                console.warn('Lamp section not found:', '<?php echo esc_js($lampId); ?>');
                return;
            }
            
            const title = lampSection.querySelector('.lamp-title');
            const glow1 = lampSection.querySelector('.lamp-glow-1');
            const glowCenter = lampSection.querySelector('.lamp-glow-center');
            const lampBulb = lampSection.querySelector('.lamp-bulb-wrapper');
            
            // Mobilde daha basit animasyon - performans için
            const isMobile = window.innerWidth < 768;
            
            // IntersectionObserver desteği kontrolü
            if (typeof IntersectionObserver === 'undefined') {
                // Fallback: Direkt görünür yap
                showElements(lampBulb, glow1, glowCenter, title, isMobile);
                return;
            }
            
            // Scroll animasyonu için Intersection Observer
            const observerOptions = {
                threshold: isMobile ? 0.1 : 0.2,
                rootMargin: isMobile ? '50px' : '0px'
            };
            
            let hasAnimated = false;
            let observer = null;
            
            try {
                observer = new IntersectionObserver((entries) => {
                    try {
                        entries.forEach(entry => {
                            if (entry.isIntersecting && !hasAnimated) {
                                hasAnimated = true;
                                
                                // Mobilde daha hızlı animasyon
                                if (isMobile) {
                                    showElements(lampBulb, glow1, glowCenter, title, true);
                                } else {
                                    // Desktop'ta kademeli animasyon
                                    if (lampBulb) {
                                        lampBulb.classList.add('visible');
                                    }
                                    
                                    setTimeout(() => {
                                        if (glow1) glow1.classList.add('visible');
                                        if (glowCenter) glowCenter.classList.add('visible');
                                    }, 600);
                                    
                                    setTimeout(() => {
                                        if (title) title.classList.add('visible');
                                    }, 1500);
                                }
                                
                                // Unobserve after animation
                                if (observer && entry.target) {
                                    try {
                                        observer.unobserve(entry.target);
                                    } catch (e) {
                                        console.warn('Unobserve error:', e);
                                    }
                                }
                            }
                        });
                    } catch (e) {
                        console.error('IntersectionObserver callback error:', e);
                        showElements(lampBulb, glow1, glowCenter, title, isMobile);
                    }
                }, observerOptions);
                
                observer.observe(lampSection);
            } catch (e) {
                console.error('IntersectionObserver creation error:', e);
                // Fallback: Direkt görünür yap
                showElements(lampBulb, glow1, glowCenter, title, isMobile);
            }
            
            // Helper function - güvenli element gösterimi
            function showElements(bulb, glow1El, glowCenterEl, titleEl, mobile) {
                try {
                    if (bulb) bulb.classList.add('visible');
                    if (glow1El) glow1El.classList.add('visible');
                    if (glowCenterEl) glowCenterEl.classList.add('visible');
                    if (titleEl) titleEl.classList.add('visible');
                } catch (e) {
                    console.error('Show elements error:', e);
                }
            }
            
        } catch (e) {
            console.error('Lamp section init error:', e);
            // Kritik hata durumunda hiçbir şey yapma - sayfa çalışmaya devam etsin
        }
    }
})();
</script>

