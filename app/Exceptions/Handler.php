<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{

    protected $status;

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
    public function register()
    {
        //for not object found exception
        $this->renderable(function (NotFoundHttpException $e, $request) {
            $this->status = Response::HTTP_NOT_FOUND;
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'result' => false,
                    'status' => $this->status,
                    "message" => "Object not found"
                ], $this->status);
            }
        });

        //sanctum authentication exception
        $this->renderable(function (AuthenticationException $e, $request) {
            $this->status = Response::HTTP_UNAUTHORIZED;
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'result' => false,
                    'status' => $this->status,
                    'message' => "Unauthenticated.",
                ], $this->status);
            }
        });

        //user has no permission exception
        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            $this->status = Response::HTTP_FORBIDDEN;
            if ($request->wantsJson()) {
                return response()->json([
                    'result' => false,
                    'status' => $this->status,
                    'message' => "This action is unauthorized.",
                ], $this->status);
            }
        });

        // for query exception
        $this->renderable(function (QueryException $e, $request) {
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'result' => false,
                    'status' => $this->status,
                    'message' => "Internal server errors.",
                    'error' => $e->getMessage()
                ], $this->status);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            $this->status = Response::HTTP_METHOD_NOT_ALLOWED;
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'result' => false,
                    'status' => $this->status,
                    'message' => "Invalid route.",
                ], $this->status);
            }
        });

        // for all other exception
        $this->renderable(function (Throwable $e, $request) {
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($request->wantsJson() || $request->is('api/*')) {
                $response = [
                    'result' => false,
                    'status' => $this->status,
                    'message' => "Internal server errors."
                ];

                if (config('app.debug')) {
                    $response['error'] =  $e->getMessage() . ". on line " . $e->getLine() . " in file " . $e->getFile();
                } else {
                    $response['error'] = $e->getMessage();
                }

                return response()->json($response, $this->status);
            }
        });
    }
}
