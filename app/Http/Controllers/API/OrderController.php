<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\ResponseFormater;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
//    Order History
    public function orderHistory(): JsonResponse
    {
        try {

            $orders = Order::query()
                ->where('user_id', auth()->id())
                ->get();
            return ResponseFormater::responseData(
                $orders,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


//    User Create Order
    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|string|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'restaurant_id' => 'required|string|exists:users,id',
            'shipping_cost' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {

            $totalPrice = 0;
            foreach ($request->order_items as $item) {
                $product = Product::query()->find($item['product_id']);
                $totalPrice += $product->price * $item['quantity'];
            }

            $totalBill = $totalPrice + $request->shipping_cost;

            $order = Order::query()->create([
                'user_id' => auth()->id(),
                'restaurant_id' => $request->restaurant_id,
                'shipping_cost' => $request->shipping_cost,
                'total_bill' => $totalBill,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'shipping_address' => auth()->user()->address,
                'shipping_letlong' => auth()->user()->latitude . ',' . auth()->user()->longitude,
                'status' => 'pending',
            ]);

            foreach ($request->order_items as $item) {
                $product = Product::query()->find($item['product_id']);
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();
            return ResponseFormater::responseData(
                $order,
                Response::HTTP_CREATED
            );
        } catch (Exception $th) {
            DB::rollBack();
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function cancelOrder(Order $order): JsonResponse
    {
        try {
            $order->update([
                'status' => 'canceled',
            ]);
            return ResponseFormater::responseData(
                $order,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

//    Cancel Order

    /**
     * Update the specified resource in storage.
     */
    public function updatePurchaseStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,canceled',
        ]);

        try {

            $order->update([
                'status' => $request->status,
            ]);
            return ResponseFormater::responseData(
                $order,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

//    Get Order By Status for restaurant

    public function getOrderByStatusForRestaurant(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,canceled',
        ]);
        try {
            $orders = Order::query()
                ->where('restaurant_id', auth()->id())
                ->where('status', $request->status)
                ->get();
            return ResponseFormater::responseData(
                $orders,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

//    Update Order Status For Restaurant

    public function updateOrderStatusForRestaurant(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,ready_for_delivery,prepared',
        ]);
        try {
            $order->update([
                'status' => $request->status,
            ]);
            return ResponseFormater::responseData(
                $order,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

//    Get Order Status By Driver

    public function getOrderStatusByDriver(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,ready_for_delivery,prepared',
        ]);
        try {
            $orders = Order::query()
                ->where('driver_id', auth()->id())
                ->where('status', $request->status)
                ->get();
            return ResponseFormater::responseData(
                $orders,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

//    get order status ready for delivery
    public function getOrderStatusReadyForDelivery(Request $request): JsonResponse
    {
//        $request->validate([
//            'status' => 'required|string|in:pending,processing,completed,canceled,delivered,prepared',
//        ]);
        try {
            $orders = Order::query()
                ->with(['restaurant'])
                ->where('status', 'ready_for_delivery')
                ->get();
            return ResponseFormater::responseData(
                $orders,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

//    Update Status For Delivery
    public function updateStatusForDriver(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,on_the_way,delivered',
        ]);
        try {
            $order->update([
                'status' => $request->status,
            ]);
            return ResponseFormater::responseData(
                $order,
                Response::HTTP_OK
            );
        } catch (Exception $th) {
            return ResponseFormater::responseMessage(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
