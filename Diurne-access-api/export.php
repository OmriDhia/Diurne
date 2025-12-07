<?php

$table = $_GET['table'] ?? null;

if (!$table) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing ?table=...']);
    exit;
}

$mdb = __DIR__ . '/db_access/db.mdb';
$output = __DIR__ . "/exports/$table.json";

if (!is_dir(__DIR__ . '/exports')) {
    mkdir(__DIR__ . '/exports', 0777, true);
}

$cmd = sprintf('mdb-export "%s" "%s"', $mdb, $table);
$handle = popen($cmd, 'r');

if (!$handle) {
    echo json_encode(['error' => 'Cannot run mdb-export']);
    exit;
}

$fp = fopen($output, 'w');
fwrite($fp, "[\n");

$first = true;

// Lire header
$headerLine = trim(fgets($handle));
$columns = str_getcsv($headerLine);
$columnCount = count($columns);

while (($line = fgets($handle)) !== false) {
    $line = trim($line);

    // Ignore lignes vides
    if ($line === '') continue;

    $values = str_getcsv($line);
    $valueCount = count($values);

    // Trop peu de colonnes → compléter avec null
    if ($valueCount < $columnCount) {
        $values = array_pad($values, $columnCount, null);
    }

    // Trop de colonnes → ignorer ce qui dépasse
    if ($valueCount > $columnCount) {
        $values = array_slice($values, 0, $columnCount);
    }

    // Maintenant array_combine ne plantera plus
    $row = array_combine($columns, $values);

    if (!$first) {
        fwrite($fp, ",\n");
    }
    $first = false;

    fwrite($fp, json_encode($row, JSON_UNESCAPED_UNICODE));
}

fwrite($fp, "\n]");
fclose($fp);
pclose($handle);

echo json_encode([
    'status' => 'ok',
    'file' => "exports/$table.json"
]);
