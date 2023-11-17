<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
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

        $newStartDate = strtotime($data['date_start']);
        $newStopDate = strtotime($data['date_stop']);

        $existedProducts = Product::where('user_id', auth()->id())->where('id', '!=', $product->id)->get();

        foreach ($existedProducts as $exist) {
            $minStartDate = strtotime($exist->date_start);
            $maxStopDate = strtotime($exist->date_stop);

            if (($minStartDate <= $newStartDate && $newStartDate <= $maxStopDate) || ($minStartDate <= $newStopDate && $newStopDate <= $maxStopDate)) {
                return redirect(route('products.index'))->with('error', 'This period is already taken. Please choose another period.');
            } elseif (($minStartDate >= $newStartDate || $newStartDate >= $maxStopDate) || ($minStartDate >= $newStopDate || $newStopDate >= $maxStopDate)) {
                $product->update($data);
                return redirect(route('products.index'))->with('success', 'Product Updated Successfully123');
            }
        }

        $product->update($data);
        return redirect(route('products.index'))->with('success', 'Product Updated Successfully12');
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

        $productsQuery = Product::query()
            ->orderBy('date_start', 'asc');

        if (auth()->user()->role === 'admin') {
            if ($search_name) {
                $productsQuery->whereRaw("name LIKE '%$search_name%'")
                    ->orderBy('date_start', 'asc');
            }

            if ($start_date && $end_date) {
                $productsQuery->where(function($query) use ($start_date, $end_date) {
                    $query->whereBetween('date_start', [$start_date, $end_date])
                        ->orWhereBetween('date_stop', [$start_date, $end_date]);
                })->orderBy('date_start', 'asc');
            }
        } else {
            $productsQuery->where('user_id', auth()->id());

            if ($search_name) {
                $productsQuery->whereRaw("name LIKE '%$search_name%'")
                    ->orderBy('date_start', 'asc');
            }

            if ($start_date && $end_date) {
                $productsQuery->where(function($query) use ($start_date, $end_date) {
                    $query->whereBetween('date_start', [$start_date, $end_date])
                        ->orWhereBetween('date_stop', [$start_date, $end_date]);
                })->orderBy('date_start', 'asc');
            }
        }

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

    public function barChart(Request $request) {
        $year = $request->input('year', date('Y'));
        $totalDaysPerMonth = [];

        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        for ($month = 1; $month <= 12; $month++) {
            $firstDayOfMonth = Carbon::create($year, $month, 1)->startOfDay();
            $lastDayOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

            $totalDays = Product::where(function ($query) use ($firstDayOfMonth, $lastDayOfMonth) {
                $query->where('date_start', '>=', $firstDayOfMonth)
                    ->where('date_stop', '<=', $lastDayOfMonth);
            })->orWhere(function ($query) use ($firstDayOfMonth, $lastDayOfMonth) {
                $query->whereBetween('date_start', [$firstDayOfMonth, $lastDayOfMonth])
                    ->orWhereBetween('date_stop', [$firstDayOfMonth, $lastDayOfMonth]);
            })->get();

            $workingDays = 0;

            foreach ($totalDays as $day) {
                $currentDay = Carbon::parse($day->date_start);
                $lastDay = Carbon::parse($day->date_stop);

                while ($currentDay->lte($lastDay)) {
                    if ($currentDay->month === $month && $currentDay->isWeekday()) {
                        $workingDays++;
                    }
                    $currentDay->addDay();
                }
            }

            $totalDaysPerMonth[$monthNames[$month]] = $workingDays;
        }

        return response()->json(['daysInYear' => $totalDaysPerMonth]);
    }

}
