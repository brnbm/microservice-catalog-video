<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Core\Domain\Exception\NotFoundDomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundDomainException)
            return $this->showError($exception->getMessage(), Response::HTTP_NOT_FOUND);

        return parent::render($request, $exception);
    }

    private function showError(String $message, int $statusCode)
    {
        return response()->json([
            'message' => $message
        ], $statusCode);
    }
}
