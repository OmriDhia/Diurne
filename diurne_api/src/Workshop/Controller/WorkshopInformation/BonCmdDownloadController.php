<?php

namespace App\Workshop\Controller\WorkshopInformation;

use App\Workshop\Repository\WorkshopOrderRepository;
use App\Setting\Repository\ManufacturerRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class BonCmdDownloadController extends AbstractController
{
    public function __construct(
        private readonly WorkshopOrderRepository $workshopOrderRepository,
        private readonly ManufacturerRepository  $manufacturerRepository
    )
    {
    }

    #[Route('/api/workshopOrdersBonCmd/{workshopOrderId}', name: 'workshop_order_bon_cmd_download', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Download Bon de commande as HTML')]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $workshopOrderId): JsonResponse
    {
        $order = $this->workshopOrderRepository->find($workshopOrderId);
        if (!$order) {
            return new JsonResponse(['error' => 'Workshop order not found.'], JsonResponse::HTTP_NOT_FOUND);
        }
        $info = $order->getWorkshopInformation();
        if (!$info) {
            return new JsonResponse(['error' => 'Workshop information not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $fontDir = $this->getFontDir();
        $templateContent = $this->loadTemplate(__DIR__ . '/../../Resources/views/bon-cmd.html');

        $compositionHeader = '';
        $compositionRows = '';
        $composition = $order->getImageCommand()?->getCarpetDesignOrder()?->getCarpetSpecification()?->getCarpetComposition();
        if ($composition) {
            $threadCount = $composition->getThreadCount() ?? 0;
            for ($i = 1; $i <= $threadCount; $i++) {
                $compositionHeader .= '<th>Color ' . $i . '</th><th>Material ' . $i . '</th><th>Percentage ' . $i . '</th>';
            }
            foreach ($composition->getLayers() as $layer) {
                $compositionRows .= '<tr>';
                $compositionRows .= '<td>' . htmlspecialchars((string)$layer->getLayerNumber()) . '</td>';
                for ($i = 1; $i <= $threadCount; $i++) {
                    $detail = null;
                    foreach ($layer->getLayerDetails() as $ld) {
                        if ($ld->getThread()?->getThreadNumber() === $i) {
                            $detail = $ld;
                            break;
                        }
                    }
                    $color = $detail?->getColor()?->getReference() ?? '';
                    $material = $detail?->getMaterial()?->getReference() ?? '';
                    $compositionRows .= '<td>' . htmlspecialchars((string)$color) . '</td>';
                    $compositionRows .= '<td>' . htmlspecialchars((string)$material) . '</td>';
                    // Percentage value from LayerDetail (formatted to remove trailing zeros but keep at least one decimal if needed)
                    $percentage = $detail?->getPercentage();
                    if ($percentage !== null && $percentage !== '') {
                        // Normalize decimal separator
                        $percentageNormalized = str_replace(',', '.', (string)$percentage);
                        // Format to up to 6 decimal places (entity stored as DECIMAL scale 6), trim unnecessary zeros
                        if (is_numeric($percentageNormalized)) {
                            $percentageFormatted = rtrim(rtrim(number_format((float)$percentageNormalized, 6, '.', ''), '0'), '.');
                        } else {
                            $percentageFormatted = $percentage;
                        }
                    } else {
                        $percentageFormatted = '';
                    }
                    // Append percent sign if not already present and value is not empty
                    if ($percentageFormatted !== '' && !str_ends_with((string)$percentageFormatted, '%')) {
                        $percentageFormatted .= '%';
                    }
                    $compositionRows .= '<td>' . htmlspecialchars((string)$percentageFormatted) . '</td>';
                }
                $compositionRows .= '<td>' . htmlspecialchars((string)($layer->getRemarque() ?? '')) . '</td>';
                $compositionRows .= '</tr>';
            }
        }

        $manufacturerName = '';
        if (null !== $info->getManufacturerId()) {
            $manufacturer = $this->manufacturerRepository->find($info->getManufacturerId());
            $manufacturerName = $manufacturer?->getName() ?? '';
        }

        $placeholders = [
            '{{rn}}' => htmlspecialchars((string)$info->getRn()),
            '{{manufacturer}}' => htmlspecialchars($manufacturerName),
            '{{quality}}' => htmlspecialchars((string)($info->getQuality()?->getName() ?? '')),
            '{{launchDate}}' => $info->getLaunchDate()?->format('d/m/Y') ?? '',
            '{{expectedEndDate}}' => $info->getExpectedEndDate()?->format('d/m/Y') ?? '',
            '{{orderedHeigh}}' => htmlspecialchars($this->formatAmount($info->getOrderedHeigh())),
            '{{orderedWidth}}' => htmlspecialchars($this->formatAmount($info->getOrderedWidth())),
            '{{carpetPurchasePricePerM2}}' => htmlspecialchars($this->formatAmount($info->getCarpetPurchasePricePerM2())),
            '{{carpetPurchasePriceTheoretical}}' => htmlspecialchars($this->formatAmount($info->getCarpetPurchasePriceTheoretical())),
            '{{compositionHeader}}' => $compositionHeader,
            '{{compositionRows}}' => $compositionRows,
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
    }

    // Helper to format amounts like (33.00). Returns empty string for null/empty values.
    private function formatAmount(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $str = (string)$value;

        // If already in parentheses, assume it's formatted
        if (preg_match('/^\(.*\)$/', trim($str))) {
            return $str;
        }

        // Normalize comma decimal separators
        $normalized = str_replace(',', '.', $str);

        if (!is_numeric($normalized)) {
            // Return original string if it's not numeric
            return $str;
        }

        $num = (float)$normalized;
        return number_format($num, 2, '.', '');
    }

    private function loadTemplate(string $templatePath): string
    {
        if (!is_file($templatePath)) {
            throw new \RuntimeException('Template file not found.');
        }

        $templateContent = file_get_contents($templatePath);
        if ($templateContent === false) {
            throw new \RuntimeException('Failed to read the template file.');
        }

        return $templateContent;
    }

    private function getFontDir(): string
    {
        return $this->getParameter('kernel.project_dir') . '/templates/Fonts/static';
    }
}
