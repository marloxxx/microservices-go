<?php

namespace App\Services\Midtrans;

use App\Models\User;
use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $order;

    public function __construct($order)
    {
        parent::__construct();

        $this->order = $order;
    }

    public function getSnapToken()
    {
        $order = $this->order;
        $order_items = $order->OrderItems;
        $item_details = [];
        foreach ($order_items as $key => $order_item) {
            $item_details[] = [
                'id' => $order_item->id,
                'price' => $order_item->price,
                'quantity' => $order_item->quantity,
                'name' => $order_item->product_name,
            ];
        }
        $customer = User::find($order->user_id);
        $params = [
            'transaction_details' => [
                'order_id' => $this->order->code,
                'gross_amount' => $this->order->total_price,
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
