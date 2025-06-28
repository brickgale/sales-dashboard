<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    /**
     * Get paginated order details with their orders and pizzas
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
        ]);

        $page = $request->input('page', 1);
        
        $orderDetails = OrderDetail::with(['order', 'pizza'])
            ->latest()
            ->paginate(10)->appends(['page' => $page]);

        return response()->json([
            'order_details' => $orderDetails
        ]);
    }

    /**
     * Store a newly created order detail
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_id',
            'pizza_id' => 'required|exists:pizzas,pizza_id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $orderDetail = OrderDetail::create($request->validated());
        
        return response()->json([
            'message' => 'Order detail created successfully',
            'data' => $orderDetail->load(['order', 'pizza'])
        ], 201);
    }

    /**
     * Get a specific order detail with its order and pizza
     */
    public function show($id)
    {
        $orderDetail = OrderDetail::with(['order', 'pizza'])->findOrFail($id);
        
        return response()->json($orderDetail);
    }

    /**
     * Update the specified order detail
     */
    public function update(Request $request, $id)
    {
        $orderDetail = OrderDetail::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'order_id' => 'sometimes|exists:orders,order_id',
            'pizza_id' => 'sometimes|exists:pizzas,pizza_id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $orderDetail->update($request->all());
        
        return response()->json([
            'message' => 'Order detail updated successfully',
            'data' => $orderDetail->load(['order', 'pizza'])
        ]);
    }

    /**
     * Remove the specified order detail
     */
    public function destroy($id)
    {
        $orderDetail = OrderDetail::findOrFail($id);
        $orderDetail->delete();
        
        return response()->json([
            'message' => 'Order detail deleted successfully'
        ]);
    }
}