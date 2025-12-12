<?php
$xmlFile = __DIR__ . '/LV2.xml';

if (!file_exists($xmlFile)) {
    die('XML datoteka ne postoji: ' . htmlspecialchars($xmlFile, ENT_QUOTES, 'UTF-8'));
}

$xml = simplexml_load_file($xmlFile);
if ($xml === false) {
    die('Ne mogu učitati XML datoteku.');
}

// Očekujemo strukturu:
// <record>
//   <id>...</id>
//   <ime>...</ime>
//   <prezime>...</prezime>
//   <email>...</email>
//   <spol>...</spol>
//   <slika>URL</slika>
//   <zivotopis>tekst...</zivotopis>
// </record>
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Profili osoba</title>
    <link rel="stylesheet" href="profili.css">
</head>
<body>
    <div class="container">
        <h1>Profili osoba</h1>

        <?php foreach ($xml->record as $record): ?>
            <?php
                $ime       = (string)$record->ime;
                $prezime   = (string)$record->prezime;
                $email     = (string)$record->email;
                $slika     = (string)$record->slika;
                $zivotopis = (string)$record->zivotopis;
            ?>
            <div class="profile">
                <?php if (!empty($slika)): ?>
                    <img src="<?php echo htmlspecialchars($slika, ENT_QUOTES, 'UTF-8'); ?>"
                         alt="Slika <?php echo htmlspecialchars($ime . ' ' . $prezime, ENT_QUOTES, 'UTF-8'); ?>"
                         width="50" height="50">
                <?php endif; ?>

                <div class="details">
                    <h2>
                        <?php echo htmlspecialchars($ime . ' ' . $prezime, ENT_QUOTES, 'UTF-8'); ?>
                    </h2>
                    <div class="email">
                        <a href="mailto:<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </div>
                    <div class="bio">
                        <?php echo htmlspecialchars($zivotopis, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>