<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PizzaController extends Controller
{
    /**
     * Get paginated pizzas with their types
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
        ]);

        $page = $request->input('page', 1);

        $pizzas = Pizza::latest()->with('pizzaType')->paginate(10)->appends(['page' => $page]);

        return response()->json([
            'pizzas' => $pizzas
        ]);
    }

    /**
     * Get a specific pizza with its type
     */
    public function show($id)
    {
        $pizza = Pizza::with('pizzaType')->findOrFail($id);
        
        return response()->json($pizza);
    }

    /**
     * Store a newly created pizza
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|max:100|unique:pizzas,slug',
            'pizza_type_id' => 'required|exists:pizza_types,pizza_type_id',
            'size' => 'required|string|in:S,M,L,XL,XXL',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $pizza = Pizza::create($request->validated());
        
        return response()->json([
            'message' => 'Pizza created successfully',
            'data' => $pizza->load('pizzaType')
        ], 201);
    }

    /**
     * Update the specified pizza
     */
    public function update(Request $request, $id)
    {
        $pizza = Pizza::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'slug' => 'sometimes|required|string|max:100|unique:pizzas,slug,' . $pizza->id,
            'pizza_type_id' => 'sometimes|exists:pizza_types,pizza_type_id',
            'size' => 'sometimes|string|in:S,M,L,XL,XXL',
            'price' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $pizza->update($request->all());
        
        return response()->json([
            'message' => 'Pizza updated successfully',
            'data' => $pizza->load('pizzaType')
        ]);
    }

    /**
     * Remove the specified pizza
     */
    public function destroy($id)
    {
        $pizza = Pizza::findOrFail($id);
        $pizza->delete();
        
        return response()->json([
            'message' => 'Pizza deleted successfully'
        ]);
    }
}