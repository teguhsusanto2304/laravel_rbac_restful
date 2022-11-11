<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->json([
                'status'  => Response::HTTP_FORBIDDEN,
                'message' => 'Forbidden',
                
            ],Response::HTTP_FORBIDDEN);
        });
    }
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'status'  => Response::HTTP_NOT_FOUND,
                'message' => 'Not Found',
                
            ],Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                'status'  => Response::HTTP_UNAUTHORIZED,
                'message' => 'Invalid Token',
                
            ],Response::HTTP_UNAUTHORIZED);
        }
        return parent::render($request, $exception);
    }

    
}
