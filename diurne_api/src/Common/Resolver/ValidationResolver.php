<?php

declare(strict_types=1);

namespace App\Common\Resolver;

use App\Common\DTO\BaseDto;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[ValueResolver(name: 'app.validation_resolver')]
#[AutoconfigureTag('controller.value_resolver', ['priority' => 100])]
class ValidationResolver implements ValueResolverInterface
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!is_subclass_of($argument->getType(), BaseDto::class)) {
            return [];
        }

        $dtoClass = $argument->getType();
        $payload = $request->toArray();
        $object = new $dtoClass(...$payload);

        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            return [$this->handleValidationErrors($errors)];
        }

        return [$object];
    }

    private function handleValidationErrors(ConstraintViolationListInterface $errors): JsonResponse
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        return new JsonResponse([
            'code' => 400,
            'message' => 'Validation failed',
            'errors' => $errorMessages,
        ], 400);
    }
}
