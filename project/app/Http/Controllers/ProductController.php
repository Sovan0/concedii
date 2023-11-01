<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation;

class ProductController extends Controller
{
    public function index()
    {
        if(auth()->user()->role === 'admin') {
            $products = Product::all();
        } else {
            $products = Product::where('user_id', auth()->user()->id)->get();
        }
        return view('products.index', ['products' => $products]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $userId = User::where('id', auth()->user()->id)->first();
        $data = $request->validate([
            'user_id' => 'empty',
            'name' => 'required',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required',
        ]);

        $data['user_id'] = $userId->id;

        $newProduct = Product::create($data);

        return redirect(route('product.index'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product]);
    }

    public function update(Product $product, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required'
        ]);

        $product->update($data);

        return redirect(route('product.index'))->with('susccess', 'Product Updated Succesffully');
    }

    public function delete(Product $product)
    {
        $product->delete();

        return redirect(route('product.index'))->with('susccess', 'Product deleted Succesffully');
    }

    public function getUserId() {
        $userId = User::where('id', auth()->user()->id)->first();
        dd($userId->id);
    }
}
