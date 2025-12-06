<?php

namespace App\Contremarque\Controller;

use App\Setting\Entity\TransportConditionLang;
use DateTime;
use Exception;
use RuntimeException;
use App\Contact\Repository\CustomerRepository;
use App\Common\Calculator\Utils\Tools;
use App\Contremarque\Repository\ImageAttachmentRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Service\ImageProvider;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\QualityLangRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuoteDownloadController extends AbstractController
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

    #[Route('/api/quote/download/{quoteId}', name: 'api_quote_download', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Download Quote as HTML')]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(Request $request, int $quoteId): JsonResponse
    {
        try {
            $quote = $this->quoteRepository->find($quoteId);
            if (!$quote) {
                return $this->createErrorResponse('Quote not found.', JsonResponse::HTTP_NOT_FOUND);
            }

            $customerName = $this->getCustomerName($quote);
            $contactName = $this->getContactName($quote);
            $address = $this->customerRepository->getCustomerAddress($quote->getContremarque()->getCustomer()->getId(), 'Livraison');
            $fontDir = $this->getFontDir();
            $templateContent = $this->loadTemplate(__DIR__ . '/../Resources/views/quote_template.html');
            $quoteRows = $this->quoteRepository->getQuoteRows($quoteId);
            $quoteBlocs = $this->generateQuoteBlocs($quoteRows);
            $conditionsTransport = $quote->getTransportCondition()->getTransportConditionLangs()->map(fn(TransportConditionLang $lang) => $lang->getDescription())->first();
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
                '{{conditionsTransport}}' => htmlspecialchars((string)$conditionsTransport),
                '{{fontPathRegular}}' => 'file://' . $fontDir . '/EBGaramond-Regular.ttf',
                '{{fontPathItalic}}' => 'file://' . $fontDir . '/EBGaramond-Italic.ttf',
                '{{fontPathMedium}}' => 'file://' . $fontDir . '/EBGaramond-Medium.ttf',
                '{{fontPathMediumItalic}}' => 'file://' . $fontDir . '/EBGaramond-MediumItalic.ttf',
                '{{fontPathSemiBold}}' => 'file://' . $fontDir . '/EBGaramond-SemiBold.ttf',
                '{{fontPathSemiBoldItalic}}' => 'file://' . $fontDir . '/EBGaramond-SemiBoldItalic.ttf',
                '{{fontPathBold}}' => 'file://' . $fontDir . '/EBGaramond-Bold.ttf',
                '{{fontPathBoldItalic}}' => 'file://' . $fontDir . '/EBGaramond-BoldItalic.ttf',

            ];

            $htmlContent = strtr($templateContent, $placeholders);

            return new JsonResponse($htmlContent, JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return $this->createErrorResponse('Failed to generate quote HTML: ' . $e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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
            $quoteBlocs .= PHP_EOL . PHP_EOL . $this->loadRowBloc($row) . PHP_EOL . PHP_EOL;
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
        $location = $quoteDetail->getLocation()->getDescription();
        $collection = $this->getCollection(
            $quoteDetail->getCarpetSpecification()->getCollection()?->getCode(),
            $quoteDetail->getCarpetSpecification()->getModel()?->getCode()
        );

        $dimensionStatement = $this->formatDimensions($this->extractDimensions($quoteDetail));
        $materialStatement = $this->materialRepository->getMaterialComposition($this->extractMaterials($quoteDetail));
        $priceResult = $this->calculatePriceResult($quoteDetail);
        $vignettePath = $this->getVignettePath($quoteDetail);
        $quality = $quoteDetail->getCarpetSpecification()->getQuality();
        $language = $this->languageRepository->findOneBy(['iso_code' => 'fr']);

        $vars = [
            '{{location}}' => htmlspecialchars($location),
            '{{collection}}' => htmlspecialchars($collection),
            '{{quality}}' => htmlspecialchars((string)$quoteDetail->getCarpetSpecification()->getQuality()?->getName()),
            '{{qualityLang}}' => htmlspecialchars((string)$this->qualityLangRepository->findOneBy(['quality' => $quality, 'language' => $language])?->getDescription()),
            '{{dimension}}' => $dimensionStatement,
            '{{materials}}' => $materialStatement,
            '{{discount}}' => $this->formatPrices($quoteDetail->getProposedDiscountRate()),
            '{{discount_price}}' => $this->formatPrices($priceResult['remise-proposee']['totalPriceHt']),
            '{{price_ht_meter}}' => $this->formatPrices($priceResult['prix-propose']['m²']['price']),
            '{{price_ht_total}}' => $this->formatPrices($priceResult['prix-propose']['totalPriceHt']),
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
            $quoteDetail->getCarpetSpecification()->getModel()?->getCode()
        );

        $dimensionStatement = $this->formatDimensions($this->extractDimensions($quoteDetail));
        $materialStatement = $this->materialRepository->getMaterialComposition($this->extractMaterials($quoteDetail));
        $priceResult = $this->calculatePriceResult($quoteDetail);
        $vignettePath = $this->getVignettePath($quoteDetail);

        $quality = $quoteDetail->getCarpetSpecification()->getQuality();
        $language = $this->languageRepository->findOneBy(['iso_code' => 'fr']);
        $vars = [
            '{{index}}' => $index + 1,
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
            if (!$measurement) {
                continue;
            }
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

        $area = ($width * $length) / 10000;

        return sprintf('%.2f cm x %.2f cm = %.2f m²', $width, $length, $area);
    }

    private function extractDimensionValue(array $data, string $key): float
    {
        if (!isset($data[$key])) {
            return 0.0;
        }

        foreach ($data[$key] as $unit) {
            if ($unit['unit_abbreviation'] === 'cm') {
                return (float)$unit['value'];
            }
        }

        return 0.0;
    }

    private function getSummaryBloc($quote): string
    {
        $templateContent = $this->loadTemplate(__DIR__ . '/../Resources/views/partials/summary_bloc.html');
        $fontDir = $this->getFontDir();

        $vars = [
            '{{withoutDiscountPrice}}' => $this->formatPrices($quote->getWithoutDiscountPrice()),
            '{{totalDiscountAmount}}' => $this->formatPrices($quote->getTotalDiscountAmount()),
            '{{total_price_ht}}' => $this->formatPrices($quote->getTotalTaxExcluded()),
            '{{shipping}}' => $this->formatPrices($quote->getShippingPrice()),
            '{{tax}}' => $this->formatPrices($quote->getTax()),
            '{{cumulatedDiscountAmount}}' => $this->formatPrices($quote->getCumulatedDiscountAmount()),
            '{{additionalDiscount}}' => $this->formatPrices($quote->getAdditionalDiscount()),
            '{{tva}}' => (int)$quote->getDiscountRule()->getDiscount(),
            '{{fontPathRegular}}' => 'file://' . $fontDir . '/EBGaramond-Regular.ttf',
            '{{fontPathItalic}}' => 'file://' . $fontDir . '/EBGaramond-Italic.ttf',
            '{{fontPathMedium}}' => 'file://' . $fontDir . '/EBGaramond-Medium.ttf',
            '{{fontPathMediumItalic}}' => 'file://' . $fontDir . '/EBGaramond-MediumItalic.ttf',
            '{{fontPathSemiBold}}' => 'file://' . $fontDir . '/EBGaramond-SemiBold.ttf',
            '{{fontPathSemiBoldItalic}}' => 'file://' . $fontDir . '/EBGaramond-SemiBoldItalic.ttf',
            '{{fontPathBold}}' => 'file://' . $fontDir . '/EBGaramond-Bold.ttf',
            '{{fontPathBoldItalic}}' => 'file://' . $fontDir . '/EBGaramond-BoldItalic.ttf',
        ];
        if ((int)$quote->getDiscountRule()->getDiscount() > 0) {
            $vars['{{total_price_ttc}}'] = $quote->getTotalTaxIncluded();
        } else {
            $vars['{{total_price_ttc}}'] = '';
        }

        return strtr($templateContent, $vars);
    }

    function formatPrices($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    private function getFontDir(): string
    {
        return $this->getParameter('kernel.project_dir') . '/templates/Fonts/static';
    }
}
