<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ResponseFormater;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:255'],
        ]);
        try {
            User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
            ]);
            return ResponseFormater::responseMessage(
                'Register success',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function registerDriver(Request $request)
    {

    }

    public function registerRestaurant(Request $request)
    {

    }
}
