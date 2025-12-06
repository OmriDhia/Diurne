<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\ExportQuote;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Repository\QuoteRepository;
use Knp\Snappy\Pdf;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

use Twig\Environment;
use OpenApi\Attributes as OA;

class ExportQuotePdfController extends CommandQueryController
{
    public function __construct(
        private readonly Security        $security,
        private readonly Environment     $twig,
        private readonly QuoteRepository $quoteRepository,
        private readonly Pdf             $snappyPdf
    )
    {
    }

    #[Route(path: '/api/export-quote-pdf/{id}', name: 'export_quote_pdf', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'PDF file of the quote downloaded successfully',
        content: new OA\MediaType(
            mediaType: 'application/pdf',
            schema: new OA\Schema(type: 'string', format: 'binary')
        )
    )]
    #[OA\Response(response: 401, description: 'Unauthorized')]
    #[OA\Response(response: 404, description: 'Quote not found')]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'html', type: 'string', description: 'Base64-encoded HTML content')
                ]
            )
        )
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(int $id, Request $request): Response
    {
        if (!$this->security->isGranted('update', 'contremarque')) {
            return new Response('Unauthorized', 401);
        }

        $quote = $this->quoteRepository->find($id);
        if (!$quote) {
            throw new NotFoundHttpException('Quote not found');
        }

        $session = $this->container->get('request_stack')->getSession();
        $user = $session->get('user');
        if (!$user) {
            return new Response('User not authenticated', 401);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['html'])) {
            return new JsonResponse(['error' => 'Missing HTML content'], 400);
        }

        $htmlContent = base64_decode((string)$data['html']);
        if ($htmlContent === false) {
            return new JsonResponse(['error' => 'Invalid Base64 encoding'], 400);
        }
        $fontPath = $this->getParameter('kernel.project_dir') . '/templates/Fonts/static/EBGaramond-Regular.ttf';
        $base64Font = base64_encode(file_get_contents($fontPath));
        $logoPath = $this->getParameter('kernel.project_dir') . '/templates/quote/logo.png';
        $logoBase64 = base64_encode(file_get_contents($logoPath));
        $headerHtml = $this->twig->render('quote/header.html.twig', ['quote' => $quote, 'user' => $user, 'logo_base64' => $logoBase64, 'base64Font' => $base64Font]);
        $footerHtml = $this->twig->render('quote/footer.html.twig', ['quote' => $quote, 'user' => $user, 'base64Font' => $base64Font]);
        $headerFile = $this->generateTemporaryFile($headerHtml, 'header');
        $footerFile = $this->generateTemporaryFile($footerHtml, 'footer');

        // Debug: Check temporary files
        if (!file_exists($headerFile) || !file_exists($footerFile)) {
            throw new \RuntimeException('Failed to create temporary header/footer files');
        }

        $options = [
            'header-html' => $headerFile,
            'footer-html' => $footerFile,
            'margin-top' => '30mm',
            'margin-bottom' => '25mm',
            'margin-left' => '15mm',
            'margin-right' => '15mm',
            'page-size' => 'A4',
            'encoding' => 'UTF-8',
            'no-outline' => true,
            'disable-smart-shrinking' => false,
            'javascript-delay' => 1000,
            'load-error-handling' => 'ignore',
            'enable-local-file-access' => true,
        ];

        // Debug: Log the options being used
        file_put_contents('/tmp/pdf_options.json', json_encode($options));

        $pdfContent = $this->snappyPdf->getOutputFromHtml($htmlContent, $options);

        $this->deleteTemporaryFiles();

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="quote_' . $id . '.pdf"',
        ]);
    }

    private function generateTemporaryFile(string $html, string $type): string
    {
        $tempFilePath = sys_get_temp_dir() . "/quote_{$type}_" . uniqid() . '.html';
        file_put_contents($tempFilePath, $html);

        return $tempFilePath;
    }

    private function deleteTemporaryFiles(): void
    {
        $tempFiles = glob(sys_get_temp_dir() . "/quote_*_*.html");
        foreach ($tempFiles as $file) {
            unlink($file);
        }
    }
}
