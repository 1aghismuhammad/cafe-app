<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\TableQrCode;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(string $token, Menu $menu)
    {
        $qrCode = $this->getActiveQrCode($token);

        if (! $menu->is_active || $menu->stock_status !== 'available') {
            return redirect()
                ->route('customer.order.table', $token)
                ->with('error', 'Menu tidak tersedia.');
        }

        $cartKey = $this->cartKey($token);
        $cart = session()->get($cartKey, []);

        $menuId = (string) $menu->id;

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
            $cart[$menuId]['subtotal'] = $cart[$menuId]['qty'] * $cart[$menuId]['price'];
        } else {
            $cart[$menuId] = [
                'menu_id' => $menu->id,
                'menu_name' => $menu->menu_name,
                'menu_code' => $menu->menu_code,
                'category_name' => $menu->category->category_name ?? '-',
                'price' => $menu->price,
                'qty' => 1,
                'subtotal' => $menu->price,
                'note' => '',
                'image_path' => $menu->image_path,
            ];
        }

        session()->put($cartKey, $cart);

        return redirect()
            ->route('customer.order.table', $token)
            ->with('success', $menu->menu_name . ' berhasil ditambahkan ke keranjang.');
    }

    public function show(string $token)
    {
        $qrCode = $this->getActiveQrCode($token);

        $cart = session()->get($this->cartKey($token), []);
        $cartSummary = $this->cartSummary($cart);

        return view('customer.cart', compact('qrCode', 'cart', 'cartSummary', 'token'));
    }

    public function increase(string $token, Menu $menu)
    {
        $this->getActiveQrCode($token);

        $cartKey = $this->cartKey($token);
        $cart = session()->get($cartKey, []);
        $menuId = (string) $menu->id;

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
            $cart[$menuId]['subtotal'] = $cart[$menuId]['qty'] * $cart[$menuId]['price'];
        }

        session()->put($cartKey, $cart);

        return redirect()
            ->route('customer.cart.show', $token)
            ->with('success', 'Jumlah menu berhasil ditambah.');
    }

    public function decrease(string $token, Menu $menu)
    {
        $this->getActiveQrCode($token);

        $cartKey = $this->cartKey($token);
        $cart = session()->get($cartKey, []);
        $menuId = (string) $menu->id;

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] -= 1;

            if ($cart[$menuId]['qty'] <= 0) {
                unset($cart[$menuId]);
            } else {
                $cart[$menuId]['subtotal'] = $cart[$menuId]['qty'] * $cart[$menuId]['price'];
            }
        }

        session()->put($cartKey, $cart);

        return redirect()
            ->route('customer.cart.show', $token)
            ->with('success', 'Jumlah menu berhasil dikurangi.');
    }

    public function remove(string $token, Menu $menu)
    {
        $this->getActiveQrCode($token);

        $cartKey = $this->cartKey($token);
        $cart = session()->get($cartKey, []);
        $menuId = (string) $menu->id;

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
        }

        session()->put($cartKey, $cart);

        return redirect()
            ->route('customer.cart.show', $token)
            ->with('success', 'Menu berhasil dihapus dari keranjang.');
    }

    public function updateNote(Request $request, string $token, Menu $menu)
    {
        $this->getActiveQrCode($token);

        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $cartKey = $this->cartKey($token);
        $cart = session()->get($cartKey, []);
        $menuId = (string) $menu->id;

        if (isset($cart[$menuId])) {
            $cart[$menuId]['note'] = $validated['note'] ?? '';
        }

        session()->put($cartKey, $cart);

        return redirect()
            ->route('customer.cart.show', $token)
            ->with('success', 'Catatan menu berhasil disimpan.');
    }

    private function getActiveQrCode(string $token): TableQrCode
    {
        $qrCode = TableQrCode::with(['restaurantTable.outlet'])
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->first();

        if (! $qrCode) {
            abort(404, 'QR meja tidak aktif atau tidak valid.');
        }

        return $qrCode;
    }

    private function cartKey(string $token): string
    {
        return 'cart_' . $token;
    }

    private function cartSummary(array $cart): array
    {
        $totalQty = 0;
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalQty += $item['qty'];
            $totalPrice += $item['subtotal'];
        }

        return [
            'total_qty' => $totalQty,
            'total_price' => $totalPrice,
        ];
    }
}