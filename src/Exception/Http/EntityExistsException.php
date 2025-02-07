<?php

declare(strict_types=1);

namespace App\Exception\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class EntityExistsException extends HttpException
{
}
