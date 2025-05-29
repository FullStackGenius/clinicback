<?php

namespace App\Http\Controllers;

use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ResourceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys  = ResourceCategory::orderBy('id', 'desc')->paginate(10);
        return view('resource-category.index', compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('resource-category..create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:resource_categories,name',
        ]);
        ResourceCategory::create([
            'name' => $request->name
        ]);
        return Redirect::route('resource-category.index')->with('success', 'Resource category create successfully');
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
        $category  = ResourceCategory::find($id);
        return view('resource-category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:resource_categories,name,' . $id,
        ]);
        $category  = ResourceCategory::find($id);
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();
        return Redirect::back()->with('success', 'Resource category update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         DB::beginTransaction();
         try {
            $category = ResourceCategory::findOrFail($id);
            // foreach ($category->subCategories as $subCategory) {
            //     $subCategory->users()->detach();
            // }
            // $category->subCategories()->delete();
            $category->delete();
             DB::commit();
             return Redirect::route('resource-category.index')->with('success', 'Resource category deleted successfully.');
         } catch (\Exception $e) {
             DB::rollBack();
             return Redirect::route('resource-category.index')->with('error', 'Something went wrong. Please try again.');
         }
    }
}
