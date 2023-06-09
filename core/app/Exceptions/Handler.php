<?php

namespace App\Exceptions;

use App\Helpers\MessageHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
            //
        });
//        $this->renderable(function (NotFoundHttpException $e, $request) {
//                return response()->failed(404,'آدرس درخواستی شما وجود ندارد');
//        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()){
            return response()->failed(401,MessageHelper::Translate('unauthenticated'));
        }
        return parent::unauthenticated($request, $exception); // TODO: Change the autogenerated stub
    }

    public function render($request, Throwable $e)
    {
        if($request->expectsJson()){
            if($e instanceof NotFoundHttpException){
                return response()->failed(404,'آدرس درخواستی شما وجود ندارد');
            }
            if($e instanceof AuthorizationException){
                return response()->failed(403,'شما مجاز به دسترسی به این قسمت نمی باشید');
            }
        }
        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }
}
