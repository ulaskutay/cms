<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-white">Çeviri Düzenle</h1>
        <a href="<?php echo admin_url('module/translation/translations'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Geri Dön
        </a>
    </div>
    
    <form method="POST" action="<?php echo admin_url('module/translation/translation_edit/' . $translation['id']); ?>">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1 dark:text-white">Kaynak Metin</label>
            <textarea readonly class="w-full px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700 dark:border-gray-500 dark:text-white" rows="4"><?php echo htmlspecialchars($translation['source_text']); ?></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1 dark:text-white">Çeviri</label>
            <textarea name="translated_text" required class="w-full px-3 py-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white" rows="8"><?php echo htmlspecialchars($translation['translated_text']); ?></textarea>
        </div>
        
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="auto_translated" value="1" <?php echo $translation['auto_translated'] ? 'checked' : ''; ?> class="rounded">
                <span class="dark:text-white">Manuel Çeviri (işaretlenirse otomatik çeviri değil, manuel düzenleme olduğu anlamına gelir)</span>
            </label>
        </div>
        
        <div class="mb-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <strong>Dil:</strong> <?php echo strtoupper($translation['target_language']); ?> | 
                <strong>Tip:</strong> <?php echo htmlspecialchars($translation['type']); ?>
            </div>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Kaydet</button>
            <a href="<?php echo admin_url('module/translation/translations'); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">İptal</a>
        </div>
    </form>
</div>
