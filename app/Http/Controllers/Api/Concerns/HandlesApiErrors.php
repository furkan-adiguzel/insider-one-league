<?php

namespace App\Http\Controllers\Api\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

trait HandlesApiErrors
{
    private function ok(mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json(
            $this->envelope(true, $data),
            $status,
            [],
            JSON_PRETTY_PRINT
        );
    }

    private function handle(Throwable $e): JsonResponse
    {
        $this->logException($e);

        $status  = 500;
        $type    = 'server_error';
        $message = config('app.debug') ? $e->getMessage() : 'Unexpected server error.';
        $fields  = null;

        if ($e instanceof ValidationException) {
            $status  = 422;
            $type    = 'validation_error';
            $message = 'Validation failed.';
            $fields  = $e->errors();
        }

        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            $type   = 'http_error';

            $message = config('app.debug')
                ? ($e->getMessage() ?: 'Request failed.')
                : 'Request failed.';
        }

        return response()->json(
            $this->envelope(false, null, [
                'type'    => $type,
                'message' => $message,
                'code'    => $status,
                'fields'  => $fields,
            ]),
            $status,
            [],
            JSON_PRETTY_PRINT
        );
    }

    private function envelope(bool $ok, mixed $data = null, array $error = null): array
    {
        return [
            'ok' => $ok,
            'data' => $data,
            'error' => $error,
            'meta' => [
                'timestamp'  => now()->toISOString(),
                'request_id' => request()->header('X-Request-ID') ?? Str::uuid()->toString(),
                'version'    => config('app.version', '1.0'),
            ],
        ];
    }

    private function logException(Throwable $e): void
    {
        Log::error('API Exception', [
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
            'url'       => request()->fullUrl(),
            'method'    => request()->method(),
            'trace'     => config('app.debug') ? $e->getTraceAsString() : null,
        ]);
    }
}
