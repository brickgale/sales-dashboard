<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Get paginated orders
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
        ]);

        $page = $request->input('page', 1);
        
        $orders = Order::with('orderDetails')
            ->latest()
            ->paginate(10)->appends(['page' => $page]);

        return response()->json([
            'orders' => $orders
        ]);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::create($request->validated());
        
        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order->load('orderDetails')
        ], 201);
    }

    /**
     * Get a specific order with its details
     */
    public function show($id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);
        
        return response()->json($order);
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|date',
            'time' => 'sometimes|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->all());
        
        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $order->load('orderDetails')
        ]);
    }

    /**
     * Remove the specified order
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        
        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}