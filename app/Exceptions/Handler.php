<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Exception|Throwable $e)
    {
        // API Handler
        if ($request->wantsJson()) {
            if($e instanceof ModelNotFoundException) {
                return response()->json(['error' => 'Requested model doesnot exist.Please create one'],404);
            }
            elseif($e instanceOf \Illuminate\Validation\ValidationException)
            {
               return response()->json(['error' => $e->errors()],400);
            }
            elseif ($e instanceof \Exception) {
                \Log::critical($e->getMessage() . ' in line ' . $e->getLine() . 'Stack trace' . $e->getTraceAsString() );
                return response()->json(['error' => "Something went wrong please contact support"],500);
            }

        }

        return parent::render($request, $e);

    }

}
