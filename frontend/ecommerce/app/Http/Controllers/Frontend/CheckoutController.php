<?php

namespace App\Http\Controllers\Frontend;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    private $client;
    private $headers;
    private $url;
    private $body;

    public function __construct()
    {
        SEOMeta::setTitleDefault(getSettings('site_name'));
        parent::__construct();
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'key' => '43a6fe54ef60d48b60a00d387b08bded'
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);
        $this->url = "localhost:8083/api/orders";
    }

    private function setMeta(string $title)
    {
        SEOMeta::setTitle($title);
        OpenGraph::setTitle(SEOMeta::getTitle());
        JsonLd::setTitle(SEOMeta::getTitle());
    }

    public function index(){
        try {
            $cities = json_decode($this->client->request('GET', 'https://api.rajaongkir.com/starter/city', [
                'headers' => [
                    'key' => '43a6fe54ef60d48b60a00d387b08bded'
                ]
            ])->getBody()->getContents())->rajaongkir->results;
        } catch (\Exception $e) {
            $cities = [];
        }
        try{
            $carts = json_decode($this->client->request('GET', 'localhost:8082/api/carts/?user_id=' . auth()->user()->id)->getBody()->getContents())->data;

        }catch(\Exception $e){
            $carts = [];
        }
        try{
            $products = json_decode($this->client->request('GET', 'localhost:8081/api/products')->getBody()->getContents())->data;
        }catch(\Exception $e){
            $products = [];
        }
        // if products is not empty merge carts and products
        if(!empty($products)){
            foreach($carts as $cart){
                foreach($products as $product){
                    if($cart->ProductID == $product->ID){
                        $cart->product = $product;
                    }
                }
            }
        }
        return view('pages.frontend.checkout.index', compact('cities', 'carts'));
    }

    public function check(Request $request){
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'courier' => 'required',
            'address' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success'
        ]);
    }

    public function shipping(Request $request){
        $response = $this->client->request('POST', 'https://api.rajaongkir.com/starter/cost', [
            'headers' => [
                'key' => '43a6fe54ef60d48b60a00d387b08bded'
            ],
            'form_params' => [
                'origin' => 501,
                'destination' => $request->city_id,
                'weight' => 1,
                'courier' => $request->courier
            ]
        ]);

        $shipping = json_decode($response->getBody()->getContents())->rajaongkir->results[0]->costs[0]->cost[0]->value;
        $total = $request->subtotal + $shipping;

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'ongkir' => $shipping,
            'total' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }

    public function store(Request $request){
        try{
            $carts = json_decode($this->client->request('GET', 'localhost:8082/api/carts/?user_id=' . auth()->user()->id)->getBody()->getContents())->data;
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        try{
            $products = json_decode($this->client->request('GET', 'localhost:8081/api/products')->getBody()->getContents())->data;
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        $carts = json_decode($this->client->request('GET', 'localhost:8082/api/carts/?user_id=' . auth()->user()->id)->getBody()->getContents())->data;
        $products = json_decode($this->client->request('GET', 'localhost:8081/api/products')->getBody()->getContents())->data;
        // if products is not empty merge carts and products
        if(!empty($products)){
            foreach($carts as $cart){
                foreach($products as $product){
                    if($cart->ProductID == $product->ID){
                        $cart->product = $product;
                    }
                }
            }
        }

        $total = 0;
        foreach($carts as $cart){
            $total += $cart->product->price * $cart->Quantity;
        }
        $total += intval($request->shipping);
        try{
            $order = json_decode($this->client->request('POST', $this->url, [
                'headers' => $this->headers,
                'json' => [
                    'user_id' => auth()->user()->id,
                    'shipping_price' => intval($request->shipping),
                    'courier' => $request->courier,
                    'total_price' => $total,
                    'status' => 'pending',
                    'payment_status' => 1,
                ]
            ])->getBody()->getContents())->data;
        } catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

        // insert order items
        foreach($carts as $cart){
            $this->client->request('POST', 'localhost:8083/api/order-items', [
                'headers' => $this->headers,
                'json' => [
                    'order_id' => $order->id,
                    'product_name' => $cart->product ? $cart->product->name : $cart->product_name,
                    'product_image' => $cart->product ? $cart->product->image : $cart->product_image,
                    'quantity' => $cart->Quantity,
                    'price' => $cart->product ? $cart->product->price : $cart->Price,
                ]
            ]);
        }
        foreach($carts as $cart){
            $this->client->request('DELETE', 'localhost:8082/api/carts/' . $cart->ID);
        }

        return redirect()->route('checkout.show', $order->id);
    }

    public function show($id){
        try{
            $order = json_decode($this->client->request('GET', $this->url . '/' . $id)->getBody()->getContents())->data;
        } catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        $snapToken = $order->snap_token;
        if (empty($snapToken)) {
            // Jika snap token masih NULL, buat token snap dan simpan ke database

            $midtrans = new CreateSnapTokenService($order);
            $snapToken = $midtrans->getSnapToken();

           try{
             // Update snap token
             $this->client->request('PUT', $this->url . '/' . $id, [
                'headers' => $this->headers,
                'json' => [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'shipping_price' => $order->shipping_price,
                    'courier' => $order->courier,
                    'payment_status' => $order->payment_status,
                    'snap_token' => $snapToken
                ]
            ]);
           } catch(\Exception $e){
               return redirect()->back()->with('error', $e->getMessage());
           }
        }

        return view('pages.frontend.checkout.show', compact('order', 'snapToken'));
    }
}
