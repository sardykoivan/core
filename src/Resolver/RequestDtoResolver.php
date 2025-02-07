<?php

declare(strict_types=1);

namespace App\Resolver;

use App\DTO\LoginRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

final class RequestDtoResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
       // return [];

        $type = $argument->getType();

        if (!$type || !class_exists($type) || !str_ends_with($type, 'RequestDto')) {
            return [];
        }

        try {
            $dto = $this->serializer->deserialize($request->getContent(), $type, 'json');
            // TODO: обработать все виды эксепшнов
        } catch (Throwable) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        return [$dto];
    }
}
