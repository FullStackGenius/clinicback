<?php

namespace App\Http\Controllers;

use App\Models\HowLikeToWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class HowLikeToWorkController extends Controller
{
    public function index()
    {
        $howLikeToWorks = HowLikeToWork::paginate(10);
        return view('how-to-like-work.index',compact('howLikeToWorks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('how-to-like-work.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|unique:how_like_to_works,name',
            'icon_image' => 'required|mimes:jpg,jpeg,png,gif,svg|max:2048',
             'description' => 'required'
        ]);
       
        if ($request->hasFile('icon_image')) {
            // Get the file from the request
            $image = $request->file('icon_image');

    
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the storage folder
            $path = $image->storeAs('how-to-like-work', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'

            
        }

        HowLikeToWork::create([
            'name'=> $request->name,
            'icon_image' => $imageName,
            'description' => $request->description,
        ]);
        return Redirect::route('how-to-like-work.index')->with('success', 'Data save successfully');
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
        $howLikeToWork  = HowLikeToWork::find($id);
        return view('how-to-like-work.edit', compact('howLikeToWork'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:how_like_to_works,name,' . $id,
            'icon_image' => 'sometimes|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'description' => 'required'
        ]);
       
        if ($request->hasFile('icon_image')) {
            // Get the file from the request
            $image = $request->file('icon_image');

    
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the storage folder
            $path = $image->storeAs('how-to-like-work', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'

            $imageData = HowLikeToWork::find($id);
            if ($imageData->icon_image) {
                Storage::disk('public')->delete('how-to-like-work/' . $imageData->icon_image);
            }
            // if (Storage::exists('how-to-like-work/'.$imageData->icon_image)) {
            //     Storage::delete('how-to-like-work/'.$imageData->icon_image);
            // }

            HowLikeToWork::where('id', $id)->update([
                'name'=> $request->name,
                'icon_image' => $imageName,
                'description' => $request->description,
                
            ]);
            
        }else{
            HowLikeToWork::where('id', $id)->update([
                'name'=> $request->name,
                'description' => $request->description,
                
            ]);
        }
       
        return Redirect::back()->with('success', 'Data save successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill  = HowLikeToWork::find($id);
        $skill->delete();
        return Redirect::route('how-to-like-work.index')->with('success', 'Data delete successfully');
    }
}
