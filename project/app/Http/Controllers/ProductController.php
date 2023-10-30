<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return view('products.index', ['products' => $products]);
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required',
        ]);

        $newHoliday = Product::create($data);

        return redirect(route('product.index'));
    }

    public function edit(Product $product) {
        return view('products.edit', ['product' => $product]);
    }

    public function update(Product $product, Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required'
        ]);

        $product -> update($data);

        return redirect(route('products.index'))->with('susccess', 'Product Updated Succesffully');
    }

    public function delete(Product $product) {
        $product->delete();

        return redirect(route('product.index'))->with('susccess', 'Product deleted Succesffully');

    }
}
