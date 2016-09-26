<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Parsedown;
class ProductsCtroller extends Controller
{
    //
    public function create()
    {
        return view('posts.create');
    }
}
