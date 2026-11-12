<?php
// Locate the PDF file in current or parent directories
$candidates = [
    __DIR__ . '/Letter of Price BidD.pdf',
    __DIR__ . '/Letter_of_Price_Bid.pdf',
    dirname(__DIR__) . '/Letter of Price BidD.pdf',
    dirname(__DIR__) . '/Letter_of_Price_Bid.pdf',
    dirname(__DIR__, 2) . '/Letter of Price BidD.pdf',
    dirname(__DIR__, 2) . '/Letter_of_Price_Bid.pdf',
];

$file = null;
$pdf_name = 'Letter of Price BidD.pdf';
foreach ($candidates as $candidate) {
    if (file_exists($candidate)) {
        $file = $candidate;
        $pdf_name = basename($candidate);
        break;
    }
}

if ($file && file_exists($file)) {
    // Enforce HTTPS
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
        $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect_url);
        exit;
    }

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
    $root_index = dirname(__DIR__, 2) . '/index.php';
    if (file_exists($root_index)) {
        require $root_index;
        exit;
    }
    http_response_code(404);
    echo "File not found";
    exit;
}
?>