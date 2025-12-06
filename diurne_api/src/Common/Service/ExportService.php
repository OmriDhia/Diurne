<?php

namespace App\Common\Service;

use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    private array $headers = [];

    /**
     * Set custom headers for the export.
     * If not called, headers will be dynamically generated from the data keys.
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Export the given data into a file of the specified format (XLSX, XLS, CSV).
     *
     * @param array       $data     the data to be exported
     * @param string      $filename the name of the exported file
     * @param string|null $format   the file format ('xlsx', 'xls', 'csv')
     *
     * @return Response a streamed response with the exported file
     */
    public function export(array $data, string $filename, ?string $format = 'xlsx'): Response
    {
        // If headers are not set explicitly, generate them from the first record's keys
        if (empty($this->headers)) {
            $this->headers = array_keys($data[0]);
        }

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the headers in the first row (Row 1)
        $columnIndex = 'A'; // Start with column A
        foreach ($this->headers as $key => $header) {
            $sheet->setCellValue($columnIndex.'1', ucfirst(str_replace('_', ' ', $key))); // Capitalize and format headers
            ++$columnIndex;
        }

        // Start adding data from the second row (Row 2)
        $dataRow = 2;
        foreach ($data as $record) {
            $columnIndex = 'A'; // Reset to column A for each row
            foreach ($this->headers as $fieldKey) {
                $sheet->setCellValue($columnIndex.$dataRow, $record[$fieldKey] ?? '');
                ++$columnIndex;
            }
            ++$dataRow;
        }

        // Determine the format and set appropriate writer and headers

        $response = new StreamedResponse();

        switch ($format) {
            case 'xls':
                $writer = new Xls($spreadsheet);
                $contentType = 'application/vnd.ms-excel';
                $extension = 'xls';
                break;
            case 'csv':
                $writer = new Csv($spreadsheet);
                $contentType = 'text/csv';
                $extension = 'csv';
                $writer->setDelimiter(';'); // Set delimiter if needed
                $writer->setEnclosure('"');
                $writer->setSheetIndex(0);  // Export only the first sheet in CSV
                break;
            case 'xlsx':
                $writer = new Xlsx($spreadsheet);
                $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $extension = 'xlsx';
                break;
            default:
                throw new InvalidArgumentException("Unsupported format: {$format}");
        }

        // Stream the file
        $response->setCallback(function () use ($writer) {
            $writer->save('php://output');
        });

        // Set headers to indicate the file format and trigger download
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'.'.$extension.'"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('Pragma', 'public');

        return $response;
    }
}
