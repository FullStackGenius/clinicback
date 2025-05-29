<?php

namespace App\Http\Controllers;

use App\Models\YourGoal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class YourGoalController extends Controller
{
    public function index()
    {
        $yourGoals = YourGoal::paginate(10);
        return view('your-goal.index',compact('yourGoals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('your-goal.create');
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
            $path = $image->storeAs('your-goal', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'

            
        }

        YourGoal::create([
            'name'=> $request->name,
            'icon_image' => $imageName
        ]);
        return Redirect::route('your-goal.index')->with('success', 'Data save successfully');
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
        $yourGoal  = YourGoal::find($id);
        return view('your-goal.edit', compact('yourGoal'));
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
            $path = $image->storeAs('your-goal', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
            $imageData = YourGoal::find($id);

            if ($imageData->icon_image) {
                Storage::disk('public')->delete('your-goal/' . $imageData->icon_image);
            }
            // if (Storage::exists('how-to-like-work/'.$imageData->icon_image)) {
            //     Storage::delete('how-to-like-work/'.$imageData->icon_image);
            // }
            YourGoal::where('id', $id)->update([
                'name'=> $request->name,
                'icon_image' => $imageName,
                'status' => $request->status
            ]);
            
        }else{
            YourGoal::where('id', $id)->update([
                'name'=> $request->name,
                'status' => $request->status
            ]);
        }
        return Redirect::back()->with('success', 'Data save successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill  = YourGoal::find($id);
        $skill->delete();
        return Redirect::route('your-goal.index')->with('success', 'Data delete successfully');
    }
}
