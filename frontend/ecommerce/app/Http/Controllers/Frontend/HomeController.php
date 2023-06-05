<?php

namespace App\Http\Controllers\Frontend;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;

class HomeController extends Controller
{
    private $client;
    private $url;
    private $headers;

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
        $this->setMeta('Home');
        try {
            $categories = json_decode($this->client->request('GET', $this->url)->getBody()->getContents())->data;
            // only get 5 categories
            $categories = array_slice($categories, 0, 5);
        } catch (\Exception $e) {
            $categories = [];
        }
        try {
            $products = json_decode($this->client->request('GET', 'localhost:8081/api/products')->getBody()->getContents())->data;
            // only get 8 products
            $products = array_slice($products, 0, 8);
        } catch (\Exception $e) {
            $products = [];
        }
        return view('pages.frontend.home.index', compact('categories', 'products'));
    }
}
