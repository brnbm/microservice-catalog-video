<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Core\Domain\Exception\NotFoundDomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(
            fn(NotFoundDomainException $e, $request) =>
            response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND)
        );

        $this->renderable(
            fn(Throwable $e, $request) =>
            response()->json([
                'message' => 'Internal server error.',
                'exception' => config('app.debug') ? $e->getMessage() : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR)
        );
    }
}
