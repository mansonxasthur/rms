<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

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
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->path() === '/') {
                return response()->json(['code' => Response::HTTP_OK, 'message' => sprintf('Welcome to %s portal api.', Config::get('app.name'))]);
            }

            return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Sometimes the most scenic roads in life are the detours you didn\'t mean to take. In your case is not, you are lost man!!'], Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (AuthenticationException $e) {
            return response()->json(['code' => Response::HTTP_UNAUTHORIZED, 'message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        });
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|Response|null
    {
        if ($e->response) {
            return $e->response;
        }

        return $this->invalidJson($request, $e);
    }
}
