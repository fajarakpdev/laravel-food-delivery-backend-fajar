<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\ResponseFormater;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLetLongController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);
        try {
            $request->user()->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            return ResponseFormater::responseMessage(
                'Update success',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
