<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('id', 'desc')->paginate(10);
        return view('country.index', compact('countries'));
    }


    public function create()
    {
        return view('country.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:countries,name',
        ]);
        Country::create([
            'name' => $request->name,
            'status' => 1
        ]);
        return Redirect::route('country.index')->with('success', 'country create successfully');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $country = Country::find($id);
        return view('country.edit', compact('country'));
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:countries,name,' . $id,
        ]);
        $skill  = Country::find($id);
        $skill->name = $request->name;
        $skill->status = $request->status;
        $skill->save();
        return Redirect::back()->with('success', 'country update successfully');
    }


    public function destroy(string $id)
    {

        DB::beginTransaction();
        try {
            $country = Country::find($id);
            if (empty($country)) {
                dd($country);
                throw new \Exception('something went wrong');
            }
            $country->users()->update(['country_id' => null]);
            $country->userExperiences()->update(['country_id' => null]);
            $country->delete();
            DB::commit();
            return redirect()->route('country.index')->with('success', 'Country deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('country.index')->with('error', 'Something went wrong. Please try again.');
        }
    }
}
