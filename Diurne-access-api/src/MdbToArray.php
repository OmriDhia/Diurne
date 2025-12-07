<?php

namespace ApiAccess;

class MdbToArray
{
    private $tableName;
    private $pathToFileMdb;

    public function __construct(string $tableName, string $pathToFileMdb)
    {
        $this->tableName = $tableName;
        $this->pathToFileMdb = $pathToFileMdb;
    }

    /**
     * Ancienne méthode — charge tout en mémoire.
     */
    public function toArray(): array
    {
        $mdbFile = new \MDBTools\Files\MDBFile($this->pathToFileMdb);
        $table = new \MDBTools\Tables\MDBTable($this->tableName, $mdbFile);
        $data = $this->reorganizeData($table->toArray());
        return $data;
    }

    /**
     * Nouvelle méthode — export en streaming, sans chargement complet.
     *
     * Le callback reçoit chaque ligne sous forme :
     *   ['COL1' => value, 'COL2' => value, ...]
     */
    public function streamRows(callable $callback)
    {
        $mdbFile = new \MDBTools\Files\MDBFile($this->pathToFileMdb);
        $table = new \MDBTools\Tables\MDBTable($this->tableName, $mdbFile);

        // Récupération brutale (par colonne)
        $raw = $table->toArray(); // Colonnes => tableaux de valeurs

        // Si table vide
        if (empty($raw)) {
            return;
        }

        $columns = array_keys($raw);
        $rowCount = count(reset($raw)); // Nombre de lignes

        // Streaming ligne par ligne
        for ($i = 0; $i < $rowCount; $i++) {
            $row = [];

            foreach ($columns as $col) {
                $row[$col] = $raw[$col][$i] ?? null;
            }

            $callback($row);
        }
    }

    /**
     * Transforme le format "par colonne" vers "par ligne".
     */
    private function reorganizeData(array $data): array
    {
        $result = [];

        foreach ($data as $columnName => $values) {
            foreach ($values as $index => $value) {
                if (!isset($result[$index])) {
                    $result[$index] = [];
                }
                $result[$index][$columnName] = $value;
            }
        }

        return $result;
    }
}
