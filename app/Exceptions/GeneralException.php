<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class GeneralException extends Exception
{
    protected $code = 422; // access property $code to set a default value
    public function report() // logic before exception render
    {

    }

    public function render($request)
    {
        return new JsonResponse( [
                'errors' => [
                    'message' => $this->getMessage(),
                ]
            ], $this->code);
    }
}
