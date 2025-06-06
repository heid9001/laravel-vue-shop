<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(
            Order::with(['user', 'orderItems.product'])
                ->latest()
                ->paginate(10)
        );
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $request->total,
                'status' => OrderStatus::New
            ]);

            foreach ($request->orderItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty']
                ]);
            }

            return response()->json([
                'message' => 'Заказ успешно создан',
                'order' => $order->load('orderItems.product')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Доступ запрещен'
            ], 403);
        }

        return response()->json($order->load('items.product'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return new OrderResource($order->load(['user', 'orderItems.product']));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Доступ запрещен'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:new,complete'
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Статус заказа обновлен',
            'order' => $order
        ]);
    }
}
