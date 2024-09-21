<?php

namespace App\Exceptions;

use Exception;

class MissingRelationException extends Exception
{
    public function __construct(string $message = "Missing required relation"){
        parent::__construct($message);
    }

    public function render($request){
        return response()->json([
            'error' => $this->getMessage()
        ], 422);
    }
}
