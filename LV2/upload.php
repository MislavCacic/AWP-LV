<?php

$uploadDir = __DIR__ . '/uploads_encrypted';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$cipherMethod = 'AES-256-CBC';
$encryptionKey = 'neki_ljuc_za_kriptiranje';
$encryptionKey = substr(hash('sha256', $encryptionKey, true), 0, 32);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Greška pri uploadu datoteke.';
    } else {
        $allowedExt = ['pdf','jpeg', 'png'];
        $originalName = $file['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            $message = 'Dozvoljeni su samo PDF, JPEG i PNG.';
        } else {
            // Učitaj sadržaj datoteke
            $fileContents = file_get_contents($file['tmp_name']);
            if ($fileContents === false) {
                $message = 'Ne mogu pročitati uploadanu datoteku.';
            } else {
                // Generiraj IV (inicijalizacijski vektor)
                $ivLength = openssl_cipher_iv_length($cipherMethod);
                $iv = openssl_random_pseudo_bytes($ivLength);

                // Kriptiraj sadržaj
                $encrypted = openssl_encrypt(
                    $fileContents,
                    $cipherMethod,
                    $encryptionKey,
                    OPENSSL_RAW_DATA,
                    $iv
                );

                if ($encrypted === false) {
                    $message = 'Greška pri kriptiranju datoteke.';
                } else {
                    // Spremamo: IV + kriptirani sadržaj + originalno ime (u zasebnu metadatoteku)
                    // Datoteka će imati unikatan naziv
                    $uniqueName = uniqid('file_', true);
                    $encPath = $uploadDir . '/' . $uniqueName . '.enc';
                    $metaPath = $uploadDir . '/' . $uniqueName . '.meta';

                    // U .enc spremamo IV + kriptirani sadržaj
                    $dataToStore = $iv . $encrypted;

                    if (file_put_contents($encPath, $dataToStore) === false) {
                        $message = 'Ne mogu spremiti kriptiranu datoteku.';
                    } else {
                        // U .meta spremamo originalni naziv i ekstenziju
                        $meta = [
                            'original_name' => $originalName,
                            'extension'     => $ext
                        ];
                        file_put_contents($metaPath, json_encode($meta));

                        $message = 'Datoteka je uspješno uploadana i kriptirana.';
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Upload i kriptiranje</title>
</head>
<body>
    <h1>Upload i kriptiranje dokumenta</h1>

    <?php if (!empty($message)): ?>
        <p><strong><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Odaberite dokument (PDF, JPG, JPEG, PNG):</label><br>
        <input type="file" name="file" required><br><br>
        <button type="submit">Upload i kriptiraj</button>
    </form>

    <p><a href="list.php">Popis kriptiranih dokumenata</a></p>
</body>
</html>