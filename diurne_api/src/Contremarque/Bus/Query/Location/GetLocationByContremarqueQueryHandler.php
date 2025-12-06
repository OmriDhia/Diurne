<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\Location;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ImageAttachmentRepository;
use App\Contremarque\Repository\LocationRepository;

class GetLocationByContremarqueQueryHandler implements QueryHandler
{
    public function __construct(private readonly LocationRepository $locationRepository, private readonly ImageAttachmentRepository $imageAttachmentRepository)
    {
    }

    public function __invoke(GetLocationByContremarqueQuery $query): GetLocationByContremarqueResponse
    {
        $contremarqueId = $query->getContremarqueId();
        $locations = $this->locationRepository->findBy(['contremarque' => $contremarqueId]);
        $formattedLocations = [];

        if ($locations) {
            foreach ($locations as $index => $location) {
                $formattedLocations[$index] = $location->toArray();

                $order = $this->locationRepository->getLastModifiedOrder($location);
                $vignetteAttachment = null;
                if (!empty($order) && !empty($order->getId())) {
                    $vignetteAttachment = $this->imageAttachmentRepository->findVignette($order, $location);
                }

                $formattedLocations[$index]['vignette'] = null;
                $formattedLocations[$index]['vignette_resized'] = null;

                if (!empty($vignetteAttachment)) {
                    $vignettePath = $this->getVignettePath($vignetteAttachment);
                    $vignetteResizedPath = $this->getResizedVignettePath($vignetteAttachment);

                    $formattedLocations[$index]['vignette'] = $vignettePath;
                    $formattedLocations[$index]['vignette_resized'] = $vignetteResizedPath;
                }
            }
        }

        return new GetLocationByContremarqueResponse($formattedLocations);
    }

    /**
     * Get the path of the vignette attachment.
     */
    private function getVignettePath($vignetteAttachment): string
    {
        return $vignetteAttachment->getAttachment()->getPath().'/'.$vignetteAttachment->getAttachment()->getFile();
    }

    /**
     * Get the resized vignette path, converting the file extension to _90x90.jpg.
     */
    private function getResizedVignettePath($vignetteAttachment): ?string
    {
        $file = $vignetteAttachment->getAttachment()->getFile();
        $resizedDirectory = $vignetteAttachment->getAttachment()->getPath().'/resized/';

        // Define the list of extensions that can be resized
        $extensionPatterns = ['.jpg', '.jpeg', '.png', '.webp'];

        // Find the first matching extension and replace it
        foreach ($extensionPatterns as $pattern) {
            if (str_contains((string) $file, $pattern)) {
                return $resizedDirectory.str_replace($pattern, '_90x90'.$pattern, $file);
            }
        }

        // Return null if no valid extension is found
        return null;
    }
}
