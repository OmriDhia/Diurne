<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DiscountRuleLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'discountRuleLangs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DiscountRule $discountRule = null;

    #[ORM\Column(length: 50)]
    private ?string $label = null;

    #[ORM\Column(length: 11)]
    private $langId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscountRule(): ?DiscountRule
    {
        return $this->discountRule;
    }

    public function setDiscountRule(?DiscountRule $discountRule): static
    {
        $this->discountRule = $discountRule;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return null
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * @param null $langId
     *
     * @return DiscountRuleLang
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;

        return $this;
    }
}
