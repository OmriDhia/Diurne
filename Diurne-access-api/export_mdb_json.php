<?php

require 'vendor/autoload.php'; // si tu utilises mdb-parser via composer

use MdbParser\MdbParser;

// Arguments CLI : php export_mdb_json.php base.mdb TABLE output.json
if ($argc < 4) {
    echo "Usage: php export_mdb_json.php <mdb_file> <table> <output_file>\n";
    exit(1);
}

$mdbFile = $argv[1];
$tableName = $argv[2];
$outputFile = $argv[3];

if (!file_exists($mdbFile)) {
    die("❌ Fichier MDB introuvable : $mdbFile\n");
}

echo "📄 Lecture MDB : $mdbFile\n";
echo "📌 Table       : $tableName\n";
echo "💾 Output JSON : $outputFile\n";

try {
    $parser = new MdbParser($mdbFile);
    $table = $parser->getTable($tableName);
} catch (Exception $e) {
    die("❌ Erreur parser MDB : " . $e->getMessage() . "\n");
}

$fp = fopen($outputFile, 'w');
if (!$fp) {
    die("❌ Impossible d'écrire dans $outputFile\n");
}

fwrite($fp, "[\n");

$first = true;

// ITERATEUR QUI NE CHARGE JAMAIS TOUT EN MÉMOIRE
foreach ($table->getRowIterator() as $row) {

    if (!$first) {
        fwrite($fp, ",\n");
    }
    $first = false;

    fwrite($fp, json_encode($row, JSON_UNESCAPED_UNICODE));
}

fwrite($fp, "\n]");
fclose($fp);

echo "✅ Export terminé avec succès (aucune surcharge mémoire)\n";
exit(0);
