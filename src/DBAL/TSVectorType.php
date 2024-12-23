<?php

namespace App\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TSVectorType extends Type
{

    const TSVECTOR = 'tsvector';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TSVECTOR';
    }

    public function getName(): string
    {
        return self::TSVECTOR;
    }
}