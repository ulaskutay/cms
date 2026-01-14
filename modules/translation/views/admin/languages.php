<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Diller</h1>
        <a href="<?php echo admin_url('module/translation'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Geri Dön
        </a>
    </div>
    
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 p-4 rounded <?php echo $_SESSION['flash_type'] === 'error' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'; ?>">
            <?php echo htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        </div>
    <?php endif; ?>
    
    <div class="mb-4">
        <button onclick="document.getElementById('language-form').classList.toggle('hidden')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Yeni Dil Ekle
        </button>
    </div>
    
    <div id="language-form" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded">
        <form method="POST" action="<?php echo admin_url('module/translation/languages'); ?>">
            <input type="hidden" name="action" value="create">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-white">Dil Kodu (örn: en, de, tr)</label>
                    <input type="text" name="code" maxlength="2" required class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-white">Dil Adı</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-white">Yerel Ad</label>
                    <input type="text" name="native_name" class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-white">Bayrak (Emoji)</label>
                    <input type="text" name="flag" class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded">
                        <span class="dark:text-white">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Kaydet</button>
            </div>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="border p-2 text-left dark:text-white">Bayrak</th>
                    <th class="border p-2 text-left dark:text-white">Kod</th>
                    <th class="border p-2 text-left dark:text-white">Ad</th>
                    <th class="border p-2 text-left dark:text-white">Yerel Ad</th>
                    <th class="border p-2 text-left dark:text-white">Durum</th>
                    <th class="border p-2 text-left dark:text-white">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($languages as $lang): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="border p-2 text-2xl"><?php echo htmlspecialchars($lang['flag']); ?></td>
                        <td class="border p-2 dark:text-white"><?php echo htmlspecialchars($lang['code']); ?></td>
                        <td class="border p-2 dark:text-white"><?php echo htmlspecialchars($lang['name']); ?></td>
                        <td class="border p-2 dark:text-white"><?php echo htmlspecialchars($lang['native_name']); ?></td>
                        <td class="border p-2">
                            <span class="px-2 py-1 rounded text-sm <?php echo $lang['is_active'] ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-300'; ?>">
                                <?php echo $lang['is_active'] ? 'Aktif' : 'Pasif'; ?>
                            </span>
                        </td>
                        <td class="border p-2">
                            <form method="POST" action="<?php echo admin_url('module/translation/languages'); ?>" onsubmit="return confirm('Bu dili silmek istediğinizden emin misiniz?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $lang['id']; ?>">
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">Sil</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
