<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys  = Category::orderBy('id', 'desc')->paginate(10);
        return view('category.index', compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);
        Category::create([
            'name' => $request->name
        ]);
        return Redirect::route('category.index')->with('success', 'Category create successfully');
        // return Redirect::route('home')->with('error', 'You are not authorized.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category  = Category::find($id);
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
        ]);
        $category  = Category::find($id);
        $category->name = $request->name;
        $category->category_status = $request->status;
        $category->save();
        return Redirect::back()->with('success', 'Category update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         DB::beginTransaction();
         try {
            $category = Category::findOrFail($id);
            foreach ($category->subCategories as $subCategory) {
                $subCategory->users()->detach();
            }
            $category->subCategories()->delete();
            $category->delete();
             DB::commit();
             return Redirect::route('category.index')->with('success', 'Category deleted successfully.');
         } catch (\Exception $e) {
             DB::rollBack();
             return Redirect::route('category.index')->with('error', 'Something went wrong. Please try again.');
         }
    }
}
