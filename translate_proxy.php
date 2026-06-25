<?php
/**
 * RoKenAI Translation Proxy
 * Proxies translation requests to Google Translate's free API
 * Used by i18n.js to auto-translate missing languages
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$text = $_GET['text'] ?? '';
$target = $_GET['tl'] ?? 'id';

if (empty($text)) {
    echo json_encode(['error' => 'No text provided']);
    exit;
}

// Validate language code (basic sanity)
if (!preg_match('/^[a-z]{2}(-[a-zA-Z]{2,4})?$/', $target)) {
    echo json_encode(['error' => 'Invalid language code']);
    exit;
}

// Build URL with multiple q parameters for batch translation
// Google Translate API supports batch translation via multiple q params
$url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=' . urlencode($target) . '&dt=t';

// Split by separator to send as multiple q params
$separator = '|||SEP|||';
$texts = explode($separator, $text);

// If no separator found, treat as single text
if (count($texts) === 1 && strpos($text, $separator) === false) {
    $url .= '&q=' . urlencode($text);
} else {
    foreach ($texts as $t) {
        $t = trim($t);
        if (!empty($t)) {
            $url .= '&q=' . urlencode($t);
        }
    }
}

// If it's still just the original text with no separator, handle single case
if (count($texts) === 1 && strpos($text, $separator) === false) {
    // Already handled above
}

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    CURLOPT_TIMEOUT => 15,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Accept-Language: en-US,en;q=0.9',
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(502);
    echo json_encode(['error' => 'Translation request failed: ' . $error]);
    exit;
}

if ($httpCode !== 200) {
    http_response_code(502);
    echo json_encode(['error' => 'Translation API returned HTTP ' . $httpCode]);
    exit;
}

$data = json_decode($response, true);

if (!$data || !isset($data[0])) {
    http_response_code(502);
    echo json_encode(['error' => 'Invalid response from translation API']);
    exit;
}

// Parse Google Translate response format:
// [[["translated1","source1",...],["translated2","source2",...]],...]
$translations = [];
foreach ($data[0] as $item) {
    if (isset($item[0])) {
        $translations[] = $item[0];
    }
}

if (count($translations) === 0) {
    http_response_code(502);
    echo json_encode(['error' => 'No translations found in response']);
    exit;
}

// If only one text was requested, return single translation
if (count($texts) <= 1 && count($translations) === 1) {
    echo json_encode([
        'translated' => $translations[0],
        'lang' => $target,
        'count' => 1
    ]);
    exit;
}

// Return batch translations joined by separator for JavaScript to split
echo json_encode([
    'translated' => implode($separator, $translations),
    'separator' => $separator,
    'lang' => $target,
    'count' => count($translations)
]);
