<?php

use App\Domain\Exception\NotFoundException;
use App\Domain\UseCases\Auth\AuthenticateUserLoginUseCase;
use App\Domain\UseCases\Auth\InputLoginUserDto;
use App\Domain\UseCases\User\InputCreateUserDto;
use App\Domain\UseCases\User\RegisterUserUseCase;
use App\Http\Middleware\AuthenticateJWTiddleware;
use App\Infra\EncryptPasswordLaravelHash;
use App\Infra\FirebaseJWTToken;
use App\Infra\SanctumToken;
use App\Infra\UserGatewayDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/user', function (Request $request): JsonResponse {
    $user = $request->get('user');
    return new JsonResponse(['user' => $user]);
})->middleware(AuthenticateJWTiddleware::class);

Route::post('/login', function (Request $request): JsonResponse {
    try {
        $data = $request->only(['email', 'password']);
        $useCase = new AuthenticateUserLoginUseCase(new EncryptPasswordLaravelHash(), new FirebaseJWTToken(), new UserGatewayDatabase());
        $response = $useCase->execute(new InputLoginUserDto(...$data));
        return new JsonResponse(['token' => $response->token, 'user' => $response->user]);
    } catch (NotFoundException $e) {
        return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return new JsonResponse(['error' => 'Server error'], 500);
    }
});

Route::post('/user', function (Request $request) {
    try {
        $userData = $request->only(['name', 'email', 'password']);
        $useCase = new RegisterUserUseCase(new EncryptPasswordLaravelHash(), new UserGatewayDatabase());
        $response = $useCase->execute(new InputCreateUserDto(...$userData));
        return new JsonResponse(['data' => $response]);
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return new JsonResponse(['error' => 'Server error'], 500);
    }
});
