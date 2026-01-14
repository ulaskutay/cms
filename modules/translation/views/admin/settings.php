<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Dil Ayarları</h1>
        <a href="<?php echo admin_url('module/translation'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Geri Dön
        </a>
    </div>
    
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 p-4 rounded <?php echo $_SESSION['flash_type'] === 'error' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'; ?>">
            <?php echo htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?php echo admin_url('module/translation/settings'); ?>">
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-1 dark:text-white">Varsayılan Dil</label>
                <select name="default_language" required class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    <?php foreach ($languages as $lang): ?>
                        <option value="<?php echo $lang['code']; ?>" <?php echo ($settings['default_language'] ?? 'tr') === $lang['code'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lang['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sitenin varsayılan dili</p>
            </div>
            
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="auto_translate" value="1" <?php echo ($settings['auto_translate'] ?? false) ? 'checked' : ''; ?> class="rounded">
                    <span class="dark:text-white">Otomatik Çeviri</span>
                </label>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Aktif edilirse, çeviri yoksa DeepL API ile otomatik çeviri yapılır</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1 dark:text-white">DeepL API Key</label>
                <input type="text" name="deepl_api_key" value="<?php echo htmlspecialchars($settings['deepl_api_key'] ?? ''); ?>" 
                       class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white" 
                       placeholder="API key'inizi buraya girin">
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    <a href="https://www.deepl.com/pro-api" target="_blank" class="text-blue-500 hover:underline">DeepL API key alın</a> 
                    (Ücretsiz plan: Ayda 500.000 karakter)
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1 dark:text-white">DeepL API URL</label>
                <input type="text" name="deepl_api_url" value="<?php echo htmlspecialchars($settings['deepl_api_url'] ?? 'https://api-free.deepl.com/v2/translate'); ?>" 
                       class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ücretsiz plan için: https://api-free.deepl.com/v2/translate</p>
            </div>
        </div>
        
        <div class="mt-6 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Kaydet</button>
            <a href="<?php echo admin_url('module/translation'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">İptal</a>
        </div>
    </form>
</div>
