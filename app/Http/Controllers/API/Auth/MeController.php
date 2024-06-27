<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeResource;
use App\ResponseFormater;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            return ResponseFormater::responseData(
                new MeResource($request->user()),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
