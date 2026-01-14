<?php
/**
 * Emlak Sektörü Blog Yazıları Ekleme Scripti
 * 3 adet emlak sektörü ile ilgili blog yazısı ekler
 */

// Config dosyası kontrolü
$configFile = __DIR__ . '/../config/database.php';
if (!file_exists($configFile)) {
    die("Hata: Veritabanı config dosyası bulunamadı!\nLütfen önce kurulumu tamamlayın: /install.php\n");
}

// Bootstrap
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../app/models/Post.php';
require_once __DIR__ . '/../app/models/PostCategory.php';
require_once __DIR__ . '/../app/models/User.php';

// Veritabanı bağlantısı
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    die("Hata: Veritabanı bağlantısı kurulamadı!\n" . $e->getMessage() . "\n");
}

// Modeller
$postModel = new Post();
$categoryModel = new PostCategory();
$userModel = new User();

// İlk admin kullanıcısını bul
$adminUser = $db->fetch("SELECT id FROM users WHERE role IN ('super_admin', 'admin') ORDER BY id ASC LIMIT 1");

if (!$adminUser) {
    die("Hata: Admin kullanıcısı bulunamadı! Önce bir admin kullanıcısı oluşturmalısınız.\n");
}

$authorId = $adminUser['id'];

// Emlak kategorisini kontrol et veya oluştur
$emlakCategory = $categoryModel->findBySlug('emlak');

if (!$emlakCategory) {
    // Kategori oluştur
    $categoryData = [
        'name' => 'Emlak',
        'slug' => 'emlak',
        'description' => 'Emlak sektörü ile ilgili yazılar, ipuçları ve haberler',
        'status' => 'active',
        'order' => 0
    ];
    
    $categoryId = $categoryModel->createCategory($categoryData);
    echo "✓ Emlak kategorisi oluşturuldu (ID: {$categoryId})\n";
} else {
    $categoryId = $emlakCategory['id'];
    echo "✓ Emlak kategorisi mevcut (ID: {$categoryId})\n";
}

// Blog yazıları
$blogPosts = [
    [
        'title' => '2024 Emlak Piyasası Trendleri: Yatırımcılar İçin Rehber',
        'slug' => '2024-emlak-piyasasi-trendleri-yatirimcilar-icin-rehber',
        'excerpt' => '2024 yılında emlak piyasasında yaşanan değişimler, yatırımcılar için fırsatlar ve dikkat edilmesi gereken noktalar hakkında kapsamlı bir rehber.',
        'content' => '<h2>2024 Emlak Piyasasına Genel Bakış</h2>
<p>2024 yılı, emlak sektörü için önemli dönüşümlerin yaşandığı bir yıl oldu. Faiz oranlarındaki değişimler, konut kredisi politikaları ve ekonomik belirsizlikler, piyasayı derinden etkiledi. Bu yazıda, yatırımcılar ve ev almak isteyenler için kritik bilgileri derledik.</p>

<h3>1. Faiz Oranları ve Kredi Politikaları</h3>
<p>Merkez Bankası\'nın faiz oranlarında yaptığı düzenlemeler, emlak piyasasını doğrudan etkiledi. Düşük faizli dönemlerde konut talebi artarken, yükselen faizlerle birlikte piyasada bir yavaşlama gözlemlendi. Ancak, bu durum yatırımcılar için fırsatlar da yaratabilir.</p>

<h3>2. Şehirler Arası Fiyat Farkları</h3>
<p>İstanbul, Ankara ve İzmir gibi büyük şehirlerde emlak fiyatları yükselmeye devam ederken, Anadolu\'daki bazı şehirlerde daha uygun fiyatlı seçenekler bulunabiliyor. Yatırım yapmak isteyenler için, şehirler arası karşılaştırma yapmak önemli.</p>

<h3>3. Yeni Konut Projeleri ve Gelişim Bölgeleri</h3>
<p>2024 yılında birçok yeni konut projesi hayata geçti. Özellikle metro hatlarının genişlemesi ve ulaşım altyapısının iyileştirilmesi, bazı bölgelerin değer kazanmasına neden oldu. Yatırımcılar için bu gelişim bölgeleri önemli fırsatlar sunuyor.</p>

<h3>4. Dijitalleşme ve Online Emlak Platformları</h3>
<p>Emlak sektöründe dijitalleşme hızla ilerliyor. Online platformlar üzerinden ev arama, sanal tur imkanları ve dijital imza süreçleri, alıcı ve satıcılar için süreçleri kolaylaştırıyor.</p>

<h3>5. Sürdürülebilir ve Enerji Verimli Konutlar</h3>
<p>Çevre bilincinin artmasıyla birlikte, enerji verimli ve sürdürülebilir konutlara olan talep arttı. Bu özelliklere sahip konutlar, hem çevre dostu hem de uzun vadede maliyet tasarrufu sağlıyor.</p>

<h2>Yatırımcılar İçin Öneriler</h2>
<ul>
<li>Uzun vadeli yatırım planı yapın ve piyasa dalgalanmalarına hazırlıklı olun</li>
<li>Farklı bölgeleri araştırın ve gelişim potansiyeli olan alanları değerlendirin</li>
<li>Finansal durumunuzu gözden geçirin ve kredi imkanlarını araştırın</li>
<li>Profesyonel emlak danışmanlarından destek alın</li>
<li>Yasal süreçleri iyi anlayın ve gerekli belgeleri hazırlayın</li>
</ul>

<h2>Sonuç</h2>
<p>2024 emlak piyasası, hem zorluklar hem de fırsatlar sunuyor. Doğru araştırma, planlama ve profesyonel destekle, emlak yatırımlarından başarılı sonuçlar almak mümkün. Piyasayı yakından takip etmek ve zamanlama yapmak, yatırımcılar için kritik öneme sahip.</p>',
        'featured_image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&h=800&fit=crop',
        'meta_title' => '2024 Emlak Piyasası Trendleri | Yatırımcı Rehberi',
        'meta_description' => '2024 yılı emlak piyasası trendleri, yatırım fırsatları ve dikkat edilmesi gereken noktalar hakkında kapsamlı rehber.',
        'meta_keywords' => 'emlak, yatırım, konut, 2024, piyasa, trend, rehber'
    ],
    [
        'title' => 'Emlak Alırken Dikkat Edilmesi Gereken 10 Önemli Nokta',
        'slug' => 'emlak-alirken-dikkat-edilmesi-gereken-10-onemli-nokta',
        'excerpt' => 'Emlak alımında karşılaşabileceğiniz tuzaklardan kaçınmak ve doğru yatırım yapmak için dikkat etmeniz gereken kritik noktalar.',
        'content' => '<h2>Emlak Alımında Kritik Kontrol Listesi</h2>
<p>Emlak alımı, hayatınızın en önemli finansal kararlarından biri olabilir. Bu nedenle, her adımı dikkatle planlamak ve potansiyel sorunları önceden tespit etmek çok önemlidir. İşte emlak alırken mutlaka kontrol etmeniz gereken 10 önemli nokta:</p>

<h3>1. Tapu Durumu ve Yasal Kontroller</h3>
<p>Tapu belgesini mutlaka inceleyin. İmar durumu, yapı ruhsatı, iskan belgesi gibi yasal belgelerin tam olup olmadığını kontrol edin. Tapu üzerinde herhangi bir haciz, ipotek veya şerh olup olmadığını araştırın.</p>

<h3>2. Konum ve Çevre Analizi</h3>
<p>Emlakın bulunduğu bölgenin gelişim potansiyelini değerlendirin. Ulaşım imkanları, okullar, hastaneler, alışveriş merkezleri gibi sosyal donatıların yakınlığını kontrol edin. Gelecekteki projeler ve bölge planlamaları hakkında bilgi edinin.</p>

<h3>3. Yapı Kalitesi ve Teknik Kontroller</h3>
<p>Binanın yaşı, yapı malzemeleri, deprem dayanıklılığı gibi teknik özellikleri mutlaka kontrol edin. Gerekirse profesyonel bir mühendis veya mimardan teknik rapor alın. Su tesisatı, elektrik tesisatı, ısıtma sistemi gibi altyapı sistemlerini inceleyin.</p>

<h3>4. Finansal Planlama ve Bütçe</h3>
<p>Emlak fiyatının yanı sıra, noter masrafları, tapu harcı, emlak vergisi, sigorta gibi ek maliyetleri de hesaplayın. Kredi imkanlarınızı önceden araştırın ve ödeme planınızı netleştirin.</p>

<h3>5. Komşuluk ve Çevre Faktörleri</h3>
<p>Bölgede yaşayan komşularla konuşun, çevredeki gürültü kaynaklarını, güvenlik durumunu araştırın. Gece ve gündüz farklı saatlerde bölgeyi ziyaret ederek gerçek yaşam koşullarını gözlemleyin.</p>

<h3>6. Enerji Verimliliği ve Faturalar</h3>
<p>Emlakın enerji sınıfını öğrenin. Isıtma ve soğutma maliyetlerini tahmin edin. Eski binalarda enerji verimliliği düşük olabilir ve bu durum aylık giderlerinizi önemli ölçüde artırabilir.</p>

<h3>7. Ortak Alanlar ve Yönetim</h3>
<p>Apartman veya site yönetiminin nasıl çalıştığını öğrenin. Aidat tutarları, yönetim kalitesi, ortak alanların bakım durumu gibi konuları araştırın. Yönetim kurulu toplantı tutanaklarını inceleyin.</p>

<h3>8. Gelecekteki Değer Artış Potansiyeli</h3>
<p>Bölgenin gelişim planlarını, yeni projeleri, ulaşım altyapısındaki iyileştirmeleri araştırın. Bu faktörler, emlağınızın gelecekteki değer artışını doğrudan etkiler.</p>

<h3>9. Sigorta ve Risk Yönetimi</h3>
<p>DASK (Doğal Afet Sigortası) zorunludur. Ayrıca konut sigortası yaptırmayı düşünün. Sigorta kapsamını ve maliyetlerini önceden araştırın.</p>

<h3>10. Profesyonel Destek ve Hukuki Danışmanlık</h3>
<p>Emlak alımında mutlaka profesyonel bir emlak danışmanı ve hukuki danışman desteği alın. Sözleşmeleri dikkatle okuyun, anlamadığınız noktaları sorun. Noter işlemlerini eksiksiz tamamlayın.</p>

<h2>Ekstra İpuçları</h2>
<ul>
<li>Birden fazla emlak seçeneğini karşılaştırın</li>
<li>Piyasa fiyat araştırması yapın</li>
<li>Müzakere sürecinde sabırlı olun</li>
<li>Tüm görüşmeleri yazılı olarak kaydedin</li>
<li>Son kararı vermeden önce bir gece düşünün</li>
</ul>

<h2>Sonuç</h2>
<p>Emlak alımı, dikkatli planlama ve araştırma gerektiren bir süreçtir. Yukarıdaki noktaları kontrol ederek, hem finansal hem de yaşam kalitesi açısından doğru kararı verebilirsiniz. Unutmayın, aceleci davranmak yerine, her adımı dikkatle atmanız, uzun vadede çok daha iyi sonuçlar almanızı sağlayacaktır.</p>',
        'featured_image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&h=800&fit=crop',
        'meta_title' => 'Emlak Alırken Dikkat Edilmesi Gerekenler | Kontrol Listesi',
        'meta_description' => 'Emlak alımında dikkat edilmesi gereken 10 kritik nokta. Tapu, konum, yapı kalitesi ve daha fazlası hakkında detaylı rehber.',
        'meta_keywords' => 'emlak alımı, dikkat edilmesi gerekenler, kontrol listesi, yatırım, konut'
    ],
    [
        'title' => 'Emlak Yatırımında Kira Getirisi Hesaplama ve Stratejileri',
        'slug' => 'emlak-yatiriminda-kira-getirisi-hesaplama-ve-stratejileri',
        'excerpt' => 'Emlak yatırımında kira getirisi nasıl hesaplanır? Yatırım getirisi oranları, kira artış stratejileri ve pasif gelir elde etme yöntemleri.',
        'content' => '<h2>Emlak Yatırımında Getiri Hesaplama</h2>
<p>Emlak yatırımı, uzun vadeli bir yatırım stratejisidir ve doğru hesaplamalarla önemli bir pasif gelir kaynağı olabilir. Bu yazıda, emlak yatırımında kira getirisi hesaplama yöntemlerini ve stratejilerini detaylı olarak ele alacağız.</p>

<h3>1. Kira Getirisi Oranı (Rental Yield) Nedir?</h3>
<p>Kira getirisi oranı, yıllık kira gelirinin emlak değerine oranıdır. Bu oran, yatırımın ne kadar karlı olduğunu gösterir. Genel olarak, %5-8 arası getiri oranı sağlıklı kabul edilir.</p>

<p><strong>Hesaplama Formülü:</strong></p>
<p>Kira Getirisi Oranı = (Yıllık Kira Geliri / Emlak Değeri) × 100</p>

<p><strong>Örnek:</strong></p>
<ul>
<li>Emlak Değeri: 2.000.000 TL</li>
<li>Aylık Kira: 15.000 TL</li>
<li>Yıllık Kira: 15.000 × 12 = 180.000 TL</li>
<li>Getiri Oranı: (180.000 / 2.000.000) × 100 = %9</li>
</ul>

<h3>2. Net Getiri Hesaplama</h3>
<p>Brüt getiri hesaplaması yeterli değildir. Vergiler, sigorta, bakım-onarım, yönetim giderleri gibi maliyetleri de hesaba katmak gerekir.</p>

<p><strong>Net Getiri = (Yıllık Kira Geliri - Yıllık Giderler) / Emlak Değeri × 100</strong></p>

<p><strong>Yıllık Giderler:</strong></p>
<ul>
<li>Emlak vergisi</li>
<li>Sigorta (DASK + Konut Sigortası)</li>
<li>Yönetim giderleri (apartman aidatı)</li>
<li>Bakım ve onarım (yıllık %1-2)</li>
<li>Boş kalma riski (yıllık %5-10)</li>
<li>Gelir vergisi (kira geliri üzerinden)</li>
</ul>

<h3>3. Yatırım Getirisi (ROI) Hesaplama</h3>
<p>ROI, toplam yatırım maliyetine göre net getiriyi gösterir. Bu hesaplamada, peşinat, kredi faizleri, noter masrafları gibi tüm maliyetler dahil edilir.</p>

<p><strong>ROI = (Yıllık Net Gelir / Toplam Yatırım) × 100</strong></p>

<h3>4. Kira Artış Stratejileri</h3>

<h4>a) Piyasa Araştırması</h4>
<p>Bölgedeki benzer emlakların kira fiyatlarını sürekli takip edin. Piyasa koşullarına göre kira artışı yapın.</p>

<h4>b) Emlak Değerini Artırma</h4>
<p>Emlakınızın değerini artırarak, kira potansiyelini de yükseltebilirsiniz:</p>
<ul>
<li>Modernizasyon ve yenileme çalışmaları</li>
<li>Enerji verimliliği iyileştirmeleri</li>
<li>Balkon, bahçe gibi ek alanlar</li>
<li>Güvenlik sistemleri</li>
</ul>

<h4>c) Uzun Vadeli Kiracı Bulma</h4>
<p>Uzun vadeli kiracılar, boş kalma riskini azaltır ve düzenli gelir sağlar. Güvenilir kiracı bulmak için:</p>
<ul>
<li>Detaylı kiracı referans kontrolü</li>
<li>Düzenli kira ödemesi takibi</li>
<li>İyi iletişim ve hızlı sorun çözme</li>
</ul>

<h3>5. Vergi Avantajları</h3>
<p>Emlak yatırımında vergi avantajlarından yararlanabilirsiniz:</p>
<ul>
<li>Gayrimenkul alımında KDV indirimi (yeni konutlarda)</li>
<li>Kira geliri üzerinden gider indirimi</li>
<li>Yatırım teşvikleri (bazı bölgelerde)</li>
</ul>

<h3>6. Risk Yönetimi</h3>
<p>Emlak yatırımında riskleri minimize etmek için:</p>
<ul>
<li>Sigorta yaptırın (DASK + Konut Sigortası)</li>
<li>Yedek fon oluşturun (3-6 aylık giderler)</li>
<li>Farklı lokasyonlara yatırım yaparak çeşitlendirin</li>
<li>Piyasa analizi yapın ve trendleri takip edin</li>
</ul>

<h3>7. Finansman Stratejileri</h3>
<p>Kredi ile emlak alımında:</p>
<ul>
<li>Faiz oranlarını karşılaştırın</li>
<li>Erken ödeme seçeneklerini değerlendirin</li>
<li>Kira geliri ile kredi taksitini karşılaştırın</li>
<li>Uzun vadeli kredi planları yapın</li>
</ul>

<h2>Örnek Yatırım Senaryosu</h2>
<p><strong>Senaryo:</strong></p>
<ul>
<li>Emlak Değeri: 1.500.000 TL</li>
<li>Peşinat: 300.000 TL (%20)</li>
<li>Kredi: 1.200.000 TL (20 yıl, %2 faiz)</li>
<li>Aylık Kira: 12.000 TL</li>
<li>Aylık Kredi Taksiti: 6.000 TL</li>
</ul>

<p><strong>Hesaplama:</strong></p>
<ul>
<li>Yıllık Kira: 144.000 TL</li>
<li>Yıllık Kredi Ödemesi: 72.000 TL</li>
<li>Yıllık Giderler (tahmini): 20.000 TL</li>
<li>Net Gelir: 144.000 - 72.000 - 20.000 = 52.000 TL</li>
<li>ROI: (52.000 / 300.000) × 100 = %17.3</li>
</ul>

<h2>Sonuç ve Öneriler</h2>
<p>Emlak yatırımı, doğru hesaplama ve strateji ile önemli bir pasif gelir kaynağı olabilir. Ancak, sadece kira getirisi değil, emlak değer artışı da yatırımın toplam getirisini etkiler. Uzun vadeli bir bakış açısıyla, piyasa araştırması yaparak ve profesyonel destek alarak, başarılı emlak yatırımları yapabilirsiniz.</p>

<p><strong>Altın Kurallar:</strong></p>
<ul>
<li>Detaylı hesaplama yapın</li>
<li>Giderleri unutmayın</li>
<li>Uzun vadeli planlama yapın</li>
<li>Piyasayı sürekli takip edin</li>
<li>Profesyonel destek alın</li>
</ul>',
        'featured_image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200&h=800&fit=crop',
        'meta_title' => 'Emlak Yatırımında Kira Getirisi Hesaplama | Stratejiler',
        'meta_description' => 'Emlak yatırımında kira getirisi nasıl hesaplanır? ROI, net getiri, kira artış stratejileri ve pasif gelir yöntemleri hakkında rehber.',
        'meta_keywords' => 'emlak yatırımı, kira getirisi, ROI, pasif gelir, yatırım stratejileri'
    ]
];

// Blog yazılarını ekle
echo "\n=== Blog Yazıları Ekleniyor ===\n\n";

foreach ($blogPosts as $index => $postData) {
    // Slug kontrolü
    $existingPost = $postModel->findBySlug($postData['slug']);
    
    if ($existingPost) {
        echo "⚠ Yazı zaten mevcut: {$postData['title']}\n";
        continue;
    }
    
    // Yazı verilerini hazırla
    $postInsertData = [
        'title' => $postData['title'],
        'slug' => $postData['slug'],
        'excerpt' => $postData['excerpt'],
        'content' => $postData['content'],
        'featured_image' => $postData['featured_image'],
        'category_id' => $categoryId,
        'author_id' => $authorId,
        'status' => 'published',
        'type' => 'post',
        'visibility' => 'public',
        'published_at' => date('Y-m-d H:i:s', strtotime("-" . (2 - $index) . " days")),
        'meta_title' => $postData['meta_title'],
        'meta_description' => $postData['meta_description'],
        'meta_keywords' => $postData['meta_keywords'],
        'allow_comments' => 1,
        'views' => rand(50, 500) // Rastgele görüntülenme sayısı
    ];
    
    // Yazıyı oluştur
    $postId = $postModel->createPost($postInsertData);
    
    if ($postId) {
        echo "✓ Blog yazısı eklendi: {$postData['title']} (ID: {$postId})\n";
    } else {
        echo "✗ Hata: {$postData['title']} eklenemedi\n";
    }
}

echo "\n=== İşlem Tamamlandı ===\n";
echo "Toplam " . count($blogPosts) . " blog yazısı işlendi.\n";
echo "Blog yazılarınızı /blog sayfasından görüntüleyebilirsiniz.\n";

// Web üzerinden çalıştırılıyorsa HTML çıktısı
if (php_sapi_name() !== 'cli') {
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Blog Yazıları Eklendi</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .success { color: #28a745; font-weight: bold; }
        .info { background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>✓ Blog Yazıları Başarıyla Eklendi!</h1>
        <div class='info'>
            <p><strong>3 adet emlak sektörü blog yazısı</strong> veritabanına eklendi.</p>
            <p>Blog yazılarınızı görüntülemek için: <a href='/blog'>/blog</a></p>
        </div>
    </div>
</body>
</html>";
}
