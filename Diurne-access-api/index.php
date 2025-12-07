<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/src/MdbToArray.php';

$pathToMDBFile = __DIR__ . '/db_access/db.mdb';
$tableName = $_GET['table'];
$transformer = new \ApiAccess\MdbToArray($tableName, $pathToMDBFile);
$jsonData = json_encode($transformer->toArray());
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
echo $jsonData;