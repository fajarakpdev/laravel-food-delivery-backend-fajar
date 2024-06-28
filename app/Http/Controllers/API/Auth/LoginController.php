<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\LoginResource;
use App\ResponseFormater;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        try {
            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                $user->tokens()->delete();
//
                return ResponseFormater::responseData(
                    new LoginResource($user),
                    Response::HTTP_OK
                );
            } else {
                return ResponseFormater::responseMessage(
                    'Login failed',
                    Response::HTTP_UNAUTHORIZED
                );
            }
        } catch (Exception $e) {
            return ResponseFormater::responseMessage(
                'Login failed',
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
