<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Uid\Uuid;

final readonly class UuidGenerator
{
    public static function generate(): string
    {
        return Uuid::v4()->toString();
    }
}
