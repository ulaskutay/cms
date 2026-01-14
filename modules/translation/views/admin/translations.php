<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Çeviriler</h1>
        <a href="<?php echo admin_url('module/translation'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Geri Dön
        </a>
    </div>
    
    <div class="mb-4">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="hidden" name="page" value="module/translation/translations">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>" placeholder="Ara..." class="px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
            <select name="language" class="px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                <option value="">Tüm Diller</option>
                <?php foreach ($languages as $lang): ?>
                    <option value="<?php echo $lang['code']; ?>" <?php echo ($selectedLanguage ?? '') === $lang['code'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($lang['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="type" class="px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                <option value="">Tüm Tipler</option>
                <option value="content" <?php echo ($selectedType ?? '') === 'content' ? 'selected' : ''; ?>>İçerik</option>
                <option value="title" <?php echo ($selectedType ?? '') === 'title' ? 'selected' : ''; ?>>Başlık</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Filtrele</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="border p-2 text-left dark:text-white">Kaynak Metin</th>
                    <th class="border p-2 text-left dark:text-white">Çeviri</th>
                    <th class="border p-2 text-left dark:text-white">Dil</th>
                    <th class="border p-2 text-left dark:text-white">Tip</th>
                    <th class="border p-2 text-left dark:text-white">Durum</th>
                    <th class="border p-2 text-left dark:text-white">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($translations as $trans): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="border p-2 dark:text-white" style="max-width: 300px;">
                            <div class="truncate" title="<?php echo htmlspecialchars($trans['source_text']); ?>">
                                <?php echo htmlspecialchars(substr($trans['source_text'], 0, 100)); ?><?php echo strlen($trans['source_text']) > 100 ? '...' : ''; ?>
                            </div>
                        </td>
                        <td class="border p-2 dark:text-white" style="max-width: 300px;">
                            <div class="truncate" title="<?php echo htmlspecialchars($trans['translated_text']); ?>">
                                <?php echo htmlspecialchars(substr($trans['translated_text'], 0, 100)); ?><?php echo strlen($trans['translated_text']) > 100 ? '...' : ''; ?>
                            </div>
                        </td>
                        <td class="border p-2 dark:text-white"><?php echo htmlspecialchars(strtoupper($trans['target_language'])); ?></td>
                        <td class="border p-2 dark:text-white"><?php echo htmlspecialchars($trans['type']); ?></td>
                        <td class="border p-2">
                            <span class="px-2 py-1 rounded text-sm <?php echo $trans['auto_translated'] ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300'; ?>">
                                <?php echo $trans['auto_translated'] ? 'Otomatik' : 'Manuel'; ?>
                            </span>
                        </td>
                        <td class="border p-2">
                            <a href="<?php echo admin_url('module/translation/translation_edit/' . $trans['id']); ?>" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Düzenle</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($totalPages > 1): ?>
        <div class="mt-4 flex gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?php echo admin_url('module/translation/translations', ['p' => $i, 'search' => $search, 'language' => $selectedLanguage, 'type' => $selectedType]); ?>" 
                   class="px-3 py-1 border rounded <?php echo $currentPage == $i ? 'bg-blue-500 text-white' : 'dark:bg-gray-700 dark:text-white'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
