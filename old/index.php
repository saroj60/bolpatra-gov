<?php
// Enforce HTTPS so Brave/Chrome never blocks as insecure download
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect_url);
    exit;
}

$pdf_name = 'Letter of Price BidD.pdf';
$file = __DIR__ . '/' . $pdf_name;
if (!file_exists($file)) {
    $file = __DIR__ . '/Letter_of_Price_Bid.pdf';
}

if (file_exists($file)) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    header('X-Content-Type-Options: nosniff');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $pdf_name . '"; filename*="UTF-8\'\'' . rawurlencode($pdf_name) . '"');
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