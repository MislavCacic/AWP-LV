<?php

$xmlFile   = 'LV2.xml';
$tableName = 'osobe';  
$columns = ['id', 'ime', 'prezime', 'email', 'spol', 'slika', 'zivotopis'];

// Nazivi izlaznih datoteka
$backupTxt = 'backup_xml_' . date('Ymd_His') . '.txt';
$backupGz  = $backupTxt . '.gz';

// učitavanje XML datoteke
if (!file_exists($xmlFile)) {
    die('XML datoteka ne postoji: ' . $xmlFile);
}

$xml = simplexml_load_file($xmlFile);

if ($xml === false) {
    die('Ne mogu učitati XML datoteku: ' . $xmlFile);
}

// otvaranje txt datoteke za pisanje
$fileHandle = fopen($backupTxt, 'w');

if (!$fileHandle) {
    die('Datoteka se ne može otvoriti.' . $backupTxt);
}

// generiranje INSERT za svaki RECORD
$columnsPart = '(' . implode(', ', $columns) . ')';

foreach ($xml->record as $record) {
    $values = [];

    foreach ($columns as $col) {
        $value = isset($record->{$col}) ? (string)$record->{$col} : '';

        $value = str_replace("\\", "\\\\", $value);
        $value = str_replace("'", "\\'", $value);

        $values[] = "'" . $value . "'";
    }

    $valuesPart = '(' . implode(', ', $values) . ')';

    $line  = "INSERT INTO $tableName $columnsPart\n";
    $line .= "VALUES $valuesPart;\n";

    fwrite($fileHandle, $line);
}

fclose($fileHandle);

// sažimanje dobivene txt datoteke (gzip)
$contents = file_get_contents($backupTxt);
if ($contents === false) {
    die('Ne mogu pročitati datoteku za kompresiju: ' . $backupTxt);
}

$gzData = gzencode($contents, 9);

if ($gzData === false) {
    die('Greška pri kompresiji.');
}

if (file_put_contents($backupGz, $gzData) === false) {
    die('Ne mogu zapisati gzip datoteku: ' . $backupGz);
}

echo "Backup iz XML-a završen.\n";
echo "Tekstualni backup: $backupTxt\n";
echo "Sažeta datoteka: $backupGz\n";