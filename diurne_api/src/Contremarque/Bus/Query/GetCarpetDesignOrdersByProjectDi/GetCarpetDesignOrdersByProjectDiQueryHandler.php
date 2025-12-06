<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrdersByProjectDi;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\ImageAttachmentRepository;
use App\Contremarque\Service\ImageProvider;

// Import ImageProvider

final readonly class GetCarpetDesignOrdersByProjectDiQueryHandler implements QueryHandler
{
    // Add ImageProvider as a class dependency

    public function __construct(
        private CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private ImageAttachmentRepository   $imageAttachmentRepository,
        private ImageProvider               $imageProvider // Inject ImageProvider
    )
    {
        // Assign ImageProvider
    }

    public function __invoke(GetCarpetDesignOrdersByProjectDiQuery $query): GetCarpetDesignOrdersByProjectDiResponse
    {
        $carpetDesignOrders = $this->carpetDesignOrderRepository->findByContremarqueAndProjectDi(
            $query->getContremarqueId(),
            $query->getProjectDiId()
        );

        $formattedCarpetDesignOrders = [];

        foreach ($carpetDesignOrders as $index => $order) {
            $formattedCarpetDesignOrders[$index] = $order->toArray();

            $vignetteAttachment = $this->imageAttachmentRepository->findVignette($order);

            $formattedCarpetDesignOrders[$index]['vignette'] = '';
            $formattedCarpetDesignOrders[$index]['vignette_resized'] = '';

            if ($vignetteAttachment !== null) {
                $vignettePath = $this->imageProvider->getVignettePath($order);
                $resizedVignettePath = $this->imageProvider->getResizedVignettePath($order);

                $formattedCarpetDesignOrders[$index]['vignette'] = $vignettePath ?: '';
                $formattedCarpetDesignOrders[$index]['vignette_resized'] = $resizedVignettePath ?: '';
            }
        }

        return new GetCarpetDesignOrdersByProjectDiResponse($formattedCarpetDesignOrders);
    }
}
