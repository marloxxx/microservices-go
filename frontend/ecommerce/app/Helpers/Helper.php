<?php

use App\Models\Setting;

if (!function_exists('generateRandom')) {
    function generateRandom($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('getSettings')) {
    function getSettings($key)
    {
        return Setting::first()->$key;
    }
}

if (!function_exists('getSeoSettings')) {
    function getSeoSettings($key)
    {
        if (Setting::first()->seo == null) {
            return null;
        } else {
            return json_decode(Setting::first()->seo)->$key;
        }
    }
}
