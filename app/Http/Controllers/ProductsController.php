<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        return new ProductCollection(Product::all());
    }
}
