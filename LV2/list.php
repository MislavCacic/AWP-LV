<?php
$uploadDir = __DIR__ . '/uploads_encrypted';

$cipherMethod = 'AES-256-CBC';
$encryptionKey = 'neki_ljuc_za_kriptiranje';
$encryptionKey = substr(hash('sha256', $encryptionKey, true), 0, 32);

if (isset($_GET['file'])) {
    $fileId = basename($_GET['file']); // zaštita od path traversal-a
    $encPath  = $uploadDir . '/' . $fileId . '.enc';
    $metaPath = $uploadDir . '/' . $fileId . '.meta';

    if (!file_exists($encPath) || !file_exists($metaPath)) {
        http_response_code(404);
        echo 'Datoteka ne postoji.';
        exit;
    }

    $metaJson = file_get_contents($metaPath);
    $meta = json_decode($metaJson, true);
    if (!is_array($meta) || !isset($meta['original_name'], $meta['extension'])) {
        http_response_code(500);
        echo 'Neispravni metapodaci.';
        exit;
    }

    $encryptedData = file_get_contents($encPath);
    if ($encryptedData === false) {
        http_response_code(500);
        echo 'Ne mogu pročitati kriptiranu datoteku.';
        exit;
    }

    $ivLength = openssl_cipher_iv_length($cipherMethod);
    if (strlen($encryptedData) < $ivLength) {
        http_response_code(500);
        echo 'Neispravni podaci (premalo za IV).';
        exit;
    }

    $iv = substr($encryptedData, 0, $ivLength);
    $cipherText = substr($encryptedData, $ivLength);

    $decrypted = openssl_decrypt(
        $cipherText,
        $cipherMethod,
        $encryptionKey,
        OPENSSL_RAW_DATA,
        $iv
    );

    if ($decrypted === false) {
        http_response_code(500);
        echo 'Greška pri dekriptiranju.';
        exit;
    }

    $ext = strtolower($meta['extension']);
    $originalName = $meta['original_name'];

    $contentType = 'application/octet-stream';
    if ($ext === 'pdf') {
        $contentType = 'application/pdf';
    } elseif ($ext === 'jpg' || $ext === 'jpeg') {
        $contentType = 'image/jpeg';
    } elseif ($ext === 'png') {
        $contentType = 'image/png';
    }

    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . basename($originalName) . '"');
    header('Content-Length: ' . strlen($decrypted));

    echo $decrypted;
    exit;
}

// Ako nema GET parametra -> ispiši listu
$files = glob($uploadDir . '/*.enc');
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Kriptirani dokumenti</title>
</head>
<body>
    <h1>Kriptirani dokumenti</h1>

    <p><a href="upload.php">Povratak na upload</a></p>

    <?php if (empty($files)): ?>
        <p>Nema kriptiranih dokumenata.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($files as $encPath): ?>
                <?php
                    $base = basename($encPath, '.enc');
                    $metaPath = $uploadDir . '/' . $base . '.meta';
                    $displayName = $base;

                    if (file_exists($metaPath)) {
                        $metaJson = file_get_contents($metaPath);
                        $meta = json_decode($metaJson, true);
                        if (is_array($meta) && isset($meta['original_name'])) {
                            $displayName = $meta['original_name'];
                        }
                    }
                ?>
                <li>
                    <?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
                    - <a href="list.php?file=<?php echo urlencode($base); ?>">Preuzmi dekriptirani</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>