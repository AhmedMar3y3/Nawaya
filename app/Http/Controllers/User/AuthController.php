<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Services\User\AuthService;
use App\Http\Resources\Auth\UserAuthResource;
use App\Http\Requests\API\Auth\LoginUserRequest;
use App\Http\Requests\API\Auth\RegisterUserRequest;
use App\Http\Requests\API\Auth\VerifyFirebaseTokenRequest;

class AuthController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function register(RegisterUserRequest $request)
    {
        $result = $this->authService->register($request->validated());
        if (! ($result['success'] ?? false)) {
            $key = $result['error'] ?? 'invalid_data';
            return $this->failureResponse(__('messages.' . $key));
        }
        
        $token = $result['user']->createToken('auth-token')->plainTextToken;
        
        return $this->successWithDataResponse(UserAuthResource::make($result['user'])->setToken($token));
    }

    public function login(LoginUserRequest $request)
    {
        $result = $this->authService->login($request->validated());
        if (! ($result['success'] ?? false)) {
            $key = $result['error'] ?? 'login_failed';
            return $this->failureResponse(__('messages.' . $key));
        }
        
        return $this->successWithDataResponse(UserAuthResource::make($result['user'])->setToken($result['token']));
    }

    public function loginWithGoogle(VerifyFirebaseTokenRequest $request)
    {
        $result = $this->authService->loginWithGoogle($request->validated());
        if (! ($result['success'] ?? false)) {
            $key = $result['error'] ?? 'invalid_id_token';
            return $this->failureResponse(__('messages.' . $key));
        }
        
        return $this->successWithDataResponse(UserAuthResource::make($result['user'])->setToken($result['token']));
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse(__('messages.logged_out'));
    }
}
