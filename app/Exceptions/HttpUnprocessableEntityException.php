<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class HttpUnprocessableEntityException extends HttpResponseException
{
    public function __construct(string $error)
    {
        parent::__construct(
            response(
                ['error' => $error],
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
