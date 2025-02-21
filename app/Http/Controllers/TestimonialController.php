<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::paginate(10);
        return view('testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|',
            'client_image' => 'required|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'designation' => 'required',
            'feedback' => 'required'
        ]);

        if ($request->hasFile('client_image')) {
            // Get the file from the request
            $image = $request->file('client_image');


            $imageName = Str::uuid(). '.' . $image->getClientOriginalExtension();

            // Store the image in the storage folder
            $path = $image->storeAs('testimonial-image', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'


        }

        Testimonial::create([
            'name' => $request->name,
            'client_image' => $imageName,
            'designation' => $request->designation,
            'feedback' => $request->feedback,
        ]);
        return Redirect::route('testimonials.index')->with('success', 'Data save successfully');
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
        $testimonial = Testimonial::find($id);
        return view('testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|',
            // 'client_image' => 'required|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'designation' => 'required',
            'feedback' => 'required',
            'status' => 'required'
        ]);

        if ($request->hasFile('client_image')) {
            // Get the file from the request
            $image = $request->file('client_image');


            $imageName = Str::uuid(). '.' . $image->getClientOriginalExtension();

            // Store the image in the storage folder
            $path = $image->storeAs('testimonial-image', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'

            $imageData = Testimonial::find($id);

            if ($imageData->client_image) {
                Storage::disk('public')->delete('testimonial-image/' . $imageData->client_image);
            }

            // if (Storage::exists('testimonial-image/' . $imageData->client_image)) {
            //     Storage::delete('testimonial-image/' . $imageData->client_image);
            // }

            Testimonial::where('id', $id)->update([
                'name' => $request->name,
                'designation' => $request->designation,
                'feedback' => $request->feedback,
                'client_image' => $imageName,
                'status' =>  $request->status
            ]);
        } else {
            Testimonial::where('id', $id)->update([
                'name' => $request->name,
                'designation' => $request->designation,
                'feedback' => $request->feedback,
                'status' =>  $request->status
            ]);
        }

        return Redirect::back()->with('success', 'Data save successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $testimonial  = Testimonial::find($id);
        $testimonial->delete();
        return Redirect::route('testimonials.index')->with('success', 'Data delete successfully');
    }
}
