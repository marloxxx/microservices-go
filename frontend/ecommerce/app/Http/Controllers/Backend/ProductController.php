<?php

namespace App\Http\Controllers\Backend;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $client;
    private $url;
    private $headers;
    private $body;
    private $messages;

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
        $this->url = "localhost:8081/api/products";
        $this->messages = [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'image.required' => 'Image is required',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be jpeg, png, jpg, gif, svg',
            'image.max' => 'Image must be less than 2MB'
        ];
    }
    private function setMeta(string $title)
    {
        SEOMeta::setTitle($title);
        OpenGraph::setTitle(SEOMeta::getTitle());
        JsonLd::setTitle(SEOMeta::getTitle());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->setMeta('Products');
        try {
            $response = $this->client->request('GET', $this->url);
            $products = json_decode($response->getBody()->getContents())->data;
            return view('pages.backend.products.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to get products');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->setMeta('Create Product');
        $categories = [];
        try {
            $response = $this->client->request('GET', 'localhost:8080/api/categories');
            $categories = json_decode($response->getBody()->getContents())->data;
            return view('pages.backend.products.create', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to get categories');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|numeric',

        ], $this->messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }
        // move image to public folder
        $image = $request->file('image');
        $image_name = 'http://localhost:8000/images/products/' . time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/products'), $image_name);
        try {
            $this->body = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => intval($request->price),
                'quantity' => intval($request->quantity),
                'image' => $image_name,
                'category_id' => intval($request->category_id),
                'category_name' => $request->category_name
            ];
            $response = $this->client->request('POST', $this->url, [
                'body' => json_encode($this->body)
            ]);
            $product = json_decode($response->getBody()->getContents())->data;
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'redirect' => route('backend.products.index'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->setMeta('Edit Product');
        try {
            // get product
            $response = $this->client->request('GET', $this->url . '/' . $id);
            $product = json_decode($response->getBody()->getContents())->data;

            // get categories
            $response = $this->client->request('GET', 'localhost:8080/api/categories');
            $categories = json_decode($response->getBody()->getContents())->data;

            return view('pages.backend.products.edit', compact('product', 'categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to get product');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|numeric',

        ], $this->messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }
        $image_name = $request->old_image;
        if ($request->hasFile('image')) {
            // move image to public folder
            $image = $request->file('image');
            $image_name = 'http://localhost:8000/images/products/' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $image_name);
        }
        try {
            $this->body = [
                'ID' => intval($request->id),
                'name' => $request->name,
                'description' => $request->description,
                'price' => intval($request->price),
                'quantity' => intval($request->quantity),
                'image' => $image_name,
                'category_id' => intval($request->category_id),
                'category_name' => $request->category_name
            ];
            $response = $this->client->request('PUT', $this->url . '/' . $id, [
                'body' => json_encode($this->body)
            ]);
            $product = json_decode($response->getBody()->getContents())->data;
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'redirect' => route('backend.products.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->client->request('DELETE', $this->url . '/' . $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
