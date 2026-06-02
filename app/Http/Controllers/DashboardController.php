<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function redirect(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }

        abort(403, 'Role tidak dikenali.');
    }

    public function admin()
    {
        return view('admin.dashboard');
    }

    public function kasir()
    {
        return view('kasir.dashboard');
    }

    public function owner()
    {
        return view('owner.dashboard');
    }
}