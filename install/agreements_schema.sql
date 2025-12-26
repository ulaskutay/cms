-- Sözleşmeler (Agreements) Sistemi Veritabanı Şeması
-- Gizlilik Politikası, KVKK, Kullanım Şartları, Çerez Politikası vb.
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Sözleşmeler Tablosu
CREATE TABLE IF NOT EXISTS `agreements` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL COMMENT 'Sözleşme başlığı',
  `slug` varchar(500) NOT NULL COMMENT 'URL slug',
  `content` longtext DEFAULT NULL COMMENT 'Sözleşme içeriği (HTML)',
  `type` enum('privacy','kvkk','terms','cookies','other') NOT NULL DEFAULT 'other' COMMENT 'Sözleşme türü',
  `status` enum('draft','published') DEFAULT 'draft' COMMENT 'Yayın durumu',
  `version` int(11) DEFAULT 1 COMMENT 'Mevcut versiyon numarası',
  `author_id` int(11) DEFAULT NULL COMMENT 'Oluşturan kullanıcı',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'SEO başlık',
  `meta_description` varchar(500) DEFAULT NULL COMMENT 'SEO açıklama',
  `published_at` datetime DEFAULT NULL COMMENT 'Yayın tarihi',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sözleşme Versiyonları Tablosu (Değişiklik Geçmişi)
CREATE TABLE IF NOT EXISTS `agreement_versions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `agreement_id` bigint(20) NOT NULL COMMENT 'Ana sözleşme ID',
  `version_number` int(11) NOT NULL COMMENT 'Versiyon numarası',
  `title` varchar(500) NOT NULL COMMENT 'Başlık (o anki)',
  `content` longtext DEFAULT NULL COMMENT 'İçerik (o anki)',
  `change_note` text DEFAULT NULL COMMENT 'Değişiklik notu',
  `author_id` int(11) DEFAULT NULL COMMENT 'Değişikliği yapan kullanıcı',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Versiyon oluşturma tarihi',
  PRIMARY KEY (`id`),
  KEY `agreement_id` (`agreement_id`),
  KEY `version_number` (`version_number`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Foreign Keys
ALTER TABLE `agreements` ADD CONSTRAINT `fk_agreements_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `agreement_versions` ADD CONSTRAINT `fk_versions_agreement` FOREIGN KEY (`agreement_id`) REFERENCES `agreements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `agreement_versions` ADD CONSTRAINT `fk_versions_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- =====================================================
-- HAZIR SÖZLEŞME ŞABLONLARI
-- [ŞİRKET ADI], [WEB SİTESİ], [E-POSTA], [ADRES] gibi
-- alanları kendi bilgilerinizle değiştirin.
-- =====================================================

-- 1. Gizlilik Politikası
INSERT INTO `agreements` (`title`, `slug`, `content`, `type`, `status`, `version`, `meta_title`, `meta_description`) VALUES
('Gizlilik Politikası', 'gizlilik-politikasi', 
'<h2>1. Giriş</h2>
<p><strong>[ŞİRKET ADI]</strong> olarak, web sitemizi (<strong>[WEB SİTESİ]</strong>) ziyaret eden kullanıcılarımızın gizliliğini korumaya büyük önem veriyoruz. Bu Gizlilik Politikası, kişisel verilerinizin nasıl toplandığını, kullanıldığını ve korunduğunu açıklamaktadır.</p>

<h2>2. Toplanan Bilgiler</h2>
<p>Web sitemizi kullandığınızda aşağıdaki bilgiler toplanabilir:</p>
<ul>
<li><strong>Kimlik Bilgileri:</strong> Ad, soyad, e-posta adresi, telefon numarası</li>
<li><strong>İletişim Bilgileri:</strong> Adres, şehir, posta kodu</li>
<li><strong>Teknik Bilgiler:</strong> IP adresi, tarayıcı türü, işletim sistemi, ziyaret edilen sayfalar</li>
<li><strong>Çerez Bilgileri:</strong> Oturum çerezleri, tercih çerezleri, analitik çerezler</li>
</ul>

<h2>3. Bilgilerin Kullanım Amaçları</h2>
<p>Topladığımız bilgiler aşağıdaki amaçlarla kullanılmaktadır:</p>
<ul>
<li>Hizmetlerimizi sunmak ve geliştirmek</li>
<li>Müşteri taleplerini yanıtlamak</li>
<li>Yasal yükümlülüklerimizi yerine getirmek</li>
<li>Pazarlama ve iletişim faaliyetleri (izniniz dahilinde)</li>
<li>Web sitesi güvenliğini sağlamak</li>
<li>İstatistiksel analizler yapmak</li>
</ul>

<h2>4. Bilgilerin Paylaşılması</h2>
<p>Kişisel verileriniz aşağıdaki durumlar dışında üçüncü taraflarla paylaşılmaz:</p>
<ul>
<li>Yasal zorunluluk durumlarında yetkili makamlarla</li>
<li>Hizmet sağlayıcılarımızla (güvenlik önlemleri dahilinde)</li>
<li>Açık rızanızın bulunduğu durumlarda</li>
</ul>

<h2>5. Veri Güvenliği</h2>
<p>Kişisel verilerinizin güvenliğini sağlamak için gerekli teknik ve idari önlemleri alıyoruz. SSL şifreleme, güvenlik duvarları ve düzenli güvenlik denetimleri uygulanmaktadır.</p>

<h2>6. Haklarınız</h2>
<p>Kişisel verilerinizle ilgili aşağıdaki haklara sahipsiniz:</p>
<ul>
<li>Verilerinize erişim hakkı</li>
<li>Verilerin düzeltilmesini isteme hakkı</li>
<li>Verilerin silinmesini isteme hakkı</li>
<li>İşlemeye itiraz etme hakkı</li>
<li>Veri taşınabilirliği hakkı</li>
</ul>

<h2>7. İletişim</h2>
<p>Gizlilik politikamız hakkında sorularınız için bizimle iletişime geçebilirsiniz:</p>
<ul>
<li><strong>E-posta:</strong> [E-POSTA]</li>
<li><strong>Adres:</strong> [ADRES]</li>
</ul>

<h2>8. Değişiklikler</h2>
<p>Bu gizlilik politikası zaman zaman güncellenebilir. Önemli değişiklikler web sitemizde duyurulacaktır.</p>

<p><em>Son güncelleme: [TARİH]</em></p>', 
'privacy', 'draft', 1, 
'Gizlilik Politikası | [ŞİRKET ADI]', 
'[ŞİRKET ADI] gizlilik politikası. Kişisel verilerinizin nasıl toplandığını, kullanıldığını ve korunduğunu öğrenin.');

-- 2. KVKK Aydınlatma Metni
INSERT INTO `agreements` (`title`, `slug`, `content`, `type`, `status`, `version`, `meta_title`, `meta_description`) VALUES
('KVKK Aydınlatma Metni', 'kvkk-aydinlatma-metni', 
'<h2>Veri Sorumlusu</h2>
<p><strong>[ŞİRKET ADI]</strong><br>
Adres: [ADRES]<br>
E-posta: [E-POSTA]</p>

<h2>1. Kişisel Verilerin İşlenme Amacı</h2>
<p>6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:</p>
<ul>
<li>Sözleşme süreçlerinin yürütülmesi</li>
<li>Mal ve hizmet satış süreçlerinin yürütülmesi</li>
<li>Müşteri ilişkileri yönetimi süreçlerinin yürütülmesi</li>
<li>İletişim faaliyetlerinin yürütülmesi</li>
<li>Pazarlama ve analiz çalışmalarının yürütülmesi</li>
<li>Finans ve muhasebe işlerinin yürütülmesi</li>
<li>Hukuki süreçlerin takibi ve yürütülmesi</li>
<li>Yetkili kişi, kurum ve kuruluşlara bilgi verilmesi</li>
</ul>

<h2>2. İşlenen Kişisel Veri Kategorileri</h2>
<ul>
<li><strong>Kimlik Bilgileri:</strong> Ad, soyad, T.C. kimlik numarası, doğum tarihi</li>
<li><strong>İletişim Bilgileri:</strong> Telefon numarası, e-posta adresi, adres</li>
<li><strong>Müşteri İşlem Bilgileri:</strong> Sipariş bilgileri, fatura bilgileri</li>
<li><strong>Fiziksel Mekan Güvenliği:</strong> Kamera kayıtları (varsa)</li>
<li><strong>İşlem Güvenliği:</strong> IP adresi, log kayıtları</li>
<li><strong>Pazarlama Bilgileri:</strong> Alışveriş geçmişi, tercihler</li>
</ul>

<h2>3. Kişisel Verilerin Aktarılması</h2>
<p>Kişisel verileriniz, yukarıda belirtilen amaçlarla sınırlı olmak üzere:</p>
<ul>
<li>İş ortaklarımıza</li>
<li>Tedarikçilerimize</li>
<li>Kanunen yetkili kamu kurum ve kuruluşlarına</li>
<li>Kanunen yetkili özel hukuk kişilerine</li>
</ul>
<p>aktarılabilecektir.</p>

<h2>4. Kişisel Verilerin Toplanma Yöntemi ve Hukuki Sebebi</h2>
<p>Kişisel verileriniz;</p>
<ul>
<li>Web sitemiz üzerinden doldurulan formlar</li>
<li>E-posta, telefon ve diğer iletişim kanalları</li>
<li>Çerezler ve benzer teknolojiler</li>
<li>Fiziksel ortamda toplanan belgeler</li>
</ul>
<p>aracılığıyla, KVKK''nın 5. ve 6. maddelerinde belirtilen hukuki sebeplere dayanarak toplanmaktadır.</p>

<h2>5. KVKK Kapsamındaki Haklarınız</h2>
<p>KVKK''nın 11. maddesi kapsamında aşağıdaki haklara sahipsiniz:</p>
<ul>
<li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
<li>Kişisel verileriniz işlenmişse buna ilişkin bilgi talep etme</li>
<li>Kişisel verilerin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
<li>Yurt içinde veya yurt dışında kişisel verilerin aktarıldığı üçüncü kişileri bilme</li>
<li>Kişisel verilerin eksik veya yanlış işlenmiş olması halinde bunların düzeltilmesini isteme</li>
<li>KVKK''nın 7. maddesinde öngörülen şartlar çerçevesinde kişisel verilerin silinmesini veya yok edilmesini isteme</li>
<li>Düzeltme, silme veya yok etme işlemlerinin kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
<li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme</li>
<li>Kişisel verilerin kanuna aykırı olarak işlenmesi sebebiyle zarara uğramanız halinde zararın giderilmesini talep etme</li>
</ul>

<h2>6. Başvuru Yöntemi</h2>
<p>Yukarıda belirtilen haklarınızı kullanmak için:</p>
<ul>
<li><strong>E-posta:</strong> [E-POSTA] adresine yazılı başvuru</li>
<li><strong>Posta:</strong> [ADRES] adresine ıslak imzalı başvuru</li>
</ul>
<p>yöntemlerinden birini kullanabilirsiniz.</p>

<p><em>Son güncelleme: [TARİH]</em></p>', 
'kvkk', 'draft', 1, 
'KVKK Aydınlatma Metni | [ŞİRKET ADI]', 
'6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında aydınlatma metni.');

-- 3. Kullanım Şartları
INSERT INTO `agreements` (`title`, `slug`, `content`, `type`, `status`, `version`, `meta_title`, `meta_description`) VALUES
('Kullanım Şartları', 'kullanim-sartlari', 
'<h2>1. Kabul</h2>
<p><strong>[WEB SİTESİ]</strong> web sitesini ("Site") kullanarak bu Kullanım Şartlarını kabul etmiş sayılırsınız. Bu şartları kabul etmiyorsanız, lütfen siteyi kullanmayınız.</p>

<h2>2. Hizmet Tanımı</h2>
<p><strong>[ŞİRKET ADI]</strong> olarak, bu site üzerinden [HİZMET TANIMI] sunmaktayız. Hizmetlerimizin kapsamı ve içeriği zaman zaman değişiklik gösterebilir.</p>

<h2>3. Kullanıcı Yükümlülükleri</h2>
<p>Site kullanıcıları olarak:</p>
<ul>
<li>Doğru ve güncel bilgiler sağlamayı kabul edersiniz</li>
<li>Hesap bilgilerinizin güvenliğinden siz sorumlusunuz</li>
<li>Siteyi yasalara uygun şekilde kullanmayı taahhüt edersiniz</li>
<li>Başkalarının haklarına saygı göstermeyi kabul edersiniz</li>
<li>Site güvenliğini tehlikeye atacak eylemlerden kaçınmayı taahhüt edersiniz</li>
</ul>

<h2>4. Yasaklı Kullanımlar</h2>
<p>Aşağıdaki eylemler kesinlikle yasaktır:</p>
<ul>
<li>Yasa dışı amaçlarla site kullanımı</li>
<li>Zararlı yazılım yayma</li>
<li>Spam veya istenmeyen içerik gönderme</li>
<li>Başkalarının kişisel bilgilerini izinsiz toplama</li>
<li>Sitenin güvenlik önlemlerini aşmaya çalışma</li>
<li>Telif hakkı ihlali yapma</li>
<li>Yanıltıcı veya yanlış bilgi yayma</li>
</ul>

<h2>5. Fikri Mülkiyet Hakları</h2>
<p>Site üzerindeki tüm içerik, tasarım, logo, metin, grafik ve diğer materyaller <strong>[ŞİRKET ADI]</strong>''nin mülkiyetindedir ve telif hakları ile korunmaktadır. İzinsiz kullanım, kopyalama veya dağıtım yasaktır.</p>

<h2>6. Üçüncü Taraf Bağlantıları</h2>
<p>Sitemiz üçüncü taraf web sitelerine bağlantılar içerebilir. Bu sitelerin içeriği veya gizlilik uygulamalarından sorumlu değiliz.</p>

<h2>7. Sorumluluk Sınırı</h2>
<p><strong>[ŞİRKET ADI]</strong>, site kullanımından kaynaklanan doğrudan veya dolaylı zararlardan sorumlu tutulamaz. Site "olduğu gibi" sunulmaktadır ve kesintisiz veya hatasız çalışacağı garanti edilmez.</p>

<h2>8. Hesap Sonlandırma</h2>
<p>Bu şartların ihlali durumunda, kullanıcı hesaplarını önceden bildirimde bulunmaksızın askıya alma veya sonlandırma hakkımız saklıdır.</p>

<h2>9. Değişiklikler</h2>
<p>Bu Kullanım Şartlarını herhangi bir zamanda değiştirme hakkımız saklıdır. Değişiklikler sitede yayınlandığı anda yürürlüğe girer.</p>

<h2>10. Uygulanacak Hukuk</h2>
<p>Bu şartlar Türkiye Cumhuriyeti yasalarına tabidir. Uyuşmazlıklarda [ŞEHİR] Mahkemeleri ve İcra Daireleri yetkilidir.</p>

<h2>11. İletişim</h2>
<p>Kullanım şartları hakkında sorularınız için:</p>
<ul>
<li><strong>E-posta:</strong> [E-POSTA]</li>
<li><strong>Adres:</strong> [ADRES]</li>
</ul>

<p><em>Son güncelleme: [TARİH]</em></p>', 
'terms', 'draft', 1, 
'Kullanım Şartları | [ŞİRKET ADI]', 
'[ŞİRKET ADI] web sitesi kullanım şartları ve koşulları.');

-- 4. Çerez Politikası
INSERT INTO `agreements` (`title`, `slug`, `content`, `type`, `status`, `version`, `meta_title`, `meta_description`) VALUES
('Çerez Politikası', 'cerez-politikasi', 
'<h2>1. Çerez Nedir?</h2>
<p>Çerezler, web sitelerinin tarayıcınıza yerleştirdiği küçük metin dosyalarıdır. Bu dosyalar, siteyi tekrar ziyaret ettiğinizde sizi tanımamıza ve tercihlerinizi hatırlamamıza yardımcı olur.</p>

<h2>2. Kullandığımız Çerez Türleri</h2>

<h3>2.1. Zorunlu Çerezler</h3>
<p>Bu çerezler web sitesinin düzgün çalışması için gereklidir ve kapatılamazlar.</p>
<ul>
<li><strong>Oturum çerezleri:</strong> Giriş bilgilerinizi ve sepet içeriğinizi saklar</li>
<li><strong>Güvenlik çerezleri:</strong> Güvenlik önlemleri için kullanılır</li>
</ul>

<h3>2.2. Performans Çerezleri</h3>
<p>Bu çerezler, ziyaretçilerin siteyi nasıl kullandığını anlamamıza yardımcı olur.</p>
<ul>
<li><strong>Analitik çerezler:</strong> Sayfa görüntüleme, tıklama oranları gibi verileri toplar</li>
<li><strong>Google Analytics:</strong> Anonim kullanım istatistikleri</li>
</ul>

<h3>2.3. İşlevsellik Çerezleri</h3>
<p>Bu çerezler, tercihlerinizi hatırlamamızı sağlar.</p>
<ul>
<li><strong>Dil tercihi:</strong> Seçtiğiniz dili hatırlar</li>
<li><strong>Tema tercihi:</strong> Açık/koyu mod tercihini saklar</li>
</ul>

<h3>2.4. Pazarlama Çerezleri</h3>
<p>Bu çerezler, size ilgi alanlarınıza göre reklamlar göstermek için kullanılır.</p>
<ul>
<li><strong>Reklam çerezleri:</strong> İlgi alanlarınıza göre reklamlar</li>
<li><strong>Sosyal medya çerezleri:</strong> Paylaşım butonları için</li>
</ul>

<h2>3. Çerez Tablosu</h2>
<table style="width:100%; border-collapse: collapse; margin: 20px 0;">
<thead>
<tr style="background-color: #f3f4f6;">
<th style="border: 1px solid #e5e7eb; padding: 12px; text-align: left;">Çerez Adı</th>
<th style="border: 1px solid #e5e7eb; padding: 12px; text-align: left;">Sağlayıcı</th>
<th style="border: 1px solid #e5e7eb; padding: 12px; text-align: left;">Amaç</th>
<th style="border: 1px solid #e5e7eb; padding: 12px; text-align: left;">Süre</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border: 1px solid #e5e7eb; padding: 12px;">PHPSESSID</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">[WEB SİTESİ]</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">Oturum yönetimi</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">Oturum</td>
</tr>
<tr>
<td style="border: 1px solid #e5e7eb; padding: 12px;">_ga</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">Google</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">Analitik</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">2 yıl</td>
</tr>
<tr>
<td style="border: 1px solid #e5e7eb; padding: 12px;">_gid</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">Google</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">Analitik</td>
<td style="border: 1px solid #e5e7eb; padding: 12px;">24 saat</td>
</tr>
</tbody>
</table>

<h2>4. Çerezleri Nasıl Kontrol Edebilirsiniz?</h2>
<p>Çerezleri aşağıdaki yöntemlerle yönetebilirsiniz:</p>

<h3>Tarayıcı Ayarları</h3>
<p>Çoğu tarayıcı, çerezleri yönetmenize olanak tanır:</p>
<ul>
<li><strong>Chrome:</strong> Ayarlar &gt; Gizlilik ve güvenlik &gt; Çerezler</li>
<li><strong>Firefox:</strong> Ayarlar &gt; Gizlilik ve Güvenlik &gt; Çerezler</li>
<li><strong>Safari:</strong> Tercihler &gt; Gizlilik &gt; Çerezler</li>
<li><strong>Edge:</strong> Ayarlar &gt; Çerezler ve site izinleri</li>
</ul>

<h3>Çerez Tercih Merkezi</h3>
<p>Sitemizde bulunan "Çerez Ayarları" butonuna tıklayarak tercihlerinizi istediğiniz zaman güncelleyebilirsiniz.</p>

<h2>5. Çerezleri Devre Dışı Bırakmanın Etkileri</h2>
<p>Zorunlu çerezleri devre dışı bırakırsanız, sitenin bazı özellikleri düzgün çalışmayabilir. Örneğin:</p>
<ul>
<li>Oturumunuz sürekli kapanabilir</li>
<li>Sepet içeriğiniz kaybolabilir</li>
<li>Tercihleriniz hatırlanmayabilir</li>
</ul>

<h2>6. İletişim</h2>
<p>Çerez politikamız hakkında sorularınız için:</p>
<ul>
<li><strong>E-posta:</strong> [E-POSTA]</li>
</ul>

<h2>7. Güncellemeler</h2>
<p>Bu politika zaman zaman güncellenebilir. Önemli değişiklikler sitede duyurulacaktır.</p>

<p><em>Son güncelleme: [TARİH]</em></p>', 
'cookies', 'draft', 1, 
'Çerez Politikası | [ŞİRKET ADI]', 
'[ŞİRKET ADI] çerez politikası. Hangi çerezleri kullandığımızı ve nasıl kontrol edebileceğinizi öğrenin.');

SET FOREIGN_KEY_CHECKS = 1;

