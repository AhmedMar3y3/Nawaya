<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class AuthService
{
    public function __construct(
        private readonly FirebaseAuth $firebaseAuth
    ) {}

    public function register(array $data): array
    {
        try {
            $existingUser = User::where('email', $data['email'])
                ->orWhere('phone', $data['phone'])
                ->first();

            if ($existingUser) {
                return [
                    'success' => false,
                    'error' => 'user_already_exists'
                ];
            }

            $user = User::create([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country_id' => $data['country_id'],
                'is_active' => true,
            ]);

            return [
                'success' => true,
                'user' => $user
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'failed'
            ];
        }
    }

    public function login(array $data): array
    {
        try {
            $user = User::where('email', $data['email'])
                ->where('phone', $data['phone'])
                ->first();

            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'invalid_credentials'
                ];
            }

            if (!$user->is_active) {
                return [
                    'success' => false,
                    'error' => 'account_inactive'
                ];
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return [
                'success' => true,
                'user' => $user,
                'token' => $token
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'login_failed'
            ];
        }
    }

    public function loginWithGoogle(array $data): array
    {
        try {
            $idToken = $data['id_token'];
            $tokenData = null;

            try {
                $verifiedToken = $this->firebaseAuth->verifyIdToken($idToken);
                $tokenData = [
                    'uid' => $verifiedToken->claims()->get('sub'),
                    'email' => $verifiedToken->claims()->get('email'),
                    'name' => $verifiedToken->claims()->get('name') ?? $verifiedToken->claims()->get('display_name') ?? '',
                ];
            } catch (FailedToVerifyToken $e) {
                $tokenData = $this->verifyGoogleOAuthToken($idToken);
                
                if (!$tokenData) {
                    throw new \Exception('Token verification failed');
                }
            }

            $email = $tokenData['email'];
            $uid = $tokenData['uid'];
            $name = $tokenData['name'] ?? '';

            $user = User::where('email', $email)->first();

            if (!$user) {
                $uniquePhone = 'google_' . $uid . '_' . time();
                
                $user = User::create([
                    'full_name' => $name ?: 'User',
                    'email' => $email,
                    'phone' => $uniquePhone,
                    'country_id' => 101,
                    'is_active' => true,
                ]);
            }

            if (!$user->is_active) {
                return [
                    'success' => false,
                    'error' => 'account_inactive'
                ];
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return [
                'success' => true,
                'user' => $user,
                'token' => $token
            ];
        } catch (FailedToVerifyToken $e) {
            return [
                'success' => false,
                'error' => 'invalid_id_token'
            ];
        } catch (\Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'invalid_id_token'
            ];
        }
    }

    private function verifyGoogleOAuthToken(string $idToken): ?array
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('https://oauth2.googleapis.com/tokeninfo', [
                'query' => ['id_token' => $idToken]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!$data || !isset($data['email'])) {
                return null;
            }

            return [
                'uid' => $data['sub'] ?? $data['user_id'] ?? '',
                'email' => $data['email'],
                'name' => $data['name'] ?? '',
            ];
        } catch (\Exception $e) {
            Log::error('Google OAuth token verification failed: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyFirebaseToken(array $data): array
    {
        return $this->loginWithGoogle($data);
    }
}

