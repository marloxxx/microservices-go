<?php

namespace App\Http\Controllers;

use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\Midtrans\CallbackService;

class PaymentCallbackController extends Controller
{
    private $client;
    private $headers;
    private $url;
    private $body;

    public function __construct()
    {
        parent::__construct();
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);
        $this->url = "localhost:8083/api/orders";
    }

    public function receive()
    {
        $callback = new CallbackService;


        if ($callback->isSignatureKeyVerified()) {
            $notification = $callback->getNotification();
            $order = $callback->getOrder();

            if ($callback->isSuccess()) {
                $this->client->request('PUT', $this->url . '/' . $order->id, [
                    'json' => [
                        'id' => $order->id,
                    'user_id' => $order->user_id,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'shipping_price' => $order->shipping_price,
                    'courier' => $order->courier,
                    'payment_status' => 2,
                    'snap_token' => $order->snap_token,
                    ]
                ]);
            }

            if ($callback->isExpire()) {
                $this->client->request('PUT', $this->url . '/' . $order->id, [
                    'json' => [
                        'id' => $order->id,
                    'user_id' => $order->user_id,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'shipping_price' => $order->shipping_price,
                    'courier' => $order->courier,
                    'payment_status' => 3,
                    'snap_token' => $order->snap_token,
                    ]
                ]);
            }

            if ($callback->isCancelled()) {
                $this->client->request('PUT', $this->url . '/' . $order->id, [
                    'json' => [
                        'id' => $order->id,
                    'user_id' => $order->user_id,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'shipping_price' => $order->shipping_price,
                    'courier' => $order->courier,
                    'payment_status' => 4,
                    'snap_token' => $order->snap_token,
                    ]
                ]);
                            }

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil diproses',
                ]);
        } else {
            return response()
                ->json([
                    'error' => true,
                    'message' => 'Signature key tidak terverifikasi',
                ], 403);
        }
    }
}
