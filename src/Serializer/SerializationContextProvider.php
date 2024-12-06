<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class SerializationContextProvider
{
    public function getContext(): array
    {
        return [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                return $object->getId();
            },
        ];
    }
}