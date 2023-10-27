<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index() {
        $holidays = Holiday::all();

        return view('holidays.index', ['holidays' => $holidays]);
    }

    public function create() {
        return view('holidays.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required',
        ]);

        $newHoliday = Holiday::create($data);

        return redirect(route('holidays.index'));
    }

    public function edit(Holiday $product) {
        return view('products.edit', ['product' => $product]);
    }

    public function update(Holiday $product, Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'date_start' => 'required',
            'date_stop' => 'required',
            'description' => 'required'
        ]);

        $product -> update($data);

        return redirect(route('product.index'))->with('susccess', 'Product Updated Succesffully');
    }

    public function delete(Holiday $product) {
        $product->delete();

        return redirect(route('product.index'))->with('susccess', 'Product deleted Succesffully');

    }
}
