<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategorys  = SubCategory::with('getCategory')->orderBy('id','desc')->paginate(15);
       
        return view('subcategory.index', compact('subCategorys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorys  = Category::where('category_status',1)->get();
        return view('subcategory.create', compact('categorys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:sub_categories,name',
            'category' => 'required',
        ]);
        SubCategory::create([
            'name' => $request->name,
            'category_id' => $request->category,
        ]);
        return Redirect::route('subcategory.index')->with('success', 'Sub Category create successfully');
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
        $subCategory  = SubCategory::find($id);
        $categorys  = Category::where('category_status',1)->get();
        return view('subcategory.edit', compact(['subCategory','categorys']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:sub_categories,name,' . $id,
            'category' => 'required',
            'status' => 'required',
        ]);
        $subCategory  = SubCategory::find($id);
        $subCategory->name = $request->name;
        $subCategory->subcategory_status = $request->status;
        $subCategory->category_id = $request->category;
        $subCategory->save();
        return Redirect::back()->with('success', 'Sub Category update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
         try {
            $subCategory = SubCategory::findOrFail($id);
            // Detach pivot table data
            $subCategory->users()->detach();
            // Delete the subcategory
            $subCategory->delete();
             DB::commit();
             return Redirect::route('subcategory.index')->with('success', 'SubCategory deleted successfully.');
         } catch (\Exception $e) {
             DB::rollBack();
             return Redirect::route('subcategory.index')->with('error', 'Something went wrong. Please try again.');
         }
    }
}
