<?php
/**
 * RoKenAI LLM Chat Proxy
 * Meneruskan chat ke LLM lokal KoboldCpp (OpenAI-compatible API)
 * 
 * Endpoint: http://localhost:5001/v1/chat/completions
 * Model: koboldcpp/MiniCPM-V-4_6-Q8_0
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// ===== Konfigurasi =====
$LLM_API_URL = 'http://localhost:5001/v1/chat/completions';
$LLM_MODEL   = 'koboldcpp/MiniCPM-V-4_6-Q8_0';
$MAX_TOKENS  = 1024;
$TEMPERATURE = 0.7;
// ======================

// Baca input JSON dari body request
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['messages'])) {
    echo json_encode(['status' => 'error', 'message' => 'Format request tidak valid. Dibutuhkan: messages array']);
    exit;
}

$messages = $input['messages'];
$maxTokens = $input['max_tokens'] ?? $MAX_TOKENS;
$temperature = $input['temperature'] ?? $TEMPERATURE;

// Validasi messages adalah array
if (!is_array($messages) || empty($messages)) {
    echo json_encode(['status' => 'error', 'message' => 'Messages harus berupa array dan tidak boleh kosong']);
    exit;
}

// Validasi setiap message punya role dan content
foreach ($messages as $msg) {
    if (!isset($msg['role']) || !isset($msg['content'])) {
        echo json_encode(['status' => 'error', 'message' => 'Setiap message harus memiliki role dan content']);
        exit;
    }
}

// Batasi jumlah pesan maksimal untuk mencegah resource exhaustion
if (count($messages) > 50) {
    http_response_code(413);
    echo json_encode(['status' => 'error', 'message' => 'Terlalu banyak pesan. Maksimal 50 pesan per request.']);
    exit;
}

// Tambahkan system prompt default jika belum ada system message
$hasSystem = false;
foreach ($messages as $msg) {
    if ($msg['role'] === 'system') {
        $hasSystem = true;
        break;
    }
}

if (!$hasSystem) {
    $systemPrompt = [
        'role' => 'system',
        'content' => "Anda adalah asisten AI untuk RoKenAI (https://rokenai.com), platform deteksi kerusakan jalan berbasis Computer Vision YOLOv8. " .
            "Tugas Anda adalah membantu pengguna melaporkan dan menganalisis kerusakan jalan seperti lubang, retak, jalan bergelombang, dan sebagainya. " .
            "Anda juga membantu menjelaskan cara penggunaan platform RoKenAI. " .
            "Jawab dengan bahasa Indonesia yang sopan, informatif, dan ramah. " .
            "Jika ditanya tentang kode atau teknis, berikan bantuan yang akurat. " .
            "Jika tidak tahu jawabannya, akui saja. Jangan mengarang informasi."
    ];
    array_unshift($messages, $systemPrompt);
}

// Siapkan payload untuk dikirim ke LLM
$payload = [
    'model' => $LLM_MODEL,
    'messages' => $messages,
    'max_tokens' => (int)$maxTokens,
    'temperature' => (float)$temperature,
    'stream' => false,
];

// Kirim request ke LLM lokal via cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $LLM_API_URL,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 120, // 2 menit timeout untuk LLM
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(502);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal terhubung ke LLM: ' . $curlError,
    ]);
    exit;
}

if ($httpCode !== 200) {
    http_response_code(502);
    echo json_encode([
        'status' => 'error',
        'message' => 'LLM mengembalikan HTTP ' . $httpCode,
        'detail' => substr($response, 0, 500),
    ]);
    exit;
}

// Parse response JSON
$llmResponse = json_decode($response, true);

if (!$llmResponse || !isset($llmResponse['choices'][0]['message']['content'])) {
    http_response_code(502);
    echo json_encode([
        'status' => 'error',
        'message' => 'Respon LLM tidak valid',
        'raw' => substr($response, 0, 500),
    ]);
    exit;
}

// Ambil konten dari response
$content = $llmResponse['choices'][0]['message']['content'];

// Ambil reasoning_content jika ada (khusus MiniCPM)
$reasoning = null;
if (isset($llmResponse['choices'][0]['message']['reasoning_content'])) {
    $reasoning = $llmResponse['choices'][0]['message']['reasoning_content'];
}

// Kembalikan response ke frontend
echo json_encode([
    'status' => 'success',
    'message' => [
        'role' => 'assistant',
        'content' => trim($content),
        'reasoning' => $reasoning ? trim($reasoning) : null,
    ],
    'usage' => $llmResponse['usage'] ?? null,
    'model' => $llmResponse['model'] ?? $LLM_MODEL,
]);
