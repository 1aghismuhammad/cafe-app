<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TableQrCode;
use App\Services\MidtransQrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(string $token)
    {
        $qrCode = $this->getActiveQrCode($token);

        $cart = session()->get($this->cartKey($token), []);

        if (empty($cart)) {
            return redirect()
                ->route('customer.cart.show', $token)
                ->with('error', 'Keranjang masih kosong.');
        }

        $cartSummary = $this->buildCartSummary($cart);

        if (empty($cartSummary['items'])) {
            return redirect()
                ->route('customer.cart.show', $token)
                ->with('error', 'Menu dalam keranjang tidak tersedia.');
        }

        return view('customer.checkout', compact('qrCode', 'cartSummary', 'token'));
    }

    public function store(Request $request, string $token)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $qrCode = $this->getActiveQrCode($token);

        $cart = session()->get($this->cartKey($token), []);

        if (empty($cart)) {
            return redirect()
                ->route('customer.cart.show', $token)
                ->with('error', 'Keranjang masih kosong.');
        }

        $cartSummary = $this->buildCartSummary($cart);

        if (empty($cartSummary['items'])) {
            return redirect()
                ->route('customer.cart.show', $token)
                ->with('error', 'Menu dalam keranjang tidak tersedia.');
        }

        $order = DB::transaction(function () use ($validated, $qrCode, $cartSummary) {
            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'table_qr_code_id' => $qrCode->id,
                'restaurant_table_id' => $qrCode->restaurant_table_id,
                'outlet_id' => $qrCode->restaurantTable->outlet_id,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_note' => $validated['customer_note'] ?? null,
                'total_amount' => $cartSummary['total_price'],
                'status' => 'awaiting_payment',
                'payment_status' => 'unpaid',
            ]);

            foreach ($cartSummary['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'menu_name' => $item['menu_name'],
                    'menu_price' => $item['price'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'item_note' => $item['note'] ?? null,
                ]);
            }

            app(MidtransQrisService::class)->createPayment($order);

            return $order;
        });

        session()->forget($this->cartKey($token));

        return redirect()->route('customer.payment.qris.show', [
            'token' => $token,
            'order' => $order->id,
        ]);
    }

    public function success(string $token, Order $order)
    {
        $qrCode = $this->getActiveQrCode($token);

        if ((int) $order->table_qr_code_id !== (int) $qrCode->id) {
            abort(404);
        }

        $order->load('items');

        return view('customer.order-success', compact('qrCode', 'order', 'token'));
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

    private function buildCartSummary(array $cart): array
    {
        $items = [];
        $totalQty = 0;
        $totalPrice = 0;

        foreach ($cart as $menuId => $cartItem) {
            $menu = Menu::with('category')
                ->where('id', $menuId)
                ->where('is_active', true)
                ->where('stock_status', 'available')
                ->first();

            if (! $menu) {
                continue;
            }

            $qty = (int) ($cartItem['qty'] ?? 1);

            if ($qty < 1) {
                continue;
            }

            $subtotal = $menu->price * $qty;

            $items[] = [
                'menu_id' => $menu->id,
                'menu_name' => $menu->menu_name,
                'menu_code' => $menu->menu_code,
                'category_name' => $menu->category->category_name ?? '-',
                'price' => $menu->price,
                'qty' => $qty,
                'subtotal' => $subtotal,
                'note' => $cartItem['note'] ?? '',
                'image_path' => $menu->image_path,
            ];

            $totalQty += $qty;
            $totalPrice += $subtotal;
        }

        return [
            'items' => $items,
            'total_qty' => $totalQty,
            'total_price' => $totalPrice,
        ];
    }

    private function generateOrderCode(): string
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . random_int(100, 999);
    }
}