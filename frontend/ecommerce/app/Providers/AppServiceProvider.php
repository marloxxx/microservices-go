<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // check if user is logged in

            View::composer('layouts.frontend.master', function ($view) {
                if (Auth::check()) {
                    $client = new \GuzzleHttp\Client();
                $url = "localhost:8082/api/carts/" . auth()->user()->id;
                $headers = [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ];
                try {
                    $response = $client->request('GET', $url, [
                        'headers' => $headers
                    ]);
                    $carts = json_decode($response->getBody()->getContents())->data;
                } catch (\Exception $e) {
                    $carts = [];
                }
                $cart_count = count($carts);
                $view->with(compact('carts', 'cart_count'));
                }
            });
    }
}
