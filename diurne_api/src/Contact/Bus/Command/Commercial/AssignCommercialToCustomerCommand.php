<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use App\Common\Bus\Command\Command;

class AssignCommercialToCustomerCommand implements Command
{
    private $status;
    private $fromDate;
    private $toDate;

    public function __construct(
        private readonly int $commercialId,
        private readonly int $customerId
    ) {
    }

    public function getCommercialId(): int
    {
        return $this->commercialId;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @return AssignCommercialToCustomerCommand
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @return AssignCommercialToCustomerCommand
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return AssignCommercialToCustomerCommand
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
