<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Wish\WishAuth;
use Wish\WishClient;

class WishController extends Controller
{

    public function index(){

        $access_token = '0ab15d920ae049158addeeda1af924c8';
        $merchant_id = '5597aa269cf31c4069d02aba';
        $client = new WishClient($access_token,'prod');

        //$products = $client->getAllProducts();
        $product_variations = $client->getAllProductVariations();
        dd($product_variations);
        echo "You have ".count($products)." products!\n";

    }
}
