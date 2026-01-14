<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Dil Yönetimi</h1>
    
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 p-4 rounded <?php echo $_SESSION['flash_type'] === 'error' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'; ?>">
            <?php echo htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded">
            <div class="text-sm text-gray-600 dark:text-gray-400">Toplam Çeviri</div>
            <div class="text-2xl font-bold dark:text-white"><?php echo $stats['total_translations'] ?? 0; ?></div>
        </div>
        <div class="bg-green-50 dark:bg-green-900 p-4 rounded">
            <div class="text-sm text-gray-600 dark:text-gray-400">Otomatik Çeviri</div>
            <div class="text-2xl font-bold dark:text-white"><?php echo $stats['auto_translations'] ?? 0; ?></div>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded">
            <div class="text-sm text-gray-600 dark:text-gray-400">Aktif Diller</div>
            <div class="text-2xl font-bold dark:text-white"><?php echo $stats['total_languages'] ?? 0; ?></div>
        </div>
    </div>
    
    <div class="flex gap-2 mb-4 flex-wrap">
        <a href="<?php echo admin_url('module/translation/languages'); ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Dilleri Yönet
        </a>
        <a href="<?php echo admin_url('module/translation/translations'); ?>" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
            Çevirileri Yönet
        </a>
        <a href="<?php echo admin_url('module/translation/bulk_translate'); ?>" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
            Toplu Çeviri
        </a>
        <a href="<?php echo admin_url('module/translation/settings'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Ayarlar
        </a>
    </div>
    
    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-4 dark:text-white">Aktif Diller</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($languages as $lang): ?>
                <?php if ($lang['is_active']): ?>
                    <div class="p-4 border rounded dark:border-gray-700 dark:bg-gray-700">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl"><?php echo htmlspecialchars($lang['flag']); ?></span>
                            <div>
                                <div class="font-semibold dark:text-white"><?php echo htmlspecialchars($lang['name']); ?></div>
                                <div class="text-sm text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($lang['code']); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
