<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Support\Exceptions\ApplicationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // dd($e);

           
            //
        });

        // $this->renderable(function (ValidationException $exception, $request) {
        //     if(request()->ajax()){
        //         return response()->json(array(
        //             'notification'=>ReturnValidationNotification($exception->getErrors())
        //         ));
        //     }
        //     return back()->withInput()->withErrors($exception->getErrors());
        // });

        $this->renderable(function (ValidationException $exception, $request) {

            if(request()->ajax()){
                return response()->json([
                    'notification' => ReturnValidationNotification($exception->validator->errors())
                ]);
            }
        });

        $this->renderable(function (ApplicationException $exception, $request) {
            if(request()->ajax()){
                return response()->json(array(
                    'notification'=>ReturnNotification(['info'=> $exception->getMessage()])
                ));
            }
            return back()->withInput()->with(['info'=> $exception->getMessage()]);
        });

        $this->renderable(function(Exception $e, $request){
            if(request()->expectsJson() || request()->ajax()){
                return response()->json(array(
                    'notification'=>ReturnNotification(['info'=> $e->getMessage()])
                ));
            }
        });
    }
}
