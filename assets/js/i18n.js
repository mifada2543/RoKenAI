/**
 * RoKenAI Internationalization System
 * Mendukung 2 bahasa: Indonesia (id) dan Inggris (en)
 */

const LANGUAGES = [
    { code: 'en', name: 'English',          native: 'English',          dir: 'ltr' },
    { code: 'id', name: 'Indonesian',        native: 'Bahasa Indonesia',  dir: 'ltr' }
];

// ===== Translations =====
const TRANSLATIONS = {
    // Navigation / Header
    'nav.home':        { en: 'Home',              id: 'Beranda' },
    'nav.upload':      { en: 'Report Damage',     id: 'Lapor Kerusakan' },
    'nav.chat':        { en: 'Ask AI',            id: 'Tanya AI' },
    'nav.examples':    { en: 'Guide',             id: 'Panduan' },
    'nav.news':        { en: 'News',              id: 'Berita' },
    'nav.history':     { en: 'History',           id: 'Riwayat' },
    'nav.signIn':      { en: 'Sign In',           id: 'Masuk' },

    // Settings
    'settings.title':        { en: 'Settings',                                         id: 'Pengaturan' },
    'settings.language':     { en: 'Language',                                         id: 'Bahasa' },
    'settings.languageDesc': { en: 'Choose your preferred language for the interface.', id: 'Pilih bahasa yang diinginkan untuk antarmuka.' },
    'settings.close':        { en: 'Close',                                            id: 'Tutup' },

    // Dashboard / Index
    'dashboard.badge':          { en: 'AI Road Damage Detection v2.0',       id: 'AI Deteksi Jalan Rusak v2.0' },
    'dashboard.h1a':            { en: 'See a Damaged Road?',                 id: 'Lihat Jalan Rusak?' },
    'dashboard.h1b':            { en: 'Report it in Seconds',                id: 'Laporkan dalam Hitungan Detik' },
    'dashboard.desc':           { en: 'RoKenAI uses Computer Vision (YOLOv8) to automatically detect and classify road damage. Just take a photo, our AI does the rest.', id: 'RoKenAI menggunakan teknologi Computer Vision (YOLOv8) untuk mendeteksi dan mengklasifikasikan kerusakan jalan secara otomatis. Cukup foto, AI kami yang verifikasi.' },
    'dashboard.cta':            { en: 'Report Now',                          id: 'Lapor Sekarang' },
    'dashboard.howCta':         { en: 'How it Works',                        id: 'Cara Kerja' },
    'dashboard.stat1':          { en: 'Reports Addressed',                   id: 'Laporan Ditindaklanjuti' },
    'dashboard.stat2':          { en: 'AI Detection Accuracy',               id: 'Akurasi Deteksi AI' },
    'dashboard.stat3':          { en: 'Inference Speed',                     id: 'Kecepatan Inferensi' },
    'dashboard.howTitle':       { en: 'How Does it Work?',                   id: 'Bagaimana Cara Kerjanya?' },
    'dashboard.howDesc':        { en: 'Three simple steps to report road damage',  id: 'Tiga langkah mudah untuk melaporkan kerusakan jalan' },
    'dashboard.step1':          { en: 'Step 1',                              id: 'Langkah 1' },
    'dashboard.step1t':         { en: 'Photograph the Road',                 id: 'Foto Jalan Rusak' },
    'dashboard.step1d':         { en: 'Take a photo of the damaged road with your phone. Make sure the image is clear and well-lit.', id: 'Ambil foto jalan yang rusak menggunakan kamera HP. Pastikan foto jelas dan terkena cahaya.' },
    'dashboard.step2':          { en: 'Step 2',                              id: 'Langkah 2' },
    'dashboard.step2t':         { en: 'Auto AI Detection',                   id: 'Deteksi Otomatis oleh AI' },
    'dashboard.step2d':         { en: 'Our YOLOv8 model detects the damage type and severity automatically.', id: 'Model YOLOv8 kami akan mendeteksi jenis kerusakan dan tingkat keparahannya secara otomatis.' },
    'dashboard.step3':          { en: 'Step 3',                              id: 'Langkah 3' },
    'dashboard.step3t':         { en: 'Followed Up',                         id: 'Ditindaklanjuti' },
    'dashboard.step3d':         { en: 'Reports go to the admin dashboard for verification and follow-up. Track repair status in real-time.', id: 'Laporan masuk ke dashboard admin untuk diverifikasi dan ditindaklanjuti. Pantau status perbaikan secara real-time.' },
    'dashboard.statsTitle':     { en: 'Platform Statistics',                 id: 'Statistik Platform' },
    'dashboard.totalReports':   { en: 'Total Reports',                       id: 'Total Laporan' },
    'dashboard.repaired':       { en: 'Repaired',                            id: 'Selesai Diperbaiki' },
    'dashboard.avgResp':        { en: 'Avg. Response (days)',                id: 'Rata-rata Respons (hari)' },
    'dashboard.featTitle':      { en: 'RoKenAI Features',                   id: 'Fitur RoKenAI' },
    'dashboard.feat1t':         { en: 'Ask AI',                              id: 'Tanya AI' },
    'dashboard.feat1d':         { en: 'Consult the AI assistant about damage types, repair priorities, and recommendations.', id: 'Konsultasi dengan asisten AI tentang jenis kerusakan, prioritas perbaikan, dan rekomendasi.' },
    'dashboard.feat1cta':       { en: 'Start Chat',                          id: 'Mulai Chat' },
    'dashboard.feat2t':         { en: 'Track Repair Status',                 id: 'Lacak Status Perbaikan' },
    'dashboard.feat2d':         { en: 'Monitor your report\'s progress via "Garis Jalan" — from Reported to Repaired.', id: 'Pantau perkembangan laporan Anda melalui "Garis Jalan" — dari Dilaporkan hingga Selesai.' },
    'dashboard.feat2cta':       { en: 'View History',                        id: 'Lihat Riwayat' },
    'dashboard.feat3t':         { en: 'Real-time Notifications',             id: 'Notifikasi Real-time' },
    'dashboard.feat3d':         { en: 'Get notified every time your report status changes.', id: 'Dapatkan pemberitahuan setiap kali status laporan berubah.' },
    'dashboard.recentTitle':    { en: 'Recent Reports',                      id: 'Laporan Terbaru' },
    'dashboard.viewAll':        { en: 'View all →',                          id: 'Lihat semua →' },

    // Chat
    'chat.newChat':        { en: 'New Conversation',   id: 'Percakapan Baru' },
    'chat.today':          { en: 'Today',              id: 'Hari Ini' },
    'chat.yesterday':      { en: 'Yesterday',          id: 'Kemarin' },
    'chat.modelName':      { en: 'RoKenAI — Road Detection Assistant', id: 'RoKenAI — Asisten Deteksi Jalan' },
    'chat.welcomeTitle':   { en: 'Welcome to RoKenAI', id: 'Selamat datang di RoKenAI' },
    'chat.welcomeDesc':    { en: 'AI assistant for road damage detection and analysis. Ask anything about reporting, damage types, or how to use the platform.', id: 'Asisten AI untuk deteksi dan analisis kerusakan jalan. Tanya apa pun tentang pelaporan, jenis kerusakan, atau cara menggunakan platform.' },
    'chat.chip1':          { en: '📷 How to report',   id: '📷 Cara melaporkan' },
    'chat.chip2':          { en: '📋 My report status', id: '📋 Status laporan saya' },
    'chat.chip3':          { en: '🔍 Damage types',    id: '🔍 Jenis kerusakan' },
    'chat.chip4':          { en: '⏱ Repair timeline',  id: '⏱ Waktu perbaikan' },
    'chat.placeholder':    { en: 'Ask RoKenAI...',     id: 'Tanya RoKenAI...' },
    'chat.hint':           { en: 'AI can make mistakes. Verify important information.', id: 'AI dapat melakukan kesalahan. Periksa informasi penting.' },

    // Upload
    'upload.title':        { en: 'Report Road Damage',     id: 'Lapor Kerusakan Jalan' },
    'upload.desc':         { en: 'Report road damage you find. Our AI will automatically detect the type and severity.', id: 'Laporkan kerusakan jalan yang Anda temukan. AI kami akan mendeteksi jenis dan tingkat keparahan secara otomatis.' },
    'upload.step1':        { en: 'Upload Damage Photo',    id: 'Upload Foto Jalan Rusak' },
    'upload.dropTitle':    { en: 'Drag damage photo here', id: 'Tarik foto jalan rusak di sini' },
    'upload.dropSub':      { en: 'Format: JPG, PNG • Max 10MB', id: 'Format: JPG, PNG • Maks 10MB' },
    'upload.browse':       { en: 'Choose Photo',           id: 'Pilih Foto' },
    'upload.step2':        { en: 'Confirm Location',       id: 'Konfirmasi Lokasi' },
    'upload.location':     { en: 'Location',               id: 'Lokasi' },
    'upload.locAuto':      { en: '(auto from GPS)',        id: '(otomatis dari GPS)' },
    'upload.address':      { en: 'Address',                id: 'Alamat' },
    'upload.addrOpt':      { en: '(optional, editable)',   id: '(opsional, bisa diedit)' },
    'upload.step3':        { en: 'Additional Notes',       id: 'Catatan Tambahan' },
    'upload.descLabel':    { en: 'Description',            id: 'Deskripsi' },
    'upload.descOpt':      { en: '(optional)',             id: '(opsional)' },
    'upload.back':         { en: 'Back',                   id: 'Kembali' },
    'upload.submit':       { en: 'Send Report',            id: 'Kirim Laporan' },
    'upload.delete':       { en: 'Delete',                 id: 'Hapus' },
    'upload.redetect':     { en: 'Re-detect',              id: 'Deteksi Ulang' },

    // Examples
    'examples.title':     { en: 'Usage Examples',          id: 'Contoh Penggunaan' },
    'examples.desc':      { en: 'Explore ready-to-use questions and use cases for road damage analysis.', id: 'Jelajahi pertanyaan siap pakai dan contoh kasus untuk analisis kerusakan jalan.' },
    'examples.search':    { en: 'Search examples...',      id: 'Cari contoh...' },
    'examples.all':       { en: 'All',                     id: 'Semua' },
    'examples.detection': { en: 'Detection',               id: 'Deteksi' },
    'examples.analysis':  { en: 'Analysis',                id: 'Analisis' },
    'examples.report':    { en: 'Report',                  id: 'Laporan' },
    'examples.training':  { en: 'Training',                id: 'Pelatihan' },
    'examples.try':       { en: 'Try',                     id: 'Coba' },

    // News
    'news.title':         { en: 'News & Updates',           id: 'Berita & Pembaruan' },
    'news.desc':          { en: 'Stay up to date with the latest RoKenAI features, research breakthroughs, and community events.', id: 'Ikuti perkembangan fitur RoKenAI terbaru, terobosan penelitian, dan acara komunitas.' },
    'news.featured':      { en: 'Featured',                 id: 'Unggulan' },
    'news.readMore':      { en: 'Read more',                id: 'Baca selengkapnya' },
    'news.stayUpdated':   { en: 'Stay Updated',             id: 'Tetap Terupdate' },
    'news.nlDesc':        { en: 'Get the latest RoKenAI news, tutorials, and updates delivered to your inbox.', id: 'Dapatkan berita, tutorial, dan pembaruan RoKenAI terbaru di kotak masuk Anda.' },
    'news.nlPlaceholder': { en: 'Enter your email address', id: 'Masukkan alamat email' },
    'news.subscribe':     { en: 'Subscribe',                id: 'Langganan' },

    // Footer
    'footer.services':    { en: 'Services',                 id: 'Layanan' },
    'footer.info':        { en: 'Information',              id: 'Informasi' },
    'footer.contact':     { en: 'Contact',                  id: 'Kontak' },
    'footer.about':       { en: 'About RoKenAI',            id: 'Tentang RoKenAI' },
    'footer.help':        { en: 'Help Center',              id: 'Pusat Bantuan' },
    'footer.privacy':     { en: 'Privacy Policy',           id: 'Kebijakan Privasi' },
    'footer.terms':       { en: 'Terms & Conditions',       id: 'Syarat & Ketentuan' },
    'footer.desc':        { en: 'AI platform for road damage reporting and detection based on Computer Vision. Report road damage in seconds.', id: 'Platform AI untuk pelaporan dan deteksi kerusakan jalan berbasis Computer Vision. Laporkan jalan rusak dalam hitungan detik.' },

    // Auth
    'auth.signIn': { en: 'Sign In', id: 'Masuk' },

    // General
    'general.loading': { en: 'Loading...', id: 'Memuat...' }
};

// ===== i18n Engine =====
const i18n = {
    currentLang: 'id',
    available: LANGUAGES,

    init() {
        const saved = localStorage.getItem('roken-lang');
        if (saved && LANGUAGES.find(l => l.code === saved)) {
            this.currentLang = saved;
        }
        this.apply();
    },

    getLang() { return this.currentLang; },

    getLangInfo() {
        return LANGUAGES.find(l => l.code === this.currentLang) || LANGUAGES[0];
    },

    t(key) {
        const entry = TRANSLATIONS[key];
        if (!entry) return key;
        return entry[this.currentLang] || entry['id'] || entry['en'] || key;
    },

    setLang(code) {
        if (!LANGUAGES.find(l => l.code === code)) return;
        this.currentLang = code;
        localStorage.setItem('roken-lang', code);
        this.apply();
        document.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang: code } }));
    },

    apply() {
        // Apply translations to all data-i18n elements
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            const translated = this.t(key);
            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                el.setAttribute('placeholder', translated);
            } else {
                el.textContent = translated;
            }
        });

        // Apply placeholder-only
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            el.setAttribute('placeholder', this.t(el.getAttribute('data-i18n-placeholder')));
        });

        // Dispatch event
        document.dispatchEvent(new Event('i18nApplied'));
    }
};

// Auto-init
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => i18n.init());
} else {
    i18n.init();
}
