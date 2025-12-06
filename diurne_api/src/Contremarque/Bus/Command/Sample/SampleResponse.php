<?php

namespace App\Contremarque\Bus\Command\Sample;

use App\Common\Bus\Query\QueryResponse;
use DateTimeInterface;
use App\Contremarque\Entity\Sample;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "SampleResponse",
    title: "Sample Response",
    description: "Response object for a Sample entity",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "carpetDesignOrder", type: "object", properties: [
            new OA\Property(property: "id", type: "integer", nullable: true, example: 1),
            new OA\Property(property: "projectDi", type: "integer", nullable: true, example: 1),
            new OA\Property(property: "location", type: "object", properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
            ], nullable: true),
            new OA\Property(property: "status", type: "object", properties: [
                new OA\Property(property: "id", type: "integer", example: 2),
            ], nullable: true),
            new OA\Property(property: "designers", type: "array", items: new OA\Items(type: "object", properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
            ])),
            new OA\Property(property: "carpetSpecification", type: "object", nullable: true),
            new OA\Property(property: "customerInstruction", type: "object", nullable: true, properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "instruction", type: "string", example: "Follow these instructions"),
            ]),
            new OA\Property(property: "variation", type: "string", nullable: true, example: "Variation 1"),
            new OA\Property(property: "variationImageReference", type: "string", nullable: true, example: "Ref123"),
            new OA\Property(property: "transmition_date", type: "string", format: "date-time", nullable: true, example: "2025-03-17T12:00:00+0000"),
        ]),
        new OA\Property(property: "location", type: "integer", example: 1),
        new OA\Property(property: "collection", type: "integer", nullable: true, example: null),
        new OA\Property(property: "model", type: "integer", nullable: true, example: null),
        new OA\Property(property: "status", type: "integer", example: 2),
        new OA\Property(property: "quality", type: "integer", example: 3),
        new OA\Property(property: "images", type: "array", items: new OA\Items(type: "integer"), example: [1, 2]),
        new OA\Property(property: "attachments", type: "array", items: new OA\Items(type: "integer"), example: [3, 4]),
        new OA\Property(property: "diCommandNumber", type: "string", example: "SAMPLE001"),
        new OA\Property(
            property: "dimension",
            type: "object",
            properties: [
                new OA\Property(property: "width", type: "string", example: "100.5"),
                new OA\Property(property: "height", type: "string", example: "200.75"),
            ]
        ),
        new OA\Property(property: "rn", type: "string", nullable: true, example: "RN123"),
        new OA\Property(property: "transmissionDate", type: "string", format: "date-time", nullable: true, example: "2025-03-17T12:00:00+0000"),
        new OA\Property(property: "customerComment", type: "string", nullable: true, example: "Initial comment"),
        new OA\Property(property: "createdAt", type: "string", format: "date-time", example: "2025-03-17T12:00:00+0000"),
        new OA\Property(property: "updatedAt", type: "string", format: "date-time", nullable: true, example: "2025-03-18T12:00:00+0000"),
    ]
)]
class SampleResponse implements QueryResponse
{
    public function __construct(
        private readonly Sample $sample
    ) {}

    public function getSample(): Sample
    {
        return $this->sample;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->sample->getId(),
            'carpetDesignOrder' => $this->sample->getCarpetDesignOrder() ? $this->sample->getCarpetDesignOrder()->toArray() : null,
            'location' => $this->sample->getLocation()->toArray(),
            'collection' => $this->sample->getCollection() ? $this->sample->getCollection()->toArray() : null,
            'model' => $this->sample->getModel() ? $this->sample->getModel()->toArray() : null,
            'status' => $this->sample->getStatus()->getId(),
            'quality' => $this->sample->getQuality()->toArray() ? $this->sample->getQuality()->toArray() : null,
            'images' => array_map(fn($image) => $image->getId(), $this->sample->getImages()->toArray()),
            'attachments' => array_map(fn($attachment) => $attachment->getId(), $this->sample->getAttachments()->toArray()),
            'diCommandNumber' => $this->sample->getDiCommandNumber(),
            'dimension' => $this->sample->getDimension()->toArray(),
            'rn' => $this->sample->getRn(),
            'transmissionDate' => $this->sample->getTransmissionDate() ? $this->sample->getTransmissionDate()->format(DateTimeInterface::ISO8601) : null,
            'customerComment' => $this->sample->getCustomerComment(),
            'createdAt' => $this->sample->getCreatedAt() ? $this->sample->getCreatedAt()->format(DateTimeInterface::ISO8601) : null,
            'updatedAt' => $this->sample->getUpdatedAt() ? $this->sample->getUpdatedAt()->format(DateTimeInterface::ISO8601) : null,
        ];
    }
}
