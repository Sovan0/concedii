<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
//use SebastianBergmann\CodeCoverage\Report\Xml\Project;

//use Symfony\Component\HttpFoundation;

class ProductController extends Controller
{
    public function index()
    {
        if(auth()->user()->role === 'admin') {
            $products = Product::orderBy('date_start', 'asc')->paginate(3);
        } else {
            $products = Product::where('user_id', auth()->user()->id)
                ->orderBy('date_start', 'asc')
                ->paginate(3);
        }
        return view('products.index', ['products' => $products]);
    }

    public function create(Product $product)
    {
        return view('products.create', ['product' => $product]);
    }

    public function store(Request $request)
    {
        $userId = User::where('id', auth()->user()->id)->first();
        $useName = User::where('name', auth()->user()->name)->first();
        $data = $request->validate([
            'user_id' => 'empty',
            'name' => 'empty',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required',
        ]);

        $data['user_id'] = $userId->id;
        $data['name'] = $useName->name;


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
            'name' => 'empty',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required'
        ]);

        $product->update($data);

        return redirect(route('product.index'))->with('success', 'Product Updated Succesffully');
    }

    public function delete(Product $product)
    {
        $product->delete();

        return redirect(route('product.index'))->with('success', 'Product deleted Succesffully');
    }

    public function getUserId() {
        $userId = User::where('id', auth()->user()->id)->first();
        dd($userId->id);
    }

//    public function search(Request $request) {
//        $query = $request->input('query');
//        session(['search_query' => $query]);
//
//        $searched_items = Product::where('name', 'like', "%$query%")->paginate(3);
//
//        return view('products.search', compact('searched_items'));
//    }

    public function search(Request $request) {
        $query = $request->input('query');

        $searched_items = Product::whereRaw("name LIKE '%$query%'")
            ->paginate(3);

        return view('products.search', compact('searched_items'));
    }
}
