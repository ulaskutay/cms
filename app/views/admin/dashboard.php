<?php include __DIR__ . '/snippets/header.php'; ?>
    <script>
        // Dashboard için özel Tailwind config
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="flex min-h-screen">
            <!-- SideNavBar -->
            <?php 
            $currentPage = 'dashboard';
            include __DIR__ . '/snippets/sidebar.php'; 
            ?>

            <!-- Content Area with Header -->
            <div class="flex-1 flex flex-col lg:ml-64">
                <!-- Top Header -->
                <?php include __DIR__ . '/snippets/top-header.php'; ?>

                <!-- Main Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-10 bg-gray-50 dark:bg-[#15202b] overflow-y-auto">
                <div class="layout-content-container flex flex-col w-full mx-auto max-w-7xl">
                    
                    <!-- Yetki Uyarısı -->
                    <?php if (!empty($message)): ?>
                    <div class="flex flex-col items-center justify-center py-16">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-10 max-w-md w-full text-center border border-gray-200 dark:border-gray-700">
                            <div class="w-20 h-20 mx-auto mb-6 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-red-500 dark:text-red-400 text-4xl">lock</span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Erişim Engellendi</h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-6"><?php echo esc_html($message); ?></p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="<?php echo admin_url('dashboard'); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium">
                                    <span class="material-symbols-outlined text-xl">home</span>
                                    Panoya Dön
                                </a>
                                <a href="<?php echo admin_url('logout'); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                                    <span class="material-symbols-outlined text-xl">logout</span>
                                    Çıkış Yap
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Normal Dashboard İçeriği -->
                    
                    <!-- PageHeading & Chips -->
                    <header class="flex flex-col sm:flex-row flex-wrap justify-between items-start sm:items-center gap-4 mb-6">
                        <div class="flex flex-col gap-2">
                            <p class="text-gray-900 dark:text-white text-3xl font-bold tracking-tight">Analizler</p>
                            <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Site performansına genel bakış.</p>
                        </div>
                        <div class="flex gap-2 p-1 overflow-x-auto bg-background-light dark:bg-background-dark rounded-lg border border-gray-200 dark:border-white/10">
                            <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-md px-3 bg-primary text-white">
                                <p class="text-sm font-medium leading-normal">Son 30 Gün</p>
                            </button>
                            <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-md px-3 hover:bg-gray-100 dark:hover:bg-white/5">
                                <p class="text-gray-800 dark:text-white text-sm font-medium leading-normal">Bu Ay</p>
                            </button>
                            <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-md px-3 hover:bg-gray-100 dark:hover:bg-white/5">
                                <p class="text-gray-800 dark:text-white text-sm font-medium leading-normal">Son 7 Gün</p>
                            </button>
                            <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-md px-3 hover:bg-gray-100 dark:hover:bg-white/5">
                                <p class="text-gray-800 dark:text-white text-sm font-medium leading-normal">Özel Aralık</p>
                                <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-base">expand_more</span>
                            </button>
                        </div>
                    </header>

                    <!-- Stats -->
                    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-background-light dark:bg-background-dark border border-gray-200 dark:border-white/10">
                            <p class="text-gray-600 dark:text-gray-300 text-base font-medium leading-normal">Toplam Ziyaretçi</p>
                            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">12,450</p>
                            <p class="text-green-500 dark:text-green-400 text-base font-medium leading-normal">+12.5%</p>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-background-light dark:bg-background-dark border border-gray-200 dark:border-white/10">
                            <p class="text-gray-600 dark:text-gray-300 text-base font-medium leading-normal">Toplam Oturum</p>
                            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">16,830</p>
                            <p class="text-red-500 dark:text-red-400 text-base font-medium leading-normal">-2.1%</p>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-background-light dark:bg-background-dark border border-gray-200 dark:border-white/10">
                            <p class="text-gray-600 dark:text-gray-300 text-base font-medium leading-normal">Ort. Oturum Süresi</p>
                            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">3dk 45s</p>
                            <p class="text-green-500 dark:text-green-400 text-base font-medium leading-normal">+5.8%</p>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl p-6 bg-background-light dark:bg-background-dark border border-gray-200 dark:border-white/10">
                            <p class="text-gray-600 dark:text-gray-300 text-base font-medium leading-normal">Hemen Çıkma Oranı</p>
                            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">45.2%</p>
                            <p class="text-red-500 dark:text-red-400 text-base font-medium leading-normal">-1.2%</p>
                        </div>
                    </section>

                    <!-- Charts -->
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 flex flex-col gap-2 rounded-xl border border-gray-200 dark:border-white/10 p-6 bg-background-light dark:bg-background-dark">
                            <p class="text-gray-900 dark:text-white text-lg font-semibold leading-normal">Site Trafiği Genel Bakış</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight truncate">28,345 Ziyaretçi</p>
                                <div class="flex gap-1">
                                    <p class="text-green-500 dark:text-green-400 text-base font-medium leading-normal">+8.2%</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">vs. Son 30 Gün</p>
                                </div>
                            </div>
                            <div class="flex min-h-[250px] flex-1 flex-col gap-8 py-4">
                                <svg fill="none" height="100%" preserveAspectRatio="none" viewBox="-3 0 478 150" width="100%" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient gradientUnits="userSpaceOnUse" id="paint0_linear_1" x1="236" x2="236" y1="1" y2="149">
                                            <stop stop-color="#137fec" stop-opacity="0.2"></stop>
                                            <stop offset="1" stop-color="#137fec" stop-opacity="0"></stop>
                                        </linearGradient>
                                    </defs>
                                    <path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25V149H0V109Z" fill="url(#paint0_linear_1)"></path>
                                    <path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25" stroke="#137fec" stroke-linecap="round" stroke-width="3"></path>
                                </svg>
                                <div class="flex justify-around">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs font-semibold leading-normal tracking-wide uppercase">Hafta 1</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs font-semibold leading-normal tracking-wide uppercase">Hafta 2</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs font-semibold leading-normal tracking-wide uppercase">Hafta 3</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs font-semibold leading-normal tracking-wide uppercase">Hafta 4</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl border border-gray-200 dark:border-white/10 p-6 bg-background-light dark:bg-background-dark">
                            <p class="text-gray-900 dark:text-white text-lg font-semibold leading-normal">Trafik Kaynakları</p>
                            <div class="flex flex-col items-center justify-center flex-1 py-4 min-h-[250px]">
                                <div class="relative w-48 h-48">
                                    <svg class="w-full h-full" viewBox="0 0 36 36">
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#137fec" stroke-dasharray="60, 100" stroke-width="3"></path>
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#34d399" stroke-dasharray="25, 100" stroke-dashoffset="-60" stroke-width="3"></path>
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#f59e0b" stroke-dasharray="15, 100" stroke-dashoffset="-85" stroke-width="3"></path>
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="text-3xl font-bold text-gray-900 dark:text-white">16k</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Oturum</span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#137fec]"></div>
                                    <span class="text-gray-700 dark:text-gray-200">Organik</span>
                                    <span class="ml-auto font-semibold text-gray-800 dark:text-white">60%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#34d399]"></div>
                                    <span class="text-gray-700 dark:text-gray-200">Doğrudan</span>
                                    <span class="ml-auto font-semibold text-gray-800 dark:text-white">25%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#f59e0b]"></div>
                                    <span class="text-gray-700 dark:text-gray-200">Yönlendirme</span>
                                    <span class="ml-auto font-semibold text-gray-800 dark:text-white">15%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                    <span class="text-gray-700 dark:text-gray-200">Diğer</span>
                                    <span class="ml-auto font-semibold text-gray-800 dark:text-white">5%</span>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-3 flex flex-col gap-2 rounded-xl border border-gray-200 dark:border-white/10 p-6 bg-background-light dark:bg-background-dark">
                            <p class="text-gray-900 dark:text-white text-lg font-semibold leading-normal">En Popüler Sayfalar</p>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-white/10 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            <th class="py-3 px-4 font-semibold">Sayfa</th>
                                            <th class="py-3 px-4 font-semibold text-right">Görüntülenme</th>
                                            <th class="py-3 px-4 font-semibold text-right">Benzersiz Görüntülenme</th>
                                            <th class="py-3 px-4 font-semibold text-right">Hemen Çıkma Oranı</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                        <tr class="text-sm text-gray-800 dark:text-gray-100">
                                            <td class="py-3 px-4 font-medium">/anasayfa</td>
                                            <td class="py-3 px-4 text-right">5,210</td>
                                            <td class="py-3 px-4 text-right">4,890</td>
                                            <td class="py-3 px-4 text-right">35.2%</td>
                                        </tr>
                                        <tr class="text-sm text-gray-800 dark:text-gray-100">
                                            <td class="py-3 px-4 font-medium">/urunler</td>
                                            <td class="py-3 px-4 text-right">4,150</td>
                                            <td class="py-3 px-4 text-right">3,980</td>
                                            <td class="py-3 px-4 text-right">42.8%</td>
                                        </tr>
                                        <tr class="text-sm text-gray-800 dark:text-gray-100">
                                            <td class="py-3 px-4 font-medium">/blog/yeni-makale</td>
                                            <td class="py-3 px-4 text-right">3,880</td>
                                            <td class="py-3 px-4 text-right">3,510</td>
                                            <td class="py-3 px-4 text-right">28.1%</td>
                                        </tr>
                                        <tr class="text-sm text-gray-800 dark:text-gray-100">
                                            <td class="py-3 px-4 font-medium">/hakkimizda</td>
                                            <td class="py-3 px-4 text-right">2,105</td>
                                            <td class="py-3 px-4 text-right">1,950</td>
                                            <td class="py-3 px-4 text-right">55.6%</td>
                                        </tr>
                                        <tr class="text-sm text-gray-800 dark:text-gray-100">
                                            <td class="py-3 px-4 font-medium">/iletisim</td>
                                            <td class="py-3 px-4 text-right">980</td>
                                            <td class="py-3 px-4 text-right">950</td>
                                            <td class="py-3 px-4 text-right">15.4%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <?php endif; ?>
                </div>
                </main>
            </div>
        </div>
    </div>
</body>
</html>
