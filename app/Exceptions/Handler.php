<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        });

        $this->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found.',
            ], 404);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'The requested resource was not found.',
            ], 404);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'message' => 'Method not allowed.',
            ], 405);
        });

        $this->renderable(function (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        });

        $this->renderable(function (AuthorizationException $e) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        });

        $this->renderable(function (Throwable $e) {
            if (app()->environment('production')) {
                return response()->json([
                    'message' => 'Server Error',
                ], 500);
            }
        });
    }
} 