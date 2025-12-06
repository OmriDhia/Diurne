<?php

namespace App\Common\Service;

use RuntimeException;

class CsvImporterService
{
    /**
     * Opens a CSV file and reads the header.
     *
     * @param string $filePath  path to the CSV file
     * @param string $separator CSV field separator (default is comma)
     *
     * @return array array where the first element is the header row and the second element is the file handle
     *
     * @throws RuntimeException if the file cannot be opened or read
     */
    private function openCsvFile(string $filePath, string $separator = ','): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new RuntimeException('Error reading CSV file: '.$filePath);
        }

        $handle = fopen($filePath, 'r');
        if (false === $handle) {
            throw new RuntimeException('Error opening CSV file: '.$filePath);
        }

        $header = fgetcsv($handle, 1000, $separator);
        if (false === $header) {
            fclose($handle);
            throw new RuntimeException('Failed to read CSV header from file: '.$filePath);
        }

        return [$header, $handle];
    }

    /**
     * Parses CSV data rows and combines them with the header.
     *
     * @param array    $header    the header row of the CSV file
     * @param resource $handle    the file handle of the opened CSV file
     * @param string   $separator CSV field separator (default is comma)
     *
     * @return array array of CSV records
     *
     * @throws RuntimeException if parsing fails
     */
    private function parseCsvData(array $header, $handle, string $separator = ','): array
    {
        $data = [];

        while (($row = fgetcsv($handle, 1000, $separator)) !== false) {
            $record = array_combine($header, $row);

            if (false === $record) {
                throw new RuntimeException('Failed to parse CSV data row.');
            }

            $data[] = $record;
        }

        fclose($handle);

        return $data;
    }

    /**
     * Reads a CSV file and returns its data as an array.
     *
     * @param string $filePath  path to the CSV file
     * @param string $separator CSV field separator (default is comma)
     *
     * @return array array of CSV records
     *
     * @throws RuntimeException if the file cannot be read or parsed
     */
    public function readCsvFile(string $filePath, string $separator = ','): array
    {
        [$header, $handle] = $this->openCsvFile($filePath, $separator);

        return $this->parseCsvData($header, $handle, $separator);
    }
}
