<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiscountRequest;
use App\Http\Requests\Admin\UpdateDiscountRequest;
use App\Models\Discount;
use App\Models\Product;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (request()->ajax()) {
            $discounts = Discount::with('product')->latest()->get();
            return DataTables::of($discounts)
                ->addIndexColumn()
                ->addColumn('product_price', function ($row) {
                    return 'Rp. ' . number_format($row->product->price, 0, ',', '.');
                })
                ->addColumn('selling_price', function ($row) {
                    return 'Rp. ' . number_format($row->product->price - $row->discount, 0, ',', '.');
                })
                ->addColumn('discount', function ($row) {
                    return 'Rp. ' . number_format($row->discount, 0, ',', '.');
                })
                ->addColumn('action', 'admin.discount.include.action')
                ->toJson();
        }

        return view('admin.discount.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscountRequest $request)
    {
        $attr = $request->validated();

        Discount::create($attr);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();

            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
