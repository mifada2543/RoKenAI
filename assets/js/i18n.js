/**
 * RoKenAI Internationalization System
 * Supports all world languages with a key-based translation engine
 */

const LANGUAGES = [
    { code: 'en', name: 'English', native: 'English', dir: 'ltr' },
    { code: 'id', name: 'Indonesian', native: 'Bahasa Indonesia', dir: 'ltr' },
    { code: 'ms', name: 'Malay', native: 'Bahasa Melayu', dir: 'ltr' },
    { code: 'ca', name: 'Catalan', native: 'Català', dir: 'ltr' },
    { code: 'cs', name: 'Czech', native: 'Čeština', dir: 'ltr' },
    { code: 'da', name: 'Danish', native: 'Dansk', dir: 'ltr' },
    { code: 'de', name: 'German', native: 'Deutsch', dir: 'ltr' },
    { code: 'et', name: 'Estonian', native: 'Eesti', dir: 'ltr' },
    { code: 'es', name: 'Spanish', native: 'Español', dir: 'ltr' },
    { code: 'fr', name: 'French', native: 'Français', dir: 'ltr' },
    { code: 'ga', name: 'Irish', native: 'Gaeilge', dir: 'ltr' },
    { code: 'gl', name: 'Galician', native: 'Galego', dir: 'ltr' },
    { code: 'hr', name: 'Croatian', native: 'Hrvatski', dir: 'ltr' },
    { code: 'it', name: 'Italian', native: 'Italiano', dir: 'ltr' },
    { code: 'lv', name: 'Latvian', native: 'Latviešu', dir: 'ltr' },
    { code: 'lt', name: 'Lithuanian', native: 'Lietuvių', dir: 'ltr' },
    { code: 'hu', name: 'Hungarian', native: 'Magyar', dir: 'ltr' },
    { code: 'mt', name: 'Maltese', native: 'Malti', dir: 'ltr' },
    { code: 'nl', name: 'Dutch', native: 'Nederlands', dir: 'ltr' },
    { code: 'no', name: 'Norwegian', native: 'Norsk', dir: 'ltr' },
    { code: 'pl', name: 'Polish', native: 'Polski', dir: 'ltr' },
    { code: 'pt', name: 'Portuguese', native: 'Português', dir: 'ltr' },
    { code: 'ro', name: 'Romanian', native: 'Română', dir: 'ltr' },
    { code: 'sk', name: 'Slovak', native: 'Slovenčina', dir: 'ltr' },
    { code: 'sl', name: 'Slovenian', native: 'Slovenščina', dir: 'ltr' },
    { code: 'fi', name: 'Finnish', native: 'Suomi', dir: 'ltr' },
    { code: 'sv', name: 'Swedish', native: 'Svenska', dir: 'ltr' },
    { code: 'vi', name: 'Vietnamese', native: 'Tiếng Việt', dir: 'ltr' },
    { code: 'tr', name: 'Turkish', native: 'Türkçe', dir: 'ltr' },
    { code: 'is', name: 'Icelandic', native: 'Íslenska', dir: 'ltr' },
    { code: 'el', name: 'Greek', native: 'Ελληνικά', dir: 'ltr' },
    { code: 'bg', name: 'Bulgarian', native: 'Български', dir: 'ltr' },
    { code: 'mk', name: 'Macedonian', native: 'Македонски', dir: 'ltr' },
    { code: 'ru', name: 'Russian', native: 'Русский', dir: 'ltr' },
    { code: 'sr', name: 'Serbian', native: 'Српски / Srpski', dir: 'ltr' },
    { code: 'uk', name: 'Ukrainian', native: 'Українська', dir: 'ltr' },
    { code: 'be', name: 'Belarusian', native: 'Беларуская', dir: 'ltr' },
    { code: 'hy', name: 'Armenian', native: 'Հայերեն', dir: 'ltr' },
    { code: 'ka', name: 'Georgian', native: 'ქართული', dir: 'ltr' },
    { code: 'he', name: 'Hebrew', native: 'עברית', dir: 'rtl' },
    { code: 'yi', name: 'Yiddish', native: 'ייִדיש', dir: 'rtl' },
    { code: 'ur', name: 'Urdu', native: 'اردو', dir: 'rtl' },
    { code: 'ar', name: 'Arabic', native: 'العربية', dir: 'rtl' },
    { code: 'fa', name: 'Persian', native: 'فارسی', dir: 'rtl' },
    { code: 'ps', name: 'Pashto', native: 'پښتو', dir: 'rtl' },
    { code: 'ku', name: 'Kurdish', native: 'Kurdî / كوردی', dir: 'rtl' },
    { code: 'sd', name: 'Sindhi', native: 'سنڌي', dir: 'rtl' },
    { code: 'ug', name: 'Uyghur', native: 'ئۇيغۇرچە', dir: 'rtl' },
    { code: 'am', name: 'Amharic', native: 'አማርኛ', dir: 'ltr' },
    { code: 'ti', name: 'Tigrinya', native: 'ትግርኛ', dir: 'ltr' },
    { code: 'dz', name: 'Dzongkha', native: 'རྫོང་ཁ', dir: 'ltr' },
    { code: 'ne', name: 'Nepali', native: 'नेपाली', dir: 'ltr' },
    { code: 'hi', name: 'Hindi', native: 'हिन्दी', dir: 'ltr' },
    { code: 'mr', name: 'Marathi', native: 'मराठी', dir: 'ltr' },
    { code: 'gu', name: 'Gujarati', native: 'ગુજરાતી', dir: 'ltr' },
    { code: 'bn', name: 'Bengali', native: 'বাংলা', dir: 'ltr' },
    { code: 'or', name: 'Odia', native: 'ଓଡ଼ିଆ', dir: 'ltr' },
    { code: 'pa', name: 'Punjabi', native: 'ਪੰਜਾਬੀ', dir: 'ltr' },
    { code: 'si', name: 'Sinhala', native: 'සිංහල', dir: 'ltr' },
    { code: 'ta', name: 'Tamil', native: 'தமிழ்', dir: 'ltr' },
    { code: 'te', name: 'Telugu', native: 'తెలుగు', dir: 'ltr' },
    { code: 'kn', name: 'Kannada', native: 'ಕನ್ನಡ', dir: 'ltr' },
    { code: 'ml', name: 'Malayalam', native: 'മലയാളം', dir: 'ltr' },
    { code: 'th', name: 'Thai', native: 'ไทย', dir: 'ltr' },
    { code: 'lo', name: 'Lao', native: 'ລາວ', dir: 'ltr' },
    { code: 'my', name: 'Burmese', native: 'မြန်မာဘာသာ', dir: 'ltr' },
    { code: 'km', name: 'Khmer', native: 'ភាសាខ្មែរ', dir: 'ltr' },
    { code: 'zh', name: 'Chinese (Simplified)', native: '简体中文', dir: 'ltr' },
    { code: 'zh-tw', name: 'Chinese (Traditional)', native: '繁體中文', dir: 'ltr' },
    { code: 'ja', name: 'Japanese', native: '日本語', dir: 'ltr' },
    { code: 'ko', name: 'Korean', native: '한국어', dir: 'ltr' },
    { code: 'mn', name: 'Mongolian', native: 'Монгол', dir: 'ltr' },
    { code: 'bo', name: 'Tibetan', native: 'བོད་སྐད', dir: 'ltr' },
    { code: 'kk', name: 'Kazakh', native: 'Қазақша', dir: 'ltr' },
    { code: 'ky', name: 'Kyrgyz', native: 'Кыргызча', dir: 'ltr' },
    { code: 'uz', name: 'Uzbek', native: 'O\'zbek', dir: 'ltr' },
    { code: 'tk', name: 'Turkmen', native: 'Türkmen', dir: 'ltr' },
    { code: 'az', name: 'Azerbaijani', native: 'Azərbaycan dili', dir: 'ltr' },
    { code: 'sq', name: 'Albanian', native: 'Shqip', dir: 'ltr' },
    { code: 'bs', name: 'Bosnian', native: 'Bosanski', dir: 'ltr' },
    { code: 'tl', name: 'Filipino', native: 'Filipino', dir: 'ltr' },
    { code: 'sw', name: 'Swahili', native: 'Kiswahili', dir: 'ltr' },
    { code: 'rw', name: 'Kinyarwanda', native: 'Ikinyarwanda', dir: 'ltr' },
    { code: 'rn', name: 'Kirundi', native: 'Ikirundi', dir: 'ltr' },
    { code: 'zu', name: 'Zulu', native: 'isiZulu', dir: 'ltr' },
    { code: 'xh', name: 'Xhosa', native: 'isiXhosa', dir: 'ltr' },
    { code: 'af', name: 'Afrikaans', native: 'Afrikaans', dir: 'ltr' },
    { code: 'st', name: 'Sesotho', native: 'Sesotho', dir: 'ltr' },
    { code: 'tn', name: 'Tswana', native: 'Setswana', dir: 'ltr' },
    { code: 'sn', name: 'Shona', native: 'chiShona', dir: 'ltr' },
    { code: 'ny', name: 'Chichewa', native: 'Chichewa', dir: 'ltr' },
    { code: 'mg', name: 'Malagasy', native: 'Malagasy', dir: 'ltr' },
    { code: 'eo', name: 'Esperanto', native: 'Esperanto', dir: 'ltr' },
    { code: 'ht', name: 'Haitian Creole', native: 'Kreyòl Ayisyen', dir: 'ltr' },
    { code: 'la', name: 'Latin', native: 'Latina', dir: 'ltr' },
    { code: 'fy', name: 'Frisian', native: 'Frysk', dir: 'ltr' },
    { code: 'lb', name: 'Luxembourgish', native: 'Lëtzebuergesch', dir: 'ltr' },
    { code: 'cy', name: 'Welsh', native: 'Cymraeg', dir: 'ltr' },
    { code: 'gd', name: 'Scottish Gaelic', native: 'Gàidhlig', dir: 'ltr' },
    { code: 'jw', name: 'Javanese', native: 'Basa Jawa', dir: 'ltr' },
    { code: 'su', name: 'Sundanese', native: 'Basa Sunda', dir: 'ltr' },
    { code: 'ha', name: 'Hausa', native: 'Hausa', dir: 'ltr' },
    { code: 'yo', name: 'Yoruba', native: 'Yorùbá', dir: 'ltr' },
    { code: 'ig', name: 'Igbo', native: 'Igbo', dir: 'ltr' },
    { code: 'so', name: 'Somali', native: 'Soomaali', dir: 'ltr' },
    { code: 'om', name: 'Oromo', native: 'Afaan Oromoo', dir: 'ltr' },
    { code: 'sm', name: 'Samoan', native: 'Gagana Samoa', dir: 'ltr' },
    { code: 'to', name: 'Tongan', native: 'Lea Faka-Tonga', dir: 'ltr' },
    { code: 'mi', name: 'Māori', native: 'Te Reo Māori', dir: 'ltr' },
    { code: 'haw', name: 'Hawaiian', native: 'ʻŌlelo Hawaiʻi', dir: 'ltr' }
];

// ===== Translations =====
const TRANSLATIONS = {
    // Navigation / Header
    'nav.chat': { en: 'Chat', id: 'Obrolan' },
    'nav.examples': { en: 'Examples', id: 'Contoh' },
    'nav.news': { en: 'News', id: 'Berita' },
    'nav.tools': { en: 'Tools', id: 'Alat' },
    'nav.upload': { en: 'Upload', id: 'Unggah' },
    'nav.dashboard': { en: 'Dashboard', id: 'Dasbor' },
    'nav.mainMenu': { en: 'Main Menu', id: 'Menu Utama' },
    'nav.navigation': { en: 'Navigation', id: 'Navigasi' },
    'nav.aiBadge': { en: 'YOLOv8 Model Active', id: 'Model YOLOv8 Aktif' },
    'nav.signIn': { en: 'Sign In', id: 'Masuk' },
    'nav.aiOnline': { en: 'AI Server Online', id: 'Server AI Online' },
    'nav.settings': { en: 'Settings', id: 'Pengaturan' },
    'nav.notifications': { en: 'Notifications', id: 'Notifikasi' },

    // Settings Modal
    'settings.title': { en: 'Settings', id: 'Pengaturan' },
    'settings.language': { en: 'Language', id: 'Bahasa' },
    'settings.languageDesc': { en: 'Choose your preferred language for the interface.', id: 'Pilih bahasa yang diinginkan untuk antarmuka.' },
    'settings.modelAI': { en: 'AI Model', id: 'Model AI' },
    'settings.modelAIDesc': { en: 'Select the AI model for detection and analysis.', id: 'Pilih model AI untuk deteksi dan analisis.' },
    'settings.modelDefault': { en: 'YOLOv8 (Default)', id: 'YOLOv8 (Bawaan)' },
    'settings.modelPlaceholder': { en: 'More models coming soon...', id: 'Model lainnya segera hadir...' },
    'settings.close': { en: 'Close', id: 'Tutup' },
    'settings.searchLanguage': { en: 'Search language...', id: 'Cari bahasa...' },

    // Notifications
    'notif.empty': { en: 'No notifications yet', id: 'Belum ada notifikasi' },
    'notif.clearAll': { en: 'Clear all', id: 'Hapus semua' },
    'notif.title': { en: 'Notifications', id: 'Notifikasi' },
    'notif.aiResponse': { en: 'AI Response Ready', id: 'Respons AI Siap' },
    'notif.aiReplied': { en: 'RoKenAI has replied', id: 'RoKenAI telah merespon' },

    // Dashboard (index.php)
    'dashboard.heroBadge': { en: 'AI Engine v2.0 • Real-time Inference', id: 'Mesin AI v2.0 • Inferensi Real-time' },
    'dashboard.heroTitle': { en: 'The Future of Road Infrastructure Inspection', id: 'Masa Depan Inspeksi Infrastruktur Jalan' },
    'dashboard.heroSubtitle': { en: 'Powered by RoKenAI — reconstructing road infrastructure monitoring through deep learning computer vision with YOLOv8.', id: 'Didukung oleh RoKenAI — merekonstruksi pemantauan infrastruktur jalan melalui visi komputer deep learning dengan YOLOv8.' },
    'dashboard.aiReady': { en: 'AI Engine Ready', id: 'Mesin AI Siap' },
    'dashboard.detectionTitle': { en: 'Road Damage Detection & Analysis', id: 'Deteksi & Analisis Kerusakan Jalan' },
    'dashboard.detectionDesc': { en: 'Upload road documentation photos to process road damage object segmentation, potholes, and cracks in real-time via YOLOv8.', id: 'Unggah foto dokumentasi jalan raya untuk memproses segmentasi objek kerusakan jalan, lubang, dan retakan secara real-time via YOLOv8.' },
    'dashboard.openDetection': { en: 'Open Detection Module', id: 'Buka Modul Deteksi' },
    'dashboard.modelSpecs': { en: 'Model Core Specs', id: 'Spesifikasi Inti Model' },
    'dashboard.live': { en: 'Live', id: 'Langsung' },
    'dashboard.weightsFile': { en: 'Weights File', id: 'File Bobot' },
    'dashboard.framework': { en: 'Framework', id: 'Kerangka' },
    'dashboard.inferenceSpeed': { en: 'Inference Speed', id: 'Kecepatan Inferensi' },
    'dashboard.modelStatus': { en: 'Model Status', id: 'Status Model' },
    'dashboard.online': { en: 'Online', id: 'Daring' },
    'dashboard.accuracy': { en: 'YOLOv8 Accuracy', id: 'Akurasi YOLOv8' },
    'dashboard.linked': { en: 'modules/main.py linked', id: 'modul/main.py terhubung' },
    'dashboard.recentInspections': { en: 'Recent Inspections', id: 'Inspeksi Terbaru' },
    'dashboard.viewAll': { en: 'View all →', id: 'Lihat semua →' },
    'dashboard.totalInspections': { en: 'Total Inspections', id: 'Total Inspeksi' },
    'dashboard.detectionAccuracy': { en: 'Detection Accuracy', id: 'Akurasi Deteksi' },
    'dashboard.avgInference': { en: 'Avg. Inference', id: 'Rata-rata Inferensi' },
    'dashboard.aiChat': { en: 'AI Chat Assistant', id: 'Asisten Obrolan AI' },
    'dashboard.aiChatDesc': { en: 'Consult with RoKenAI about road damage analysis results.', id: 'Konsultasi dengan RoKenAI tentang hasil analisis kerusakan jalan.' },
    'dashboard.reports': { en: 'Generate Reports', id: 'Buat Laporan' },
    'dashboard.reportsDesc': { en: 'Export detailed inspection reports in PDF format.', id: 'Ekspor laporan inspeksi detail dalam format PDF.' },
    'dashboard.training': { en: 'Model Training', id: 'Pelatihan Model' },
    'dashboard.trainingDesc': { en: 'Retrain the model with new road damage datasets.', id: 'Latih ulang model dengan dataset kerusakan jalan baru.' },

    // Chat
    'chat.history': { en: 'Chat History', id: 'Riwayat Obrolan' },
    'chat.searchPlaceholder': { en: 'Search conversations...', id: 'Cari percakapan...' },
    'chat.today': { en: 'Today', id: 'Hari Ini' },
    'chat.yesterday': { en: 'Yesterday', id: 'Kemarin' },
    'chat.lastWeek': { en: 'Last Week', id: 'Minggu Lalu' },
    'chat.welcomeTitle': { en: 'Welcome to RoKenAI', id: 'Selamat datang di RoKenAI' },
    'chat.welcomeDesc': { en: 'Your AI assistant for road infrastructure damage detection and analysis. Ask me anything about road inspection data.', id: 'Asisten AI Anda untuk deteksi dan analisis kerusakan infrastruktur jalan. Tanyakan apa pun tentang data inspeksi jalan.' },
    'chat.inputPlaceholder': { en: 'Ask RoKenAI...', id: 'Tanya RoKenAI...' },

    // Upload
    'upload.title': { en: 'Upload Road Image', id: 'Unggah Gambar Jalan' },
    'upload.desc': { en: 'Upload road documentation photos for damage analysis using YOLOv8 AI model.', id: 'Unggah foto dokumentasi jalan raya untuk analisis kerusakan menggunakan model AI YOLOv8.' },
    'upload.dropTitle': { en: 'Drag & drop your image here', id: 'Seret & letakkan gambar Anda di sini' },
    'upload.dropSubtitle': { en: 'Supported formats: JPG, PNG, WEBP • Max 10MB', id: 'Format didukung: JPG, PNG, WEBP • Maks 10MB' },
    'upload.browse': { en: 'Browse Files', id: 'Cari Berkas' },
    'upload.orClick': { en: 'or click anywhere in this area', id: 'atau klik di area ini' },
    'upload.preview': { en: 'Image Preview', id: 'Pratinjau Gambar' },
    'upload.delete': { en: 'Delete', id: 'Hapus' },
    'upload.analyze': { en: 'Analyze Image', id: 'Analisis Gambar' },

    // Examples
    'examples.title': { en: 'Example Prompts', id: 'Contoh Pertanyaan' },
    'examples.desc': { en: 'Explore ready-to-use prompts and example use cases for road damage analysis with RoKenAI.', id: 'Jelajahi pertanyaan siap pakai dan contoh penggunaan untuk analisis kerusakan jalan dengan RoKenAI.' },
    'examples.search': { en: 'Search examples...', id: 'Cari contoh...' },
    'examples.all': { en: 'All', id: 'Semua' },
    'examples.tryInChat': { en: 'Try in Chat', id: 'Coba di Obrolan' },

    // News
    'news.title': { en: 'News & Updates', id: 'Berita & Pembaruan' },
    'news.desc': { en: 'Stay up to date with the latest RoKenAI features, research breakthroughs, and community events.', id: 'Ikuti perkembangan fitur RoKenAI terbaru, terobosan penelitian, dan acara komunitas.' },
    'news.featured': { en: 'Featured', id: 'Unggulan' },
    'news.readMore': { en: 'Read more', id: 'Baca selengkapnya' },
    'news.stayUpdated': { en: 'Stay Updated', id: 'Tetap Terupdate' },
    'news.newsletterDesc': { en: 'Get the latest RoKenAI news, tutorials, and updates delivered to your inbox.', id: 'Dapatkan berita, tutorial, dan pembaruan RoKenAI terbaru di kotak masuk Anda.' },
    'news.subscribe': { en: 'Subscribe', id: 'Langganan' },

    // Auth
    'auth.backToHome': { en: 'Back to Home', id: 'Kembali ke Beranda' },
    'auth.welcomeBack': { en: 'Welcome Back', id: 'Selamat Datang Kembali' },
    'auth.signInDesc': { en: 'Sign in to access your AI-powered road inspection dashboard.', id: 'Masuk untuk mengakses dasbor inspeksi jalan bertenaga AI Anda.' },
    'auth.usernameOrEmail': { en: 'Username or Email', id: 'Nama Pengguna atau Email' },
    'auth.usernamePlaceholder': { en: 'Enter your username or email', id: 'Masukkan nama pengguna atau email' },
    'auth.password': { en: 'Password', id: 'Kata Sandi' },
    'auth.passwordPlaceholder': { en: 'Enter your password', id: 'Masukkan kata sandi' },
    'auth.rememberMe': { en: 'Remember me', id: 'Ingat saya' },
    'auth.forgotPassword': { en: 'Forgot password?', id: 'Lupa kata sandi?' },
    'auth.signIn': { en: 'Sign In', id: 'Masuk' },
    'auth.noAccount': { en: 'Don\'t have an account?', id: 'Belum punya akun?' },
    'auth.createOne': { en: 'Create one', id: 'Buat satu' },
    'auth.createAccount': { en: 'Create Account', id: 'Buat Akun' },
    'auth.registerDesc': { en: 'Join RoKenAI and start analyzing road infrastructure with AI.', id: 'Bergabunglah dengan RoKenAI dan mulailah menganalisis infrastruktur jalan dengan AI.' },
    'auth.username': { en: 'Username', id: 'Nama Pengguna' },
    'auth.usernameChoice': { en: 'Choose a username', id: 'Pilih nama pengguna' },
    'auth.email': { en: 'Email', id: 'Email' },
    'auth.emailPlaceholder': { en: 'Enter your email', id: 'Masukkan email' },
    'auth.passwordCreate': { en: 'Create a strong password', id: 'Buat kata sandi yang kuat' },
    'auth.confirmPassword': { en: 'Confirm Password', id: 'Konfirmasi Kata Sandi' },
    'auth.confirmPlaceholder': { en: 'Re-enter password', id: 'Masukkan ulang kata sandi' },
    'auth.terms': { en: 'I agree to the', id: 'Saya setuju dengan' },
    'auth.termsOfService': { en: 'Terms of Service', id: 'Ketentuan Layanan' },
    'auth.privacyPolicy': { en: 'Privacy Policy', id: 'Kebijakan Privasi' },
    'auth.haveAccount': { en: 'Already have an account?', id: 'Sudah punya akun?' },
    'auth.signInLink': { en: 'Sign in', id: 'Masuk' },
    'auth.orContinue': { en: 'Or continue with', id: 'Atau lanjutkan dengan' },
    'auth.registerSuccess': { en: 'Account created successfully! Please sign in.', id: 'Akun berhasil dibuat! Silakan masuk.' },
    'auth.passwordStrength': { en: 'Password strength:', id: 'Kekuatan kata sandi:' },
    'auth.strengthWeak': { en: 'Weak', id: 'Lemah' },
    'auth.strengthMedium': { en: 'Medium', id: 'Sedang' },
    'auth.strengthStrong': { en: 'Strong', id: 'Kuat' },
    'auth.agreeTerms': { en: 'I agree to the', id: 'Saya setuju dengan' },
    'auth.and': { en: 'and', id: 'dan' },
    'auth.googleSignIn': { en: 'Google', id: 'Google' },
    'auth.githubSignIn': { en: 'GitHub', id: 'GitHub' },
    'auth.comingSoon': { en: 'coming soon!', id: 'segera hadir!' },

    // Chat page additions
    'chat.modelBadge': { en: 'RoKenAI • YOLOv8', id: 'RoKenAI • YOLOv8' },
    'chat.newChat': { en: 'New Chat', id: 'Obrolan Baru' },
    'chat.roadDamageAnalysis': { en: 'Road damage analysis', id: 'Analisis kerusakan jalan' },
    'chat.yoloConfig': { en: 'YOLOv8 configuration', id: 'Konfigurasi YOLOv8' },
    'chat.datasetPrep': { en: 'Dataset preprocessing', id: 'Prapemrosesan dataset' },
    'chat.modelTraining': { en: 'Model training results', id: 'Hasil pelatihan model' },
    'chat.exportReport': { en: 'Export inspection report', id: 'Ekspor laporan inspeksi' },
    'chat.suggestionAnalyze': { en: '🔍 Analyze road damage', id: '🔍 Analisis kerusakan jalan' },
    'chat.suggestionMetrics': { en: '📊 YOLOv8 metrics', id: '📊 Metrik YOLOv8' },
    'chat.suggestionUpload': { en: '📁 Upload guide', id: '📁 Panduan unggah' },
    'chat.suggestionRecent': { en: '📋 Recent results', id: '📋 Hasil terbaru' },
    'chat.inputPlaceholderAlt': { en: 'Ask RoKenAI...', id: 'Tanya RoKenAI...' },
    'chat.addFile': { en: 'Add file', id: 'Tambah berkas' },
    'chat.clearChat': { en: 'Clear chat', id: 'Hapus obrolan' },
    'chat.moreOptions': { en: 'More options', id: 'Opsi lainnya' },
    'chat.showHistory': { en: 'Show history', id: 'Tampilkan riwayat' },

    // Upload page additions
    'upload.previewTitle': { en: 'Image Preview', id: 'Pratinjau Gambar' },
    'upload.fileSize': { en: 'KB', id: 'KB' },
    'upload.tooLarge': { en: 'File too large. Maximum size is 10MB.', id: 'Berkas terlalu besar. Ukuran maksimal 10MB.' },

    // Examples page additions
    'examples.detection': { en: 'Detection', id: 'Deteksi' },
    'examples.analysis': { en: 'Analysis', id: 'Analisis' },
    'examples.reports': { en: 'Reports', id: 'Laporan' },
    'examples.training': { en: 'Training', id: 'Pelatihan' },

    // News page additions
    'news.newsletterPlaceholder': { en: 'Enter your email address', id: 'Masukkan alamat email' },
    'news.calendar': { en: 'Calendar', id: 'Kalender' },

    // Profile page
    'profile.title': { en: 'Profile', id: 'Profil' },

    // General/misc
    'general.loading': { en: 'Loading...', id: 'Memuat...' },
    'general.error': { en: 'Error', id: 'Kesalahan' },
    'general.translating': { en: 'Translating...', id: 'Menerjemahkan...' }
};

// ===== i18n Engine =====
const i18n = {
    currentLang: 'en',
    available: LANGUAGES,

    async init() {
        const saved = localStorage.getItem('roken-lang');
        if (saved) {
            const found = LANGUAGES.find(l => l.code === saved);
            if (found) this.currentLang = saved;
        }
        this.apply();
        // Auto-translate for non-English languages
        if (this.currentLang !== 'en') {
            await this.autoTranslate();
            this.apply();
        }
    },

    getLang() {
        return this.currentLang;
    },

    getLangInfo() {
        return LANGUAGES.find(l => l.code === this.currentLang) || LANGUAGES[0];
    },

    t(key) {
        const entry = TRANSLATIONS[key];
        if (!entry) return key;
        // Direct match for current language
        if (entry[this.currentLang]) return entry[this.currentLang];
        // Fall back to English
        return entry['en'] || key;
    },

    // Auto-translate missing translations for the current language
    async autoTranslate() {
        const lang = this.currentLang;
        if (lang === 'en') return; // No need to translate English

        // Check localStorage cache
        const cacheKey = 'roken-trans-' + lang;
        let cached = {};
        try {
            const cachedRaw = localStorage.getItem(cacheKey);
            if (cachedRaw) cached = JSON.parse(cachedRaw);
        } catch(e) {}

        // Collect all texts that need translation for this language
        const missingTexts = [];
        const missingKeys = [];
        const newTranslations = {};

        for (const [key, entry] of Object.entries(TRANSLATIONS)) {
            if (entry[lang]) continue; // Already has translation
            if (!entry['en']) continue; // No English source

            const cachedTrans = cached[key];
            if (cachedTrans) {
                // Use cached translation
                TRANSLATIONS[key][lang] = cachedTrans;
                newTranslations[key] = cachedTrans;
                continue;
            }

            missingTexts.push(entry['en']);
            missingKeys.push(key);
        }

        if (missingKeys.length === 0) {
            // All were cached
            return;
        }

        // Try to translate via PHP proxy (batch mode with separator)
        try {
            const separator = '|||SEP|||';
            const joined = missingTexts.join(separator);
            const resp = await fetch('translate_proxy.php?tl=' + encodeURIComponent(lang) + '&text=' + encodeURIComponent(joined));
            const data = await resp.json();

            if (data && data.translated) {
                const parts = data.translated.split('|||');
                // Google Translate sometimes uses different separators
                if (parts.length < 2 && data.translated.includes(separator)) {
                    // Use actual separator
                }
                let translations;
                if (data.separator) {
                    translations = data.translated.split(data.separator);
                } else if (data.translated.includes('|||SEP|||')) {
                    translations = data.translated.split('|||SEP|||');
                } else if (data.translated.includes('|||')) {
                    translations = data.translated.split('|||');
                } else {
                    translations = [data.translated];
                }

                for (let i = 0; i < missingKeys.length && i < translations.length; i++) {
                    const t = (translations[i] || '').trim();
                    if (t && t !== missingTexts[i]) {
                        TRANSLATIONS[missingKeys[i]][lang] = t;
                        newTranslations[missingKeys[i]] = t;
                    }
                }

                // Cache in localStorage
                try {
                    const existingCache = JSON.parse(localStorage.getItem(cacheKey) || '{}');
                    Object.assign(existingCache, newTranslations);
                    localStorage.setItem(cacheKey, JSON.stringify(existingCache));
                } catch(e) {}
            }
        } catch(e) {
            console.warn('Auto-translate failed for ' + lang + ':', e);
        }
    },

    // Override apply to add auto-translate after applying known translations
    async applyWithAuto() {
        this.apply();
        await this.autoTranslate();
        // Re-apply after auto-translation completes
        this.apply();
    },

    setLang(code) {
        const found = LANGUAGES.find(l => l.code === code);
        if (!found) return;
        this.currentLang = code;
        localStorage.setItem('roken-lang', code);
        // Use async apply
        this.applyWithAuto();
        // Dispatch event so other components can react
        document.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang: code } }));
    },

    apply() {
        const dir = this.getLangInfo().dir || 'ltr';
        document.documentElement.setAttribute('dir', dir);
        if (dir === 'rtl') {
            document.documentElement.setAttribute('data-rtl', '');
        } else {
            document.documentElement.removeAttribute('data-rtl');
        }

        // Update all data-i18n elements
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            const translated = this.t(key);
            // Check if element has child elements (like icon + text span)
            const textNode = el.getAttribute('data-i18n-text');
            if (textNode !== null) {
                // Only replace the text node / inner text
                const placeholder = el.querySelector('[data-i18n-placeholder]');
                if (placeholder) {
                    placeholder.textContent = translated;
                } else if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    el.setAttribute('placeholder', translated);
                } else {
                    el.textContent = translated;
                }
            } else {
                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    const attr = el.getAttribute('data-i18n-attr') || 'placeholder';
                    el.setAttribute(attr, translated);
                } else {
                    el.textContent = translated;
                }
            }
        });

        // Update document title if data-i18n-title is set
        const titleEl = document.querySelector('[data-i18n-title]');
        if (titleEl) {
            document.title = this.t(titleEl.getAttribute('data-i18n-title'));
        }

        // Dispatch event for components that need re-rendering
        document.dispatchEvent(new Event('i18nApplied'));
    }
};

// Auto-init when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => i18n.init());
} else {
    i18n.init();
}
