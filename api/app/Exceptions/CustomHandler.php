<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class CustomHandler extends ExceptionHandler
{
    public function report123(Exception $exception)
    {
        parent::report($exception);
    }

    public function render123($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Database\QueryException) {
            return response()->json(['error' => 'Database error occurred'], 500);
        }

        return parent::render($request, $exception);
    }
}
