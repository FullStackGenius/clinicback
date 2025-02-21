<?php

namespace App\Http\Controllers;

use App\Models\YourExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class YourExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $yourExperiences = YourExperience::paginate(10);
        return view('your-experience.index',compact('yourExperiences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('your-experience.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|unique:your_experiences,name',
            'icon_image' => 'required|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
       
        if ($request->hasFile('icon_image')) {
            // Get the file from the request
            $image = $request->file('icon_image');

    
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the storage folder
            $path = $image->storeAs('your-experience', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'

            
        }

        YourExperience::create([
            'name'=> $request->name,
            'icon_image' => $imageName
        ]);
        return Redirect::route('your-experience.index')->with('success', 'Data save successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $yourExperience  = YourExperience::find($id);
        return view('your-experience.edit', compact('yourExperience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:your_experiences,name,' . $id,
            'icon_image' => 'sometimes|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
       
        if ($request->hasFile('icon_image')) {
            // Get the file from the request
            $image = $request->file('icon_image');

    
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the storage folder
            $path = $image->storeAs('your-experience', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'

            $imageData = YourExperience::find($id);

            if ($imageData->icon_image) {
                Storage::disk('public')->delete('your-experience/' . $imageData->icon_image);
            }
        
            YourExperience::where('id', $id)->update([
                'name'=> $request->name,
                'icon_image' => $imageName
            ]);
        }else{
            YourExperience::where('id', $id)->update([
                'name'=> $request->name 
            ]);
        }
       
        return Redirect::back()->with('success', 'Data save successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill  = YourExperience::find($id);
        $skill->delete();
        return Redirect::route('your-experience.index')->with('success', 'Data delete successfully');
    }
}
