<?php
// Simple Gemini proxy: accepts POST JSON {"message":"..."}
// Requires environment variables:
// - GEMINI_API_KEY (your API key)
// - GEMINI_API_URL (full URL to send requests to, e.g. https://api.example.com/v1/generate)

header('Content-Type: application/json; charset=utf-8');

$raw = file_get_contents('php://input');
if (!$raw) {
    http_response_code(400);
    echo json_encode(['error' => 'no input']);
    exit;
}

$data = json_decode($raw, true);
if (!isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'missing message']);
    exit;
}

$apiKey = getenv('GEMINI_API_KEY');
$apiUrl = getenv('GEMINI_API_URL');
if (!$apiKey || !$apiUrl) {
    http_response_code(500);
    echo json_encode(['error' => 'server not configured: set GEMINI_API_KEY and GEMINI_API_URL']);
    exit;
}

$payload = isset($data['payload']) ? $data['payload'] : [ 'prompt' => $data['message'] ];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey,
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$resp = curl_exec($ch);
$err = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($resp === false) {
    http_response_code(502);
    echo json_encode(['error' => 'upstream error', 'details' => $err]);
    exit;
}

// Forward response status and body
http_response_code($code ? $code : 200);
echo $resp;

?>
