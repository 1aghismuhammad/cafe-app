<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Roles that can be created/managed via this controller.
     * Admin creation is intentionally excluded for safety.
     */
    private array $allowedRoles = ['kasir', 'owner'];

    public function index()
    {
        $users = User::whereIn('role', $this->allowedRoles)
            ->with('outlets')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $outlets = Outlet::where('status', 'active')
            ->orderBy('outlet_name')
            ->get();

        return view('admin.users.create', compact('outlets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:kasir,owner'],
            'outlets' => ['nullable', 'array'],
            'outlets.*' => ['exists:outlets,id', \Illuminate\Validation\Rule::exists('outlets', 'id')->where('status', 'active')],
        ]);

        if ($validated['role'] === 'kasir' && empty($validated['outlets'])) {
            return back()->withErrors(['outlets' => 'Kasir harus ditugaskan ke minimal satu outlet.'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if ($validated['role'] === 'kasir' && ! empty($validated['outlets'])) {
            $user->outlets()->attach($validated['outlets']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat mengubah data admin.');
        }

        $outlets = Outlet::where('status', 'active')->get();
        return view('admin.users.edit', compact('user', 'outlets'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat mengubah data admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:kasir,owner'],
            'outlets' => ['nullable', 'array'],
            'outlets.*' => ['exists:outlets,id', \Illuminate\Validation\Rule::exists('outlets', 'id')->where('status', 'active')],
        ]);

        if ($validated['role'] === 'kasir' && empty($validated['outlets'])) {
            return back()->withErrors(['outlets' => 'Kasir harus ditugaskan ke minimal satu outlet.'])->withInput();
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (! empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        if ($validated['role'] === 'kasir') {
            $user->outlets()->sync($validated['outlets'] ?? []);
        } else {
            $user->outlets()->sync([]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        if (! in_array($user->role, $this->allowedRoles, true)) {
            abort(403, 'User ini tidak dapat dihapus melalui halaman ini.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
