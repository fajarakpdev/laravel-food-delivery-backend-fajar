<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\ResponseFormater;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            if (auth()->user()->role === 'user') {
                return ResponseFormater::responseMessage('Not have restaurant', Response::HTTP_FORBIDDEN);
            }
            return ResponseFormater::responseData(
                ProductResource::collection(
                    Product::query()
                        ->whereBelongsTo(auth()->user(), 'user')
                        ->with(['user'])
                        ->get()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (auth()->user()->role === 'user') {
            return ResponseFormater::responseMessage('Not have restaurant', Response::HTTP_FORBIDDEN);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'is_favorite' => 'required|boolean',
            'image' => ['required', 'max:255', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        try {
            $product = Product::query()->create([
                'name' => $request->name ?? '',
                'description' => $request->description ?? '',
                'price' => $request->price ?? 0,
                'stock' => $request->stock ?? 0,
                'is_available' => $request->is_available ?? false,
                'is_favorite' => $request->is_favorite ?? false,
                'user_id' => auth()->user()->id ?? 0,
            ]);
            if (!empty($request->file('image'))) {
                $photo = $request->file('image');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('product', $photoName);

                $product->update([
                    'image' => $photoName,
                ]);
            }
            return ResponseFormater::responseMessage(
                'Product created',
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        if (auth()->user()->role === 'user') {
            return ResponseFormater::responseMessage('Not have restaurant', Response::HTTP_FORBIDDEN);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'is_favorite' => 'required|boolean',
//            'image' => ['required', 'max:255', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        try {
            $product->update([
                'name' => $request->name ?? '',
                'description' => $request->description ?? '',
                'price' => $request->price ?? 0,
                'stock' => $request->stock ?? 0,
                'is_available' => $request->is_available ?? false,
                'is_favorite' => $request->is_favorite ?? false,
                'user_id' => auth()->user()->id ?? 0,
            ]);
//            if (!empty($request->file('image'))) {
//                $photo = $request->file('image');
//                $photoName = time() . '.' . $photo->getClientOriginalExtension();
//                $photo->storeAs('product', $photoName);
//
//                $product->update([
//                    'image' => $photoName,
//                ]);
//            }
            return ResponseFormater::responseMessage(
                'Product updated',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        if (auth()->user()->role === 'user') {
            return ResponseFormater::responseMessage('Not have restaurant', Response::HTTP_FORBIDDEN);
        }
        try {
            return ResponseFormater::responseData(
                new ProductResource($product),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        if (auth()->user()->role === 'user') {
            return ResponseFormater::responseMessage('Not have restaurant', Response::HTTP_FORBIDDEN);
        }
        try {
            $product->delete();
            return ResponseFormater::responseMessage(
                'Product deleted',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return ResponseFormater::responseMessage($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
