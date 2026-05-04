<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutOrderRequest;
use App\Models\Order;
use App\Services\CheckoutOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutOrderController extends Controller
{
    public function store(StoreCheckoutOrderRequest $request, CheckoutOrderService $checkout): JsonResponse
    {
        try {
            $order = $checkout->placeOrder($request->validated(), $request->user());

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'redirect' => URL::temporarySignedRoute(
                    'order.thanks',
                    now()->addDays(14),
                    ['order' => $order->id]
                ),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => __('Unable to complete your order. Please try again.'),
            ], 500);
        }
    }

    public function thanks(Order $order): View
    {
        $order->loadMissing(['items']);

        return view('order-thanks', compact('order'));
    }
}
