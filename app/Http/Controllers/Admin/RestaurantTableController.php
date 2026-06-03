<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantTableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::with('outlet')
            ->latest()
            ->get();

        return view('admin.restaurant-tables.index', compact('tables'));
    }

    public function create()
    {
        $outlets = Outlet::where('status', 'active')
            ->orderBy('outlet_name')
            ->get();

        return view('admin.restaurant-tables.create', compact('outlets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'exists:outlets,id'],
            'table_number' => ['required', 'string', 'max:50'],
            'table_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'table_code')
                    ->where(fn ($query) => $query->where('outlet_id', $request->outlet_id)),
            ],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        RestaurantTable::create($validated);

        return redirect()
            ->route('admin.restaurant-tables.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(RestaurantTable $restaurantTable)
    {
        $outlets = Outlet::where('status', 'active')
            ->orderBy('outlet_name')
            ->get();

        return view('admin.restaurant-tables.edit', compact('restaurantTable', 'outlets'));
    }

    public function update(Request $request, RestaurantTable $restaurantTable)
    {
        $validated = $request->validate([
            'outlet_id' => ['required', 'exists:outlets,id'],
            'table_number' => ['required', 'string', 'max:50'],
            'table_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'table_code')
                    ->where(fn ($query) => $query->where('outlet_id', $request->outlet_id))
                    ->ignore($restaurantTable->id),
            ],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $restaurantTable->update($validated);

        return redirect()
            ->route('admin.restaurant-tables.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(RestaurantTable $restaurantTable)
    {
        $restaurantTable->delete();

        return redirect()
            ->route('admin.restaurant-tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }
}