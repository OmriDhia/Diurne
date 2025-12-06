<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ExportQuoteDocx;

use RuntimeException;
use PhpOffice\PhpWord\Shared\Html;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Bus\Command\ExportQuoteDocx\ExportQuoteDocxCommand;
use App\Contremarque\Entity\Quote;
use App\Contremarque\Repository\QuoteRepository;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twig\Environment;

class ExportQuoteDocxCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository,
        private readonly UserRepository $userRepository,
        private readonly Environment $twig
    ) {}

    /**
     * Handles the ExportQuoteDocxCommand to generate and return a DOCX file.
     *
     * This method fetches the quote and user, renders the quote body HTML using Twig,
     * and generates a DOCX file with headers, footers, and page numbers using PHPWord.
     * The resulting file is returned as a Symfony Response for download.
     *
     * @param ExportQuoteDocxCommand $command The command containing quote and user IDs
     * @return Response The HTTP response with the DOCX file
     * @throws RuntimeException If the quote or user is not found
     */
    public function __invoke(ExportQuoteDocxCommand $command): Response
    {
        // Fetch the quote
        $quote = $this->quoteRepository->find($command->getQuoteId());
        if (!$quote) {
            throw new RuntimeException('Quote not found');
        }

        // Fetch the user
        $user = $this->userRepository->find($command->getUserId());
        if (!$user) {
            throw new RuntimeException('User not found');
        }

        // Create a new PhpWord instance
        $phpWord = new PhpWord();

        // Define default font style
        $phpWord->addFontStyle('defaultFont', ['name' => 'Arial', 'size' => 12]);

        // Add a section for the document
        $section = $phpWord->addSection();

        // Render the quote body HTML
        $quoteHtml = $this->twig->render('quote/quote_body.html.twig', [
            'quote' => $quote,
            'user' => $user,
        ]);

        // Add header to all pages
        $header = $section->addHeader();
        $headerHtml = $this->twig->render('quote/header.html.twig', [
            'quote' => $quote,
        ]);
        Html::addHtml($header, $headerHtml);

        // Add footer with page numbers to all pages
        $footer = $section->addFooter();
        $footerHtml = $this->twig->render('quote/footer.html.twig', [
            'quote' => $quote,
        ]);
        Html::addHtml($footer, $footerHtml);
        $footer->addPreserveText('Page {PAGE} of {NUMPAGES}', ['alignment' => 'center']);

        // Add the quote body content
        Html::addHtml($section, $quoteHtml);

        // Save the document to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'quote_') . '.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        // Create the response
        $response = new Response(file_get_contents($tempFile));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            "quote_{$command->getQuoteId()}.docx"
        );
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', $disposition);

        // Clean up the temporary file
        unlink($tempFile);

        return $response;
    }
}
