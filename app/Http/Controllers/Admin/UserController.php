<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Customer;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $users = Customer::with('user', 'memberLevel')->latest()->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imagePath = $row->image ? asset('storage/upload/avatar/' . $row->image) : asset('template/dist/img/avatar4.png');
                    return '<img src="' . $imagePath . '" alt="Gambar 1" style="max-width:100px;">';
                })
                ->addColumn('name', function ($row) {
                    return $row->full_name;
                })
                ->addColumn('member_level', function ($row) {
                    return $row->memberLevel->name;
                })
                ->addColumn('action', function ($row) {
                    return view('admin.user.include.action', compact('row'));
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('admin.user.index');
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
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
