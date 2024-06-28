<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ResponseFormater;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function registerUser(Request $request): JsonResponse
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

    public function registerDriver(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:255'],
            'license_plate' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'max:255', 'image', 'mimes:jpeg,png,jpg'],
        ]);
        try {
            $user = User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'license_plate' => $request->license_plate,
                'role' => 'driver'
            ]);
            if (!empty($request->file('photo'))) {
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('profile-photos', $photoName);

                $user->update([
                    'profile_photo' => $photoName,
                ]);
            }
            return ResponseFormater::responseMessage(
                'Register success',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function registerRestaurant(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:255'],
            'restaurant_address' => ['required', 'string', 'max:255'],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_description' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'max:255', 'image', 'mimes:jpeg,png,jpg'],
        ]);
        try {
            $user = User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'restaurant_address' => $request->restaurant_address,
                'restaurant_name' => $request->restaurant_name,
                'restaurant_description' => $request->restaurant_description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'role' => 'restaurant',
            ]);
            if (!empty($request->file('photo'))) {
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('profile-photos', $photoName);

                $user->update([
                    'profile_photo' => $photoName,
                ]);
            }
            return ResponseFormater::responseMessage(
                'Register success',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
