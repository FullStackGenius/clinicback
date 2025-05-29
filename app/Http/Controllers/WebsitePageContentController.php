<?php

namespace App\Http\Controllers;

use App\Constants\ProjectConstants;
use App\Models\WebsitePageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class WebsitePageContentController extends Controller
{
    public function accountSectionContent()
    {
        $websitePageContent =  WebsitePageContent::where('section_name', 'home-account-section')->first();
        return view('home-page-section.account-section', compact('websitePageContent'));
    }

    // public function accountSectionContentStore(Request $request)
    // {

    //     $request->validate([
    //         'content' => 'required',
    //         'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', 
    //     ]);

    //     if ($request->hasFile('content_image')) {
    //         $image = $request->file('content_image');
    //         $imageName = Str::uuid(). '.' . $image->getClientOriginalExtension();
    //         $path = $image->storeAs(ProjectConstants::CONTENT_IMAGE, $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
    //         $websitePageContent =  WebsitePageContent::where('section_name', 'home-account-section')->first();

    //         if ($websitePageContent->content_image) {
    //             Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE.'/' . $websitePageContent->content_image);
    //         }
    //         // if (Storage::exists('content-image/' . $websitePageContent->content_image)) {
    //         //     Storage::delete('content-image/' . $websitePageContent->content_image);
    //         // }
    //         WebsitePageContent::where('section_name', 'home-account-section')->update([
    //             'content' => $request->content,
    //             'content_image' => $imageName,
    //         ]);
    //     } else {
    //         WebsitePageContent::where('section_name', 'home-account-section')->update([
    //             'content' => $request->content,
    //         ]);
    //     }


    //     return Redirect::back()->with('success', 'Data save successfully');
    // }


    public function accountSectionContentStore(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            // Fetch or create the website page content entry
            $websitePageContent = WebsitePageContent::where('section_name', 'home-account-section')->firstOrFail();

            $imageName = $websitePageContent->content_image; // Keep the old image by default

            if ($request->hasFile('content_image')) {
                // Initialize Intervention Image
                $manager = new ImageManager(new Driver());

                // Get the uploaded file
                $image = $request->file('content_image');

                // Generate a unique filename with WebP format
                $imageName = Str::uuid() . '.webp';

                // Process & encode the image to WebP format
                $processedImage = $manager->read($image->getRealPath())
                    ->encode(new WebpEncoder(quality: 80));

                // Store the processed image
                Storage::disk('public')->put(ProjectConstants::CONTENT_IMAGE . "/{$imageName}", $processedImage);

                // Delete old image if it exists
                if ($websitePageContent->content_image) {
                    Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE . '/' . $websitePageContent->content_image);
                }
            }

            // Update only if necessary
            $updateData = ['content' => $request->content];
            if ($request->hasFile('content_image')) {
                $updateData['content_image'] = $imageName;
            }

            $websitePageContent->update($updateData);

            return Redirect::back()->with('success', 'Data saved successfully');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function flexibleSectionContent()
    {
        $websitePageContent =  WebsitePageContent::where('section_name', 'flexible-account-section')->first();
        return view('home-page-section.flexible-section', compact('websitePageContent'));
    }

    // public function flexibleSectionContentStore(Request $request)
    // {

    //     $request->validate([
    //         'content' => 'required',
    //         'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    //     ]);

    //     if ($request->hasFile('content_image')) {
    //         // Get the file from the request
    //         $image = $request->file('content_image');
    //         $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
    //         $path = $image->storeAs(ProjectConstants::CONTENT_IMAGE, $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
    //         $websitePageContent =  WebsitePageContent::where('section_name', 'flexible-account-section')->first();

    //         if ($websitePageContent->content_image) {
    //             Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE . '/' . $websitePageContent->content_image);
    //         }

    //         // if (Storage::exists('content-image/' . $websitePageContent->content_image)) {
    //         //     Storage::delete('content-image/' . $websitePageContent->content_image);
    //         // }
    //         WebsitePageContent::where('section_name', 'flexible-account-section')->update([
    //             'content' => $request->content,
    //             'content_image' => $imageName,
    //         ]);
    //     } else {
    //         WebsitePageContent::where('section_name', 'flexible-account-section')->update([
    //             'content' => $request->content,
    //         ]);
    //     }


    //     return Redirect::back()->with('success', 'Data save successfully');
    // }


    public function flexibleSectionContentStore(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            // Fetch or create the website page content entry
            $websitePageContent = WebsitePageContent::where('section_name', 'flexible-account-section')->firstOrFail();

            $imageName = $websitePageContent->content_image; // Keep the old image by default

            if ($request->hasFile('content_image')) {
                // Initialize Intervention Image
                $manager = new ImageManager(new Driver());

                // Get the uploaded file
                $image = $request->file('content_image');

                // Generate a unique filename with WebP format
                $imageName = Str::uuid() . '.webp';

                // Process & encode the image to WebP format
                $processedImage = $manager->read($image->getRealPath())
                    ->encode(new WebpEncoder(quality: 80));

                // Store the processed image
                Storage::disk('public')->put(ProjectConstants::CONTENT_IMAGE . "/{$imageName}", $processedImage);

                // Delete old image if it exists
                if ($websitePageContent->content_image) {
                    Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE . '/' . $websitePageContent->content_image);
                }
            }

            // Update only if necessary
            $updateData = ['content' => $request->content];
            if ($request->hasFile('content_image')) {
                $updateData['content_image'] = $imageName;
            }

            $websitePageContent->update($updateData);

            return Redirect::back()->with('success', 'Data saved successfully');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function contractSectionContent()
    {
        $websitePageContents =  WebsitePageContent::where('section_name', 'contract-section-data')->get();
        return view('home-page-section.contract-section.index', compact('websitePageContents'));
    }

    public function contractSectionContentEdit($id)
    {
        $websitePageContent =  WebsitePageContent::where('id', $id)->where('section_name', 'contract-section-data')->first();
        return view('home-page-section.contract-section.edit', compact('websitePageContent'));
    }

    public function contractSectionContentUpdate(Request $request, $id)
    {

        if (isset($request->content) && trim($request->content) === "<br>") {
            $request->merge(['content' => '']);
        }
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $websitePageContent =  WebsitePageContent::where('id', $id)->where('section_name', 'contract-section-data')->first();
        $websitePageContent->title = $request->title;
        $websitePageContent->content = $request->content;
        $websitePageContent->save();
        return Redirect::route('contract-section.edit', $id)->with('success', 'Data save successfully');
    }


    public function learnHowToHireContent()
    {
        $websitePageContent =  WebsitePageContent::where('section_name', 'learn-how-to-hire')->first();
        return view('home-page-section.learn-how-to-hire', compact('websitePageContent'));
    }

    // public function learnHowToHireContentStore(Request $request)
    // {

    //     $request->validate([
    //         'content' => 'required',
    //         'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    //     ]);

    //     if ($request->hasFile('content_image')) {
    //         $image = $request->file('content_image');
    //         $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
    //         $path = $image->storeAs(ProjectConstants::CONTENT_IMAGE, $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
    //         $websitePageContent =  WebsitePageContent::where('section_name', 'learn-how-to-hire')->first();

    //         if ($websitePageContent->content_image) {
    //             Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE . '/' . $websitePageContent->content_image);
    //         }
    //         // if (Storage::exists('content-image/' . $websitePageContent->content_image)) {
    //         //     Storage::delete('content-image/' . $websitePageContent->content_image);
    //         // }
    //         WebsitePageContent::where('section_name', 'learn-how-to-hire')->update([
    //             'content' => $request->content,
    //             'content_image' => $imageName,
    //         ]);
    //     } else {
    //         WebsitePageContent::where('section_name', 'learn-how-to-hire')->update([
    //             'content' => $request->content,
    //         ]);
    //     }


    //     return Redirect::back()->with('success', 'Data save successfully');
    // }

    public function learnHowToHireContentStore(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            // Fetch or create the website page content entry
            $websitePageContent = WebsitePageContent::where('section_name', 'learn-how-to-hire')->firstOrFail();

            $imageName = $websitePageContent->content_image; // Keep the old image by default

            if ($request->hasFile('content_image')) {
                // Initialize Intervention Image
                $manager = new ImageManager(new Driver());

                // Get the uploaded file
                $image = $request->file('content_image');

                // Generate a unique filename with WebP format
                $imageName = Str::uuid() . '.webp';

                // Process & encode the image to WebP format
                $processedImage = $manager->read($image->getRealPath())
                    ->encode(new WebpEncoder(quality: 80));

                // Store the processed image
                Storage::disk('public')->put(ProjectConstants::CONTENT_IMAGE . "/{$imageName}", $processedImage);

                // Delete old image if it exists
                if ($websitePageContent->content_image) {
                    Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE . '/' . $websitePageContent->content_image);
                }
            }

            // Prepare update data
            $updateData = ['content' => $request->content];
            if ($request->hasFile('content_image')) {
                $updateData['content_image'] = $imageName;
            }

            // Update database record
            $websitePageContent->update($updateData);

            return Redirect::back()->with('success', 'Data saved successfully');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
