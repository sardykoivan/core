<?php

declare(strict_types=1);

namespace App\Exception\Http\User;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class UserAuthenticationException extends HttpException
{
}
