<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse([
                'error' => $exception->getMessage()
            ], $exception->getStatusCode());

            foreach ($exception->getHeaders() as $key => $value) {
                $response->headers->set($key, $value);
            }

            $event->setResponse($response);
        }
    }
}
