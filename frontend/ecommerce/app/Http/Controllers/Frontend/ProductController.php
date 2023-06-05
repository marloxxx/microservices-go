<?php

namespace App\Http\Controllers\Frontend;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;

class ProductController extends Controller
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
        $this->url = "localhost:8080/api/categories";
    }

    private function setMeta(string $title)
    {
        SEOMeta::setTitle($title);
        OpenGraph::setTitle(SEOMeta::getTitle());
        JsonLd::setTitle(SEOMeta::getTitle());
    }
    public function index()
    {
        $this->setMeta('Products');
        try {
            $products = json_decode($this->client->request('GET', 'localhost:8081/api/products')->getBody()->getContents())->data;
        } catch (\Exception $e) {
            $products = [];
        }
        return view('pages.frontend.product.index', compact('products'));
    }
    public function show($id)
    {
        $this->setMeta('Product');
        try {
            $product = json_decode($this->client->request('GET', 'localhost:8081/api/products/' . $id)->getBody()->getContents())->data;
        } catch (\Exception $e) {
            $product = [];
        }
        return view('pages.frontend.product.show', compact('product'));
    }
}
