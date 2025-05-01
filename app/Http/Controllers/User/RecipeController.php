<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function store(Request $request)
    {
        $attr = $request->validate([
            'image' => 'required'
        ]);

        if ($request->file('image') && $request->file('image')->isValid()) {

            $filename = $request->file('image')->hashName();
            $request->file('image')->storeAs('upload/resep', $filename, 'public');

            $attr['image'] = $filename;
        }

        $recipe = Recipe::create([
            'customer_id' => auth()->user()->id,
            'image' => $attr['image'],
            'status' => 1
        ]);

        return redirect()->back()->with('success', 'Resep berhasil dikirim!');
    }
}
