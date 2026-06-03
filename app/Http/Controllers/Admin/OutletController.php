<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::latest()->get();

        return view('admin.outlets.index', compact('outlets'));
    }

    public function create()
    {
        return view('admin.outlets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outlet_name' => ['required', 'string', 'max:255'],
            'outlet_code' => ['required', 'string', 'max:50', 'unique:outlets,outlet_code'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'open_time' => ['nullable'],
            'close_time' => ['nullable'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Outlet::create($validated);

        return redirect()
            ->route('admin.outlets.index')
            ->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function edit(Outlet $outlet)
    {
        return view('admin.outlets.edit', compact('outlet'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'outlet_name' => ['required', 'string', 'max:255'],
            'outlet_code' => ['required', 'string', 'max:50', 'unique:outlets,outlet_code,' . $outlet->id],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'open_time' => ['nullable'],
            'close_time' => ['nullable'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $outlet->update($validated);

        return redirect()
            ->route('admin.outlets.index')
            ->with('success', 'Outlet berhasil diperbarui.');
    }

    public function destroy(Outlet $outlet)
    {
        $outlet->delete();

        return redirect()
            ->route('admin.outlets.index')
            ->with('success', 'Outlet berhasil dihapus.');
    }
}