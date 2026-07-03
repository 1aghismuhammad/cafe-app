<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\MidtransQrisService;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request, MidtransQrisService $midtransQrisService)
    {
        $midtransQrisService->handleNotification($request->all());

        return response()->json([
            'message' => 'Notification processed.',
        ]);
    }
}