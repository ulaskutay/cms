<?php
/**
 * Admin Top Header - Minimal
 * Web sitesine gitme butonu, tarih ve bildirimler
 */

// Session kontrolü
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Web sitesi URL'i
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$homeUrl = $protocol . '://' . $host . '/';

// Tarih formatı
$dayNames = [
    'Monday' => 'Pzt',
    'Tuesday' => 'Sal',
    'Wednesday' => 'Çar',
    'Thursday' => 'Per',
    'Friday' => 'Cum',
    'Saturday' => 'Cmt',
    'Sunday' => 'Paz'
];
$monthNames = [
    'January' => 'Ocak',
    'February' => 'Şubat',
    'March' => 'Mart',
    'April' => 'Nisan',
    'May' => 'Mayıs',
    'June' => 'Haziran',
    'July' => 'Temmuz',
    'August' => 'Ağustos',
    'September' => 'Eylül',
    'October' => 'Ekim',
    'November' => 'Kasım',
    'December' => 'Aralık'
];
$formattedDate = str_replace(
    array_keys($monthNames),
    array_values($monthNames),
    date('d F Y')
);
$formattedDay = $dayNames[date('l')] ?? date('D');
?>

<!-- Minimal Top Header -->
<header class="sticky top-0 z-40 w-full bg-white/95 dark:bg-background-dark/95 backdrop-blur-sm border-b border-gray-200 dark:border-white/10">
    <div class="flex items-center justify-between px-4 lg:px-6 py-3">
        <!-- Sol: Mobil Menü Butonu -->
        <div class="lg:hidden">
            <button type="button" 
                    onclick="toggleMobileSidebar()" 
                    class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-white/5 transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                    title="Menüyü Aç">
                <span class="material-symbols-outlined text-xl">menu</span>
            </button>
        </div>
        
        <!-- Desktop: Boş (Sidebar için yer) -->
        <div class="hidden lg:block"></div>

        <!-- Sağ: Tarih, Dark Mode, Bildirimler ve Web Butonu -->
        <div class="flex items-center gap-4">
            <!-- Tarih (Minimal) -->
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <span class="material-symbols-outlined text-base">calendar_today</span>
                <span class="hidden sm:inline"><?php echo esc_html($formattedDay); ?>,</span>
                <span><?php echo esc_html($formattedDate); ?></span>
            </div>

            <!-- Dark Mode Toggle Switch -->
            <div class="flex items-center">
                <button type="button" 
                        id="dark-mode-toggle"
                        onclick="toggleDarkMode()" 
                        class="relative inline-flex h-7 w-14 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        role="switch"
                        aria-label="Dark mode toggle"
                        aria-checked="false">
                    <!-- Background Track -->
                    <span class="absolute inset-0 rounded-full bg-gradient-to-r from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 transition-all duration-300"></span>
                    
                    <!-- Toggle Circle -->
                    <span class="absolute left-1 top-1 h-5 w-5 rounded-full bg-white shadow-lg transform transition-transform duration-300 ease-in-out dark:translate-x-7 dark:bg-gray-800 flex items-center justify-center">
                        <!-- Sun Icon (Light Mode) -->
                        <span id="sun-icon" class="material-symbols-outlined text-xs text-yellow-500 opacity-100 dark:opacity-0 dark:scale-0 transition-all duration-300" style="font-size: 14px;">light_mode</span>
                        <!-- Moon Icon (Dark Mode) -->
                        <span id="moon-icon" class="material-symbols-outlined text-xs text-blue-400 opacity-0 dark:opacity-100 dark:scale-100 scale-0 transition-all duration-300 absolute" style="font-size: 14px;">dark_mode</span>
                    </span>
                </button>
            </div>

            <!-- Bildirimler (Minimal) -->
            <div class="relative">
                <button type="button" 
                        onclick="toggleNotifications()" 
                        class="relative p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-white/5 transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                        title="Bildirimler">
                    <span class="material-symbols-outlined text-xl">notifications</span>
                    <!-- Bildirim Badge (sadece bildirim varsa göster) -->
                    <span class="absolute top-0.5 right-0.5 w-1.5 h-1.5 bg-red-500 rounded-full border border-white dark:border-background-dark hidden"></span>
                </button>
                
                <!-- Bildirim Dropdown -->
                <div id="notifications-dropdown" 
                     class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-background-dark rounded-lg shadow-xl border border-gray-200 dark:border-white/10 overflow-hidden z-50 transition-all duration-200 ease-out opacity-0 transform -translate-y-2">
                    <div class="p-3 border-b border-gray-200 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-gray-900 dark:text-white font-semibold text-sm">Bildirimler</h3>
                            <button onclick="markAllAsRead()" class="text-primary text-xs hover:underline">Tümünü Okundu İşaretle</button>
                        </div>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Bildirim Listesi -->
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                            <span class="material-symbols-outlined text-3xl mb-2 block opacity-50">notifications_none</span>
                            <p>Yeni bildirim bulunmuyor</p>
                        </div>
                    </div>
                    <div class="p-2 border-t border-gray-200 dark:border-white/10 text-center">
                        <a href="#" class="text-primary text-xs hover:underline">Tüm Bildirimleri Görüntüle</a>
                    </div>
                </div>
            </div>

            <!-- Web'e Git Butonu (Minimal) -->
            <a href="<?php echo esc_url($homeUrl); ?>" 
               target="_blank" 
               class="flex items-center gap-2 px-3 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-white/5 transition-colors text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white group"
               title="Web Sitesine Git">
                <span class="material-symbols-outlined text-lg group-hover:text-primary transition-colors">open_in_new</span>
                <span class="text-sm font-medium hidden sm:inline">Site</span>
            </a>
        </div>
    </div>
</header>

<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notifications-dropdown');
    
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        setTimeout(() => {
            dropdown.classList.remove('opacity-0', '-translate-y-2');
            dropdown.classList.add('opacity-100', 'translate-y-0');
        }, 10);
    } else {
        dropdown.classList.add('opacity-0', '-translate-y-2');
        dropdown.classList.remove('opacity-100', 'translate-y-0');
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
}

function markAllAsRead() {
    // Bildirimleri okundu işaretleme fonksiyonu (ileride AJAX ile yapılacak)
    console.log('Tüm bildirimler okundu işaretlendi');
}

// Dışarı tıklandığında dropdown'ı kapat
document.addEventListener('click', function(event) {
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const notificationsButton = event.target.closest('[onclick="toggleNotifications()"]');
    
    if (notificationsDropdown && !notificationsDropdown.contains(event.target) && !notificationsButton) {
        notificationsDropdown.classList.add('opacity-0', '-translate-y-2');
        notificationsDropdown.classList.remove('opacity-100', 'translate-y-0');
        setTimeout(() => {
            notificationsDropdown.classList.add('hidden');
        }, 200);
    }
});

// Mobil sidebar açma/kapama fonksiyonu
function toggleMobileSidebar() {
    const sidebar = document.querySelector('.sidebar-fixed');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('hidden');
        // Body scroll'u engelle
        if (sidebar.classList.contains('mobile-open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
}

// Overlay'e tıklandığında sidebar'ı kapat
function closeMobileSidebar() {
    const sidebar = document.querySelector('.sidebar-fixed');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.remove('mobile-open');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
}
</script>
