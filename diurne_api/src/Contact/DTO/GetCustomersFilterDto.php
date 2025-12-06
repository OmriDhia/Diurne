<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use App\Common\Assert as customerAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GetCustomersFilterDto
{
    #[Assert\LessThanOrEqual(500)]
    public ?int $page = null;

    #[Assert\LessThanOrEqual(300)]
    public ?int $itemsPerPage = null;

    #[Assert\Length(max: 50, maxMessage: 'firstname cannot exceed {{ limit }} characters.')]
    #[customerAssert\Name(message: 'Please enter a valid name.')]
    public ?string $firstname = null;

    #[Assert\Length(max: 50, maxMessage: 'lastname cannot exceed {{ limit }} characters.')]
    #[customerAssert\Name(message: 'Please enter a valid name.')]
    public ?string $lastname = null;

    #[Assert\Length(max: 50, maxMessage: 'commercial cannot exceed {{ limit }} characters.')]
    #[customerAssert\Name(message: 'Please enter a valid name.')]
    public ?string $commercial = null;

    public ?bool $is_agent = null;
    public ?bool $is_prescripteur = null;

    #[Assert\Length(max: 50, maxMessage: 'prescripteur cannot exceed {{ limit }} characters.')]
    #[customerAssert\Name(message: 'Please enter a valid name.')]
    public ?string $prescripteur = null;

    public ?bool $active = null;
    public ?bool $hasInvalidCommercial = null;
    public ?bool $hasOnlyOneContact = null;
    public ?string $socialReason = null;
    public ?float $tva_ce = null;

    #[customerAssert\Url(message: 'Please enter a valid url.')]
    public ?string $website = null;

    public ?string $customerGroupId = null;

    #[customerAssert\CityName(message: 'Please enter a valid city.')]
    public ?string $city = null;

    #[Assert\Length(max: 12, maxMessage: 'Zip code cannot exceed {{ limit }} characters.')]
    #[customerAssert\PostCode(message: 'Please enter a valid postal code.')]
    public ?string $zip_code = null;

    public ?int $countryId = null;
    public ?bool $hasWrongAddress = null;
    public ?bool $hasValidAddress = null;
    public ?int $mailingLanguageId = null;
    public ?string $contactMailing = null;
    public ?string $customerName = null;
    public ?string $commercialId = null;
}
