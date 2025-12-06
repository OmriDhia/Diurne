<?php

namespace App\Contremarque\Bus\Command\Sample;

use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Repository\ImageRepository;
use App\Contremarque\Repository\SampleRepository;

class AttachImageToSampleCommandHandler implements CommandHandler
{
    public function __construct(private readonly SampleRepository $sampleRepository, private readonly ImageRepository  $imageRepository)
    {
    }

    public function __invoke(AttachImageToSampleCommand $command): array
    {
        $errors = [];

        $sample = $this->sampleRepository->find($command->getSampleId());
        if (null === $sample) {
            $errors[] = 'Sample not found';
        }

        $image = $this->imageRepository->find($command->getImageId());
        if (null === $image) {
            $errors[] = 'Image not found';
        }

        if ($sample->getImages()->contains($image)) {
            $errors[] = 'Image already attached to the sample';
        }
        if (null !== $image->getSample()) {
            $errors[] = 'Image already attached to another sample';
        }
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
                'data' => null
            ];
        }
        $sample->addImage($image);
        $this->sampleRepository->persist($sample);
        $this->sampleRepository->flush();

        return [
            'success' => true,
            'errors' => [],
            'data' => new SampleResponse($sample),
        ];
    }
}
