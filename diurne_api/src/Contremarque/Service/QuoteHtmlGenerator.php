<?php

declare(strict_types=1);

namespace App\Contremarque\Service;

use RuntimeException;
use DateTime;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Calculator\Utils\Tools;
use App\Contremarque\Repository\ImageAttachmentRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\QualityLangRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuoteHtmlGenerator
{
    public function __construct(
        private readonly QuoteRepository           $quoteRepository,
        private readonly CustomerRepository        $customerRepository,
        private readonly MaterialRepository        $materialRepository,
        private readonly ImageAttachmentRepository $imageAttachmentRepository,
        private readonly ImageProvider             $imageProvider,
        private readonly QualityLangRepository     $qualityLangRepository,
        private readonly LanguageRepository        $languageRepository,
    ) {}

    public function generateHtml(int $quoteId): string
    {
        $quote = $this->quoteRepository->find($quoteId);
        if (!$quote) {
            throw new RuntimeException('Quote not found.');
        }

        $customerName = $this->getCustomerName($quote);
        $contactName = $this->getContactName($quote);
        $address = $this->customerRepository->getCustomerAddress($quote->getContremarque()->getCustomer()->getId(), 'Livraison');

        $templateContent = $this->loadTemplate(__DIR__ . '/../Resources/views/quote_template.html');
        $quoteRows = $this->quoteRepository->getQuoteRows($quoteId);
        $quoteBlocs = $this->generateQuoteBlocs($quoteRows);

        $placeholders = [
            '{{quoteId}}' => htmlspecialchars((string)$quoteId),
            '{{date}}' => htmlspecialchars((new DateTime())->format('d/m/Y')),
            '{{customerName}}' => $customerName,
            '{{contactName}}' => $contactName,
            '{{customerAddress}}' => $address,
            '{{contremarqueName}}' => htmlspecialchars((string)$quote->getContremarque()->getDesignation()),
            '{{quoteNumber}}' => htmlspecialchars((string)$quote->getReference()),
            '{{quoteCarpetCount}}' => $quote->getQuoteDetails()->count(),
            '{{quoteRows}}' => $quoteBlocs,
            '{{summary}}' => $this->getSummaryBloc($quote),
        ];

        return strtr($templateContent, $placeholders);
    }

    // Private helper methods (copied from QuoteDownloadController)
    private function getCustomerName($quote): string
    {
        $customer = $quote->getContremarque()->getCustomer();
        return $customer->getCustomerGroup()->getName() === 'Particulier (Client)'
            ? $this->customerRepository->getContactName($customer->getId())
            : $customer->getSocialReason();
    }

    private function getContactName($quote): string
    {
        $customer = $quote->getContremarque()->getCustomer();
        return $customer->getCustomerGroup()->getName() === 'Particulier (Client)'
            ? ''
            : $this->customerRepository->getContactName($customer->getId());
    }

    private function generateQuoteBlocs(array $quoteRows): string
    {
        $quoteBlocs = '';
        foreach ($quoteRows as $row) {
            $quoteBlocs .= PHP_EOL . $this->loadRowBloc($row) . PHP_EOL;
        }
        return $quoteBlocs;
    }

    private function loadRowBloc(array $row): string
    {
        if ($row['row_count'] == 1) {
            return $this->loadSingleRowBloc($row[0]);
        } elseif ($row['row_count'] > 1) {
            return $this->loadMultipleRowBloc($row);
        }
        return '';
    }

    private function loadSingleRowBloc($quoteDetail): string
    {
        $templatePath = (float)$quoteDetail->getProposedDiscountRate() > 0
            ? __DIR__ . '/../Resources/views/partials/quote_bloc_table_with_discount.html'
            : __DIR__ . '/../Resources/views/partials/quote_bloc_table_without_discount.html';

        $templateContent = $this->loadTemplate($templatePath);

        $collection = $this->getCollection(
            $quoteDetail->getCarpetSpecification()->getCollection()?->getCode(),
            (string)$quoteDetail->getCarpetSpecification()->getModel()?->getCode()
        );

        $dimensionStatement = $this->formatDimensions($this->extractDimensions($quoteDetail));
        $materialStatement = $this->materialRepository->getMaterialComposition($this->extractMaterials($quoteDetail));
        $priceResult = $this->calculatePriceResult($quoteDetail);
        $vignettePath = $this->getVignettePath($quoteDetail);
        $quality = $quoteDetail->getCarpetSpecification()->getQuality();
        $language = $this->languageRepository->findOneBy(['iso_code' => 'fr']);

        $vars = [
            '{{collection}}' => htmlspecialchars($collection),
            '{{quality}}' => htmlspecialchars((string)$quoteDetail->getCarpetSpecification()->getQuality()?->getName()),
            '{{qualityLang}}' => htmlspecialchars((string)$this->qualityLangRepository->findOneBy(['quality' => $quality, 'language' => $language])?->getDescription()),
            '{{dimension}}' => $dimensionStatement,
            '{{materials}}' => $materialStatement,
            '{{discount}}' => $quoteDetail->getProposedDiscountRate(),
            '{{discount_price}}' => $priceResult['remise-proposee']['totalPriceHt'],
            '{{price_ht_meter}}' => $priceResult['prix-propose']['m²']['price'],
            '{{price_ht_total}}' => $priceResult['prix-propose']['totalPriceHt'],
            '{{image}}' => $vignettePath ? $this->generateImageTag($vignettePath) : '',
        ];

        return strtr($templateContent, $vars);
    }

    private function loadMultipleRowBloc(array $row): string
    {
        $quoteDetail = $row[0];
        $idLocation = $quoteDetail->getLocation()->getId();
        $quote = $quoteDetail->getQuote();
        $options = '';

        foreach ($quote->getQuoteDetails() as $index => $detail) {
            if ((int)$detail->getLocation()->getId() !== (int)$idLocation) {
                continue;
            }
            $options .= $this->loadOption($detail, $index);
        }

        $vars = ['{{options}}' => $options];
        $optionsTemplatePath = __DIR__ . '/../Resources/views/partials/quote_bloc_options.html';
        $optionsTemplatePath = $this->loadTemplate($optionsTemplatePath);

        return strtr($optionsTemplatePath, $vars);
    }

    private function loadOption($quoteDetail, $index): string
    {
        $optionTemplatePath = __DIR__ . '/../Resources/views/partials/quote_bloc_option_item.html';
        $templateContent = $this->loadTemplate($optionTemplatePath);


        $collection = $this->getCollection(
            $quoteDetail->getCarpetSpecification()->getCollection()?->getCode(),
            (string)$quoteDetail->getCarpetSpecification()->getModel()?->getCode()
        );

        $dimensionStatement = $this->formatDimensions($this->extractDimensions($quoteDetail));
        $materialStatement = $this->materialRepository->getMaterialComposition($this->extractMaterials($quoteDetail));
        $priceResult = $this->calculatePriceResult($quoteDetail);
        $vignettePath = $this->getVignettePath($quoteDetail);

        $quality = $quoteDetail->getCarpetSpecification()->getQuality();
        $language = $this->languageRepository->findOneBy(['iso_code' => 'fr']);
        $vars = [
            '{{index}}' => $index,
            '{{collection}}' => htmlspecialchars($collection),
            '{{quality}}' => htmlspecialchars((string)$quoteDetail->getCarpetSpecification()->getQuality()?->getName()),
            '{{qualityLang}}' => htmlspecialchars((string)$this->qualityLangRepository->findOneBy(['quality' => $quality, 'language' => $language])?->getDescription()),
            '{{dimension}}' => $dimensionStatement,
            '{{materials}}' => $materialStatement,
            '{{price_ht_meter}}' => $priceResult['prix-propose']['m²']['price'],
            '{{price_ht_total}}' => $priceResult['prix-propose']['totalPriceHt'],
            '{{image}}' => $vignettePath ? $this->generateImageTag($vignettePath) : '',
        ];

        return strtr($templateContent, $vars);
    }

    private function extractDimensions($quoteDetail): array
    {
        $dimensions = [];
        foreach ($quoteDetail->getCarpetSpecification()->getCarpetDimensions() as $carpetDimension) {
            $measurement = $carpetDimension->getMesurement();
            $dimensions[$measurement->getId()] = [];
            foreach ($carpetDimension->getDimensionValues() as $index => $dimensionValue) {
                if ($dimensionValue) {
                    $dimensions[$measurement->getName()][$index] = [
                        'unit_id' => $dimensionValue->getUnit()?->getId() ?? 0,
                        'unit_name' => $dimensionValue->getUnit()?->getName(),
                        'unit_abbreviation' => $dimensionValue->getUnit()?->getAbbreviation(),
                        'value' => $dimensionValue->getValue(),
                    ];
                }
            }
        }
        return $dimensions;
    }

    private function extractMaterials($quoteDetail): array
    {
        $materials = [];
        foreach ($quoteDetail->getCarpetSpecification()->getMaterials() as $material) {
            $materials[$material->getMaterial()->getId()] = [
                'material_id' => $material->getMaterial()->getId(),
                'reference' => $material->getMaterial()->getReference(),
                'rate' => $material->getRate(),
            ];
        }
        return $materials;
    }

    private function calculatePriceResult($quoteDetail): array
    {
        $priceTypes = [
            ['name' => 'Tarif'],
            ['name' => 'Tarif grand projet'],
            ['name' => 'Remise proposee'],
            ['name' => 'Prix propose avant remise complementaire'],
            ['name' => 'Prix propose'],
        ];

        $result = [];
        foreach ($priceTypes as $priceType) {
            $typeName = Tools::slugify($priceType['name']);
            $prices = [];

            foreach ($quoteDetail->getCarpetPriceBases() as $priceBase) {
                if ($priceBase->getPriceType()->getName() === $priceType['name']) {
                    $result[$typeName]['totalPriceHt'] = $priceBase->getTotalPriceHt();
                    $result[$typeName]['totalPriceTtc'] = $priceBase->getTotalPriceTtc();

                    foreach ($priceBase->getPriceSimulators() as $simulator) {
                        $prices[$simulator->getUnit()] = [
                            'unit' => $simulator->getUnit(),
                            'price' => $simulator->getUnitPriceHt(),
                        ];
                    }
                }
            }

            if (!empty($prices)) {
                $result[$typeName] = array_merge($result[$typeName], $prices);
            }
        }
        return $result;
    }

    private function getVignettePath($quoteDetail): ?string
    {
        if (!$quoteDetail->getCarpetDesignOrder()) {
            return null;
        }

        $vignettePath = $this->imageProvider->getVignettePath($quoteDetail->getCarpetDesignOrder());
        if (!is_file($vignettePath)) {
            return null;
        }

        $imageData = file_get_contents($vignettePath);
        return 'data:image/jpeg;base64,' . base64_encode($imageData);
    }

    private function generateImageTag(?string $vignettePath): string
    {
        return $vignettePath
            ? '<div style="display: flex; justify-content: center; align-items: center; width: 200px; height: 200px; margin: 10px;"><img src="' . $vignettePath . '" alt="" width="160" height="160" style="object-fit: contain;"/></div>'
            : '';
    }

    private function loadTemplate(string $templatePath): string
    {

        if (!is_file($templatePath)) {
            throw new RuntimeException('Template file not found.');
        }

        $templateContent = file_get_contents($templatePath);
        if ($templateContent === false) {
            throw new RuntimeException('Failed to read the template file.');
        }

        return $templateContent;
    }

    private function createErrorResponse(string $message, int $statusCode): JsonResponse
    {
        return new JsonResponse(['error' => $message], $statusCode);
    }

    private function getCollection(?string $collection, string $model, ?string $variation = null): string
    {
        if (!$variation) {
            return is_numeric($model) ? ($collection ? "{$collection} {$model}" : "{$model}") : "{$model}";
        }
        return is_numeric($model) ? ($collection ? "{$collection} {$model} {$variation}" : "{$model} {$variation}") : "{$model} {$variation}";
    }

    private function formatDimensions(array $data): string
    {
        $width = $this->extractDimensionValue($data, 'Largeur');
        $length = $this->extractDimensionValue($data, 'Longueur');
        $area = $width * $length;

        return sprintf('%.2f m x %.2f m = %.2f m²', $width, $length, $area);
    }

    private function extractDimensionValue(array $data, string $key): float
    {
        if (!isset($data[$key])) {
            return 0.0;
        }

        foreach ($data[$key] as $unit) {
            if ($unit['unit_abbreviation'] === 'cm') {
                return (float)$unit['value'] / 100;
            }
        }

        return 0.0;
    }

    private function getSummaryBloc($quote): string
    {
        $templateContent = $this->loadTemplate(__DIR__ . '/../Resources/views/partials/summary_bloc.html');

        $vars = [
            '{{withoutDiscountPrice}}' => $quote->getWithoutDiscountPrice(),
            '{{totalDiscountAmount}}' => $quote->getTotalDiscountAmount(),
            '{{total_price_ht}}' => $quote->getTotalTaxExcluded(),
            '{{shipping}}' => $quote->getShippingPrice(),
            '{{tax}}' => $quote->getTax(),
            '{{cumulatedDiscountAmount}}' => $quote->getCumulatedDiscountAmount(),
            '{{additionalDiscount}}' => $quote->getAdditionalDiscount(),
            '{{tva}}' => (int)$quote->getDiscountRule()->getDiscount(),
        ];
        if ((int)$quote->getDiscountRule()->getDiscount() > 0) {
            $vars['{{total_price_ttc}}'] = $quote->getTotalTaxIncluded();
        } else {
            $vars['{{total_price_ttc}}'] = '';
        }

        return strtr($templateContent, $vars);
    }
}
