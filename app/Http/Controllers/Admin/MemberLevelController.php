<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberLevel;
use App\Http\Requests\Admin\StoreMemberLevelRequest;
use App\Http\Requests\Admin\UpdateMemberLevelRequest;
use Yajra\DataTables\Facades\DataTables;

class MemberLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $memberLevels = MemberLevel::latest()->get();
            return DataTables::of($memberLevels)
                ->addIndexColumn()
                ->addColumn('action', 'admin.member-level.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.member-level.index');
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
    public function store(StoreMemberLevelRequest $request)
    {
        try {
            $attr = $request->validated();

            MemberLevel::create($attr);

            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberLevel $memberLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberLevel $memberLevel)
    {
        return view('admin.member-level.edit', compact('memberLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberLevelRequest $request, MemberLevel $memberLevel)
    {
        try {
            $attr = $request->validated();

            $memberLevel->update($attr);

            return redirect()
                ->route('admin.member-level.index')
                ->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberLevel $memberLevel)
    {
        try {
            $memberLevel->delete();

            return redirect()
                ->back()
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', __($th->getMessage()));
        }
    }
}
