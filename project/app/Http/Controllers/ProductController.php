<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

//use SebastianBergmann\CodeCoverage\Report\Xml\Project;

//use Symfony\Component\HttpFoundation;

class ProductController extends Controller{
    public function index(Request $request) {
        $query = Product::query();

        if (auth()->user()->role === 'admin') {
            $query->orderBy('date_start', 'asc');
        } else {
            $query->where('user_id', auth()->user()->id)
                ->orderBy('date_start', 'asc');
        }

        if (session()->has('start_date')) {
            $query->where('date_start', '>=', session('start_date'))
                ->orderBy('date_start', 'asc');
        }

        if (session()->has('end_date')) {
            $query->where('date_stop', '<=', session('end_date'))
                ->orderBy('date_start', 'asc');
        }

        if (session()->has('search_query')) {
            $query->where('name', 'LIKE', '%' . session('search_query') . '%')
                ->orderBy('date_start', 'asc');
        }
        $products = $query->paginate(5);

        return view('products.index', [
            'products' => $products,
            'start_date' => session('start_date'),
            'end_date' => session('end_date'),
            'search_query' => session('search_query'),
        ]);
    }

    public function showCreateProduct(Product $product)
    {
        return view('products.create', ['product' => $product]);
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

        return redirect(route('products.index'))->with('success', 'Product Updated Succesffully');
    }

    public function delete(Product $product)
    {
        $product->delete();

        return redirect(route('products.index'))->with('success', 'Product deleted Succesffully');
    }

    public function filtered(Request $request) {
        $search_name = $request->input('query');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        session(['search_query' => $search_name, 'start_date' => $start_date, 'end_date' => $end_date]);

        $productsQuery = Product::query();

        if (auth()->user()->role === 'admin') {
            if ($search_name) {
                $productsQuery->whereRaw("name LIKE '%$search_name%'")
                    ->orderBy('date_start', 'asc');
            }

            if ($start_date && $end_date) {
                $productsQuery->whereBetween('date_start', [$start_date, $end_date])
                    ->orderBy('date_start', 'asc');
            }
        } else {
            $productsQuery->where('user_id', auth()->id());

            if ($search_name) {
                $productsQuery->whereRaw("name LIKE '%$search_name%'")
                    ->orderBy('date_start', 'asc');
            }

            if ($start_date && $end_date) {
                $productsQuery->whereBetween('date_start', [$start_date, $end_date])
                    ->orderBy('date_start', 'asc');
            }
        }

//        $orderBy = $request->get('orderBy', 'date_start');
//        $orderDirection = $request->get('orderDirection', 'asc');
//        $productsQuery->orderBy($orderBy, $orderDirection);

        $products = $productsQuery->paginate(5);

        $products->appends(['query' => $search_name, 'start_date' => $start_date, 'end_date' => $end_date]);

        return view('products.index', ['products' => $products]);
    }

    public function getUserId()
    {
        $userId = User::where('id', auth()->user()->id)->first();
        dd($userId->id);
    }

    public function getDate(Request $request)
    {
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
        if (count($existedProducts) > 0) {
            foreach ($existedProducts as $exist) {
                $minStartDate = $exist->date_start;
                $maxStopDate = $exist->date_stop;
                $minStartDateNumber = date("y-m-d", strtotime($minStartDate));
                $maxStopDateNumber = date("y-m-d", strtotime($maxStopDate));
                $minStartDateNumberStrtotime = strtotime($minStartDateNumber);
                $minStopDateNumberStrtotime = strtotime($maxStopDateNumber);
                if (($minStartDateNumberStrtotime <= $dateStartStrtotime and $dateStartStrtotime <= $minStopDateNumberStrtotime) or ($minStartDateNumberStrtotime <= $dateStopStrtotime and $dateStopStrtotime <= $minStopDateNumberStrtotime)) {
                    return response()->json(['error' => 1, 'message' => 'This period is already taken. Please choose another period.']);
                } elseif (($minStartDateNumberStrtotime >= $dateStartStrtotime or $dateStartStrtotime >= $minStopDateNumberStrtotime) or ($minStartDateNumberStrtotime >= $dateStopStrtotime or $dateStopStrtotime >= $minStopDateNumberStrtotime)) {
                    $userId = auth()->id();
                    $userName = auth()->user()->name;
                    $newProduct = new Product();
                    $newProduct->user_id = $userId;
                    $newProduct->name = $userName;
                    $newProduct->date_start = $dateStart;
                    $newProduct->date_stop = $dateStop;
                    $newProduct->description = $description;
                    $newProduct->save();

                    return response()->json(['error' => 0, 'message' => 'Product successfully created.']);
                }
            }

        } else {
            $userId = auth()->id();
            $userName = auth()->user()->name;
            $newProduct = new Product();
            $newProduct->user_id = $userId;
            $newProduct->name = $userName;
            $newProduct->date_start = $dateStart;
            $newProduct->date_stop = $dateStop;
            $newProduct->description = $description;
            $newProduct->save();

            return response()->json(['error' => 0, 'message' => 'Product successfully created.']);
        }
    }
}
