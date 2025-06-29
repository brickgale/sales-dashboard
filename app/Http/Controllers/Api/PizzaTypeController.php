<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PizzaType;
use Illuminate\Http\Request;

class PizzaTypeController extends Controller
{
    /**
     * Get paginated pizza types
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
        ]);

        $page = $request->input('page', 1);
        
        $query = PizzaType::latest();

        $pizza_types = [];
        if ($request->input('getAll') == 'true') {
            $pizza_types = $query->get();
        } else {
            $pizza_types = $query->paginate(10)->appends(['page' => $page]);
        }

        return response()->json([
            'pizza_types' => $pizza_types
        ]);
    }

    /**
     * Get a specific pizza type with its pizzas
     */
    public function show($id)
    {
        $pizzaType = PizzaType::with('pizzas')->findOrFail($id);
        return response()->json($pizzaType);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:pizza_types,slug',
            'category' => 'required|string|max:100',
            'ingredients' => 'required|string|max:1000',
        ]);

        $pizza_type = PizzaType::create($request->only([
            'name',
            'category',
            'ingredients',
        ]));

        return response()->json([
            'pizza_type' => $pizza_type,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'slug' => 'sometimes|required|string|max:100|unique:pizza_types,slug,' . $id,
            'category' => 'sometimes|required|string|max:100',
            'ingredients' => 'sometimes|required|string|max:1000',
        ]);

        $pizza_type = PizzaType::findOrFail($id);
        $pizza_type->update($request->only([
            'name',
            'category',
            'ingredients',
        ]));

        return response()->json([
            'pizza_type' => $pizza_type,
        ]);
    }

    public function destroy($id)
    {
        $pizza_type = PizzaType::findOrFail($id);
        $pizza_type->delete();

        return response()->json([
            'message' => 'Pizza type deleted successfully',
        ]);
    }
}
