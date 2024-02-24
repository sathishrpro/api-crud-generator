<?php

namespace App\API\Common\Presentation;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Throwable;

class APIErrorResponse implements Responsable
{
    public function __construct(
        private string $message,
        private ?Throwable $exception = null,
        private int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        private array $headers = []
    ) {
    }

    /**
     * @param  $request
     * @return \Symfony\Component\HttpFoundation\Response|void
     */
    public function toResponse($request)
    {
        $response = ['error' => $this->message];

        if (! is_null($this->exception) && (config('app.debug') && config('app.env') == 'local')) {
            $response['debug'] = [
                'message' => $this->exception->getMessage(),
                'file'    => $this->exception->getFile(),
                'line'    => $this->exception->getLine(),
                'trace'   => $this->exception->getTraceAsString()
            ];
        }
        return response()->json($response, $this->code, $this->headers);
    }
}
