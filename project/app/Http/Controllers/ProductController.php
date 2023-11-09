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
    public function index(Request $request) {
        $query = Product::query();

        if (auth()->user()->role === 'admin') {
            $query->orderBy('date_start', 'asc');
        } else {
            $query->where('user_id', auth()->user()->id)
                ->orderBy('date_start', 'asc');
        }

        if (session()->has('start_date')) {
            $query->where('date_start', '>=', session('start_date'));
        }

        if (session()->has('end_date')) {
            $query->where('date_stop', '<=', session('end_date'));
        }

        if (session()->has('search_query')) {
            $query->where('name', 'LIKE', '%' . session('search_query') . '%');
        }

        $products = $query->paginate(3);

        return view('products.index', [
            'products' => $products,
            'start_date' => session('start_date'),
            'end_date' => session('end_date'),
            'search_query' => session('search_query'),
        ]);
    }

    public function create(Product $product) {
        return view('products.create', ['product' => $product]);
    }

    public function store(Request $request) {
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

//        return redirect(route('product.index'));
        return response()->json(['error' => 0, 'message' => 'All is great']);
    }

    public function edit(Product $product) {
        return view('products.edit', ['product' => $product]);
    }

    public function update(Product $product, Request $request) {
        $data = $request->validate([
            'name' => 'empty',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required'
        ]);

        $product->update($data);

        return redirect(route('product.index'))->with('success', 'Product Updated Succesffully');
    }

    public function delete(Product $product) {
        $product->delete();

        return redirect(route('product.index'))->with('success', 'Product deleted Succesffully');
    }

    public function search(Request $request) {
        $query = $request->input('query');
        session(['search_query' => $query]);

        $products = Product::whereRaw("name LIKE '%$query%'")->paginate(3);

        return view('products.search', compact('products'));
    }

    public function filteredProducts(Request $request) {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        session(['start_date' => $start_date, 'end_date' => $end_date]);

        $query = Product::query();

        if ($start_date && $end_date) {
            $query->whereBetween('date_start', [$start_date, $end_date]);
        }

        $products = $query->paginate(3);

        return view('products.index', ['products' => $products]);
    }

    public function getUserId() {
        $userId = User::where('id', auth()->user()->id)->first();
        dd($userId->id);
    }

    public function getDate(Request $request) {
        $dateStart = new Product();
        $dateStart->date_start = $request->date_start;
        dd($dateStart->date_start);
    }

    public function productCreate(Request $request) {
        $data = $request->get("data");

        $dateStart = $data['date_start'];
        $dateStop = $data['date_stop'];
        $description = $data['description'];

        $dateStartNumber = date("y-m-d", strtotime($dateStart));
        $dateStopNumber = date("y-m-d", strtotime($dateStop));
        $dateStartStrtotime = strtotime($dateStartNumber);
        $dateStopStrtotime = strtotime($dateStopNumber);

        $existedProducts = Product::where('user_id', auth()->id())->get();
        foreach ($existedProducts as $exist) {
            $minStartDate = $exist->date_start;
            $maxStopDate = $exist->date_stop;
            $minStartDateNumber = date("y-m-d", strtotime($minStartDate));
            $maxStopDateNumber = date("y-m-d", strtotime($maxStopDate));
            $minStartDateNumberStrtotime = strtotime($minStartDateNumber);
            $minStopDateNumberStrtotime = strtotime($maxStopDateNumber);

//            dd($dateStartStrtotime, $dateStopStrtotime, $minStartDateNumberStrtotime, $minStopDateNumberStrtotime);
            if(($minStartDateNumberStrtotime <= $dateStartStrtotime AND $dateStartStrtotime <= $minStopDateNumberStrtotime) OR ($minStartDateNumberStrtotime <= $dateStopStrtotime AND $dateStopStrtotime <= $minStopDateNumberStrtotime)) {
                return "This period was took it";
            } elseif($minStartDateNumberStrtotime >= $dateStartStrtotime OR $dateStartStrtotime >= $minStopDateNumberStrtotime OR $minStartDateNumberStrtotime >= $dateStopStrtotime OR $dateStopStrtotime >= $minStopDateNumberStrtotime) {
                return "Success like test";
            }
        }

        dd($existedProducts);

//        if(!$exitedProducts) {
//            $dateStart = $request->date_start;
//            $dateStop = $request->date_stop;
//            $description = $request->description;
//            return "test";
//        } else {
//            return $existElements;
//        }

//        return $request->get("data");
    }
}
