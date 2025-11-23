<?php

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\ExpiredTokenException;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\MissingTokenException;
use App\Exceptions\UnauthorizedException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate;
use PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        LaravelServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.auth' => Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (UniqueConstraintViolationException $e, $request) {
            throw new AlreadyExistsException(); // ou ConflictException
        });

        $exceptions->render(function (UnauthorizedHttpException $e, $request) {
    
            $previous = $e->getPrevious();
    
            if ($previous instanceof TokenExpiredException) {
                throw new ExpiredTokenException();
            }
    
            if ($previous instanceof TokenInvalidException) {
                throw new InvalidTokenException();
            }
    
            if ($previous instanceof JWTException) {
                throw new MissingTokenException();
            }
    
            // fallback genérico
            throw new UnauthorizedException();
        });
    
    })->create();
