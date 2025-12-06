<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\ExportQuote;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Command\ExportQuoteDocx\ExportQuoteDocxCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Controller for exporting a quote as a DOCX file and providing a download link.
 *
 * This controller allows authorized users to export a specific quote into a Word (DOCX)
 * document using the CQRS pattern. It takes a quote ID as a parameter, checks user permissions,
 * generates the DOCX file, saves it under public/quote/, and returns a JSON response with a
 * download link to the file.
 */
class ExportQuoteDocxController extends CommandQueryController
{
    #[Route(path: '/api/export-quote-docx/{id}', name: 'export_quote_docx', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Download link for the DOCX file of the quote generated successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'download_link', type: 'string', example: '/quote/quote_123.docx')
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - Insufficient permissions to export the quote',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 401),
                new OA\Property(property: 'message', type: 'string', example: 'Unauthorized to access this content')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Quote not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Quote not found')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'The identifier of the quote to export',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(int $id, Request $request): Response
    {
        // Check if the user has permission to export quotes
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Get the current user's ID using Symfony Security
        $session = $this->container->get('request_stack')->getSession();
        $session->start();
        $currentUser = $session->get('user');
        if (!$currentUser) {
            return new JsonResponse(['code' => 401, 'message' => 'User not authenticated'], 401);
        }
        $userId = $currentUser->getId();

        // Create and dispatch the CQRS command
        $command = new ExportQuoteDocxCommand($id, $userId);

        try {
            // The handler generates the file and returns a Response
            $response = $this->handle($command);
        } catch (RuntimeException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        // Define the file path under public/quote/
        $fileName = "quote_{$id}.docx";
        $filePath = $this->getParameter('kernel.project_dir') . '/public/quote/' . $fileName;

        // Ensure the quote directory exists
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            $filesystem = new Filesystem();
            $filesystem->mkdir($directory, 0755);
        }

        // Save the file content from the response
        file_put_contents($filePath, $response->getContent());

        // Generate the download link
        $baseUrl = $request->getSchemeAndHttpHost(); // e.g., http://localhost
        $downloadLink = $baseUrl . '/quote/' . $fileName;

        // Return a JSON response with the download link
        return new JsonResponse([
            'success' => true,
            'download_link' => $downloadLink
        ], 200);
    }
}
