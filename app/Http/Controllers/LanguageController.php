<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    
    public function index()
    {
        $languages = Language::orderBy('id', 'desc')->paginate(10);
        return view('languages.index', compact('languages'));
    }

   
    public function create()
    {
        return view('languages.create');
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:languages,name',
        ]);
        Language::create([
            'name' => $request->name,
            'status' => 1
        ]);
        return Redirect::route('language.index')->with('success', 'language create successfully');
    }

  
    public function show(string $id)
    {
        //
    }

   
    public function edit(string $id)
    {
        $language = Language::find($id);
        return view('languages.edit', compact('language'));
    }

   
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:languages,name,' . $id,
        ]);
        $skill  = Language::find($id);
        $skill->name = $request->name;
        $skill->status = $request->status;
        $skill->save();
        return Redirect::back()->with('success', 'language update successfully');
    }

  
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $language = Language::find($id);
            if (empty($language)) {
                throw new \Exception('something went wrong');
            }
            $language->users()->detach();
            $language ->delete();
            DB::commit();
            return redirect()->route('language.index')->with('success', 'language deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('language.index')->with('error', 'Something went wrong. Please try again.');
        }
    }
}
