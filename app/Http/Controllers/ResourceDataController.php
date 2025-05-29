<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ResourceCategory;
use App\Models\ResourceData;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class ResourceDataController extends Controller
{

    public function index()
    {
        $resourceData  = ResourceData::with('resourceCategory')->orderBy('id', 'desc')->paginate(15);
        return view('resource-data.index', compact('resourceData'));
    }

    public function create()
    {
        $resourceCategorys  = ResourceCategory::where('status', 1)->get();
        return view('resource-data.create', compact('resourceCategorys'));
    }

    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'category' => 'required',
    //         'title' => 'required',
    //         'short_description' => 'required',
    //         'long_description' => 'required',
    //         'status' => 'required',
    //         'resource_image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
    //     ]);

    //     if ($request->hasFile('resource_image')) {
    //         $image = $request->file('resource_image');
    //         $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
    //         $path = $image->storeAs('resource-image', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
    //     }
    //     ResourceData::create([
    //         'title' => $request->title,
    //         'short_description' => $request->short_description,
    //         'description' => $request->long_description,
    //         'resource_image' => $imageName,
    //         'status' => $request->status,
    //         'resource_category_id' => $request->category,
    //     ]);
    //     return Redirect::route('resources.index')->with('success', 'resources create successfully');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'title' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'status' => 'required',
            'resource_image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        try {
            // Initialize Image Manager
            $manager = new ImageManager(new Driver());

            $imageName = null;

            if ($request->hasFile('resource_image')) {
                // Get the uploaded file
                $image = $request->file('resource_image');

                // Generate a unique filename with WebP format
                $imageName = Str::uuid() . '.webp';

                // Process & encode the image to WebP format
                $processedImage = $manager->read($image->getRealPath())
                    ->encode(new WebpEncoder(quality: 80));

                // Store the processed image
                Storage::disk('public')->put("resource-image/{$imageName}", $processedImage);
            }

            // Create new resource data entry
            ResourceData::create([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'description' => $request->long_description,
                'resource_image' => $imageName,
                'status' => $request->status,
                'resource_category_id' => $request->category,
            ]);

            return Redirect::route('resources.index')->with('success', 'Resource created successfully');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function show(string $id) {}


    public function edit(string $id)
    {
        $resourceData  = ResourceData::find($id);
        $categorys  = ResourceCategory::where('status', 1)->get();
        return view('resource-data.edit', compact(['resourceData', 'categorys']));
    }


    // public function update(Request $request, string $id)
    // {
    //     if (isset($request->long_description) && trim($request->long_description) === "<br>") {
    //         $request->merge(['long_description' => '']);
    //     }
    //     $request->validate([
    //         'category' => 'required',
    //         'title' => 'required',
    //         'short_description' => 'required',
    //         'long_description' => 'required',
    //         'status' => 'required',
    //     ]);

    //     if ($request->hasFile('resource_image')) {
    //         $image = $request->file('resource_image');
    //         $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
    //         $path = $image->storeAs('resource-image', $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
    //         $imageData = ResourceData::find($id);
    //         if ($imageData->resource_image) {
    //             Storage::disk('public')->delete('resource-image/' . $imageData->resource_image);
    //         }
    //         ResourceData::where('id', $id)->update([
    //             'title' => $request->title,
    //             'short_description' => $request->short_description,
    //             'description' => $request->long_description,
    //             'resource_image' => $imageName,
    //             'status' => $request->status,
    //             'resource_category_id' => $request->category
    //         ]);
    //     } else {
    //         ResourceData::where('id', $id)->update([
    //             'title' => $request->title,
    //             'short_description' => $request->short_description,
    //             'description' => $request->long_description,
    //             'status' => $request->status,
    //             'resource_category_id' => $request->category
    //         ]);
    //     }
    //     return Redirect::back()->with('success', 'Data save successfully');
    // }

    public function update(Request $request, string $id)
    {
        if (isset($request->long_description) && trim($request->long_description) === "<br>") {
            $request->merge(['long_description' => '']);
        }

        $request->validate([
            'category' => 'required',
            'title' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'status' => 'required',
        ]);

        try {
            $imageName = null;
            $resource = ResourceData::findOrFail($id);

            if ($request->hasFile('resource_image')) {
                // Initialize Image Manager
                $manager = new ImageManager(new Driver());

                // Get the uploaded image
                $image = $request->file('resource_image');

                // Generate a unique filename with WebP extension
                $imageName = Str::uuid() . '.webp';

                // Process & encode the image to WebP format
                $processedImage = $manager->read($image->getRealPath())
                    ->encode(new WebpEncoder(quality: 80));

                // Store the optimized image
                Storage::disk('public')->put("resource-image/{$imageName}", $processedImage);

                // Delete the old image if it exists
                if (!empty($resource->resource_image)) {
                    Storage::disk('public')->delete("resource-image/{$resource->resource_image}");
                }

                $resource->update([
                    'resource_image' => $imageName,
                ]);
            }

            // Update the resource details
            $resource->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'description' => $request->long_description,
                'status' => $request->status,
                'resource_category_id' => $request->category,
            ]);

            return Redirect::back()->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $resourceData = ResourceData::findOrFail($id);
            if ($resourceData->resource_image) {
                Storage::disk('public')->delete('resource-image/' . $resourceData->resource_image);
            }
            $resourceData->delete();
            DB::commit();
            return Redirect::route('resources.index')->with('success', 'resource deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('resources.index')->with('error', 'Something went wrong. Please try again.');
        }
    }
}
