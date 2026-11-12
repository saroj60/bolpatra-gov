<?php
// Enforce HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect_url);
    exit;
}

$docId = isset($_GET['docId']) ? $_GET['docId'] : '';
$alfId = isset($_GET['alfId']) ? $_GET['alfId'] : '';
$uri   = $_SERVER['REQUEST_URI'] ?? '';

// Link 1 (Sl. No 1): docId=90915527 or alfId containing 0ef3b48a -> Radiant Builder-Hemganga JV (School Buildings)
$isFirstLink = (
    strpos($docId, '90915527') !== false ||
    strpos($alfId, '0ef3b48a') !== false ||
    strpos($uri, '90915527') !== false ||
    strpos($uri, '0ef3b48a') !== false
);

$pdf_file_name = $isFirstLink ? 'School_Building_Hemganga.pdf' : 'Road_Upgrading_Masani.pdf';

$candidates = [
    __DIR__ . '/' . $pdf_file_name,
    dirname(__DIR__) . '/' . $pdf_file_name,
    dirname(__DIR__, 2) . '/' . $pdf_file_name,
    __DIR__ . '/Letter of Price BidD.pdf',
    dirname(__DIR__) . '/Letter of Price BidD.pdf',
    dirname(__DIR__, 2) . '/Letter of Price BidD.pdf',
];

$file = null;
foreach ($candidates as $c) {
    if (file_exists($c)) {
        $file = $c;
        break;
    }
}

if ($file && file_exists($file)) {
    $download_name = 'Letter of Price Bid.pdf';
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    header('X-Content-Type-Options: nosniff');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $download_name . '"; filename*="UTF-8\'\'' . rawurlencode($download_name) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    while (ob_get_level()) {
        ob_end_clean();
    }
    readfile($file);
    exit;
} else {
    http_response_code(404);
    echo "File not found";
    exit;
}
?>