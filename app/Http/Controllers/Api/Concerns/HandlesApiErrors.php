<?php

namespace App\Http\Controllers\Api\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

trait HandlesApiErrors
{
    private function ok(array $payload = [], int $status = 200): JsonResponse
    {
        return response()->json($payload, $status);
    }

    private function handle(Throwable $e): JsonResponse
    {
        // Validation errors (422)
        if ($e instanceof ValidationException) {
            return response()->json([
                'ok' => false,
                'error' => [
                    'type' => 'validation_error',
                    'message' => 'Validation failed.',
                    'fields' => $e->errors(),
                ],
            ], 422);
        }

        // abort(422/404/403...) etc.
        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();

            return response()->json([
                'ok' => false,
                'error' => [
                    'type' => 'http_error',
                    'message' => $e->getMessage() ?: 'Request failed.',
                ],
            ], $status);
        }

        // Default (500)
        return response()->json([
            'ok' => false,
            'error' => [
                'type' => 'server_error',
                'message' => config('app.debug') ? $e->getMessage() : 'Unexpected server error.',
            ],
        ], 500);
    }
}
