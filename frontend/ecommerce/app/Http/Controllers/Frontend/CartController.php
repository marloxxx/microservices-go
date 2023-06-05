<?php

namespace App\Http\Controllers\Frontend;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;

class CartController extends Controller
{
    private $client;
    private $url;
    private $headers;
    private $body;

    public function __construct()
    {
        SEOMeta::setTitleDefault(getSettings('site_name'));
        parent::__construct();
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $this->client = new Client([
            'headers' => $this->headers
        ]);
        $this->url = "localhost:8082/api/carts";
    }

    private function setMeta(string $title)
    {
        SEOMeta::setTitle($title);
        OpenGraph::setTitle(SEOMeta::getTitle());
        JsonLd::setTitle(SEOMeta::getTitle());
    }

    public function index(){
        $this->setMeta('Cart');
        try {
            $carts = json_decode($this->client->request('GET', $this->url . '/' . auth()->user()->id)->getBody()->getContents())->data;
        } catch (\Exception $e) {
            $carts = [];
        }
        return view('pages.frontend.cart.index', compact('carts'));
    }

    public function store(Request $request){
        $this->setMeta('Cart');
        try {
            // get product
            $product = json_decode($this->client->request('GET', 'localhost:8081/api/products/' . $request->product_id)->getBody()->getContents())->data;
            // check if product is available
            if ($product->quantity < $request->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product is not available'
                ]);
            }
            // dd($product);
            $this->body = [
                'product_id' => intval($request->product_id),
                'product_image' => $product->image,
                'product_name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'total' => $product->price * $request->quantity,
            ];
            // check if product is already in cart
            $carts = json_decode($this->client->request('GET', $this->url . '/' . auth()->user()->id)->getBody()->getContents())->data;
            // dd($carts);
            // if product is already in cart, update quantity
            foreach ($carts as $cart) {
                if ($cart->ProductID == $request->product_id) {
                    $this->body['quantity'] += $cart->Quantity;
                    $this->body['total'] += $cart->Total;
                    $this->body['ID'] = $cart->ID;
                    $this->body['user_id'] = auth()->user()->id;
                    $this->client->request('PUT', $this->url . '/' . $cart->ID, [
                        'body' => json_encode($this->body)
                    ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Product added to cart',
                    ]);
                } else {
                    // if product is not in cart, add product to cart
                    $this->client->request('POST', $this->url, [
                        'body' => json_encode($this->body)
                    ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Product added to cart',
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id){
        $this->setMeta('Cart');
        try {
            // get product
            $product = json_decode($this->client->request('GET', 'localhost:8081/api/products/' . $request->product_id)->getBody()->getContents())->data;
            // check if product is available
            if ($product->stock < $request->qty) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product is not available'
                ]);
            }
            $this->body = [
                'product_id' => $request->product_id,
                'product_image' => $product->image,
                'product_name' => $product->name,
                'quantity' => $request->qty,
                'price' => $product->price,
                'total' => $product->price * $request->qty,
            ];
            $this->client->request('PUT', $this->url . '/' . $id, [
                'body' => json_encode($this->body)
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Cart updated',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update cart'
            ]);
        }
    }

    public function destroy($id){
        $this->setMeta('Cart');
        try {
            $this->client->request('DELETE', $this->url . '/' . $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Cart deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete cart'
            ]);
        }
    }
}
