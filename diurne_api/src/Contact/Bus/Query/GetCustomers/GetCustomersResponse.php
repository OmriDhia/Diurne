<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomers;

use App\Common\Bus\Query\QueryResponse;
use App\Common\Service\ExportService;
use Symfony\Component\HttpFoundation\Response;

final class GetCustomersResponse implements QueryResponse
{
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $customers,
        public ?string $exportFormat,
    ) {
    }

    /**
     * @return (array|int)[]
     *
     * @psalm-return array{count: int, page: int, itemsPerPage: int, customers: array}
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'customers' => $this->customers,
        ];
    }

    public function export(): Response
    {
        $exportService = new ExportService();
        $headers = [
            'Raison_Sociale' => 'raison_social',
            'Type de Client' => 'cg_name',
            'Civilite' => 'civility',
            'Contact' => 'contact',
            'Commercial_Actuel' => 'last_commercial',
            'EMAIL_CONTACT' => 'email',
            'ADRESSE' => 'address',
            'VILLE' => 'city',
            'CP' => 'zip_code',
            'PAYS' => 'country',
            'Adresse_Complet' => 'has_wrong_address',
            'Remarque' => 'comment',
        ];
        $filename = 'Mailing'.date('dmYHis'); // e.g., Mailing01092024144747
        $exportService->setHeaders($headers);

        return $exportService->export($this->customers, $filename, $this->exportFormat);
    }
}
