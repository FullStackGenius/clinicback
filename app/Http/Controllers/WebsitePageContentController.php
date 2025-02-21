<?php

namespace App\Http\Controllers;

use App\Constants\ProjectConstants;
use App\Models\WebsitePageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebsitePageContentController extends Controller
{
    public function accountSectionContent()
    {
        $websitePageContent =  WebsitePageContent::where('section_name', 'home-account-section')->first();
        return view('home-page-section.account-section', compact('websitePageContent'));
    }

    public function accountSectionContentStore(Request $request)
    {

        $request->validate([
            'content' => 'required',
            'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', 
        ]);

        if ($request->hasFile('content_image')) {
            $image = $request->file('content_image');
            $imageName = Str::uuid(). '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs(ProjectConstants::CONTENT_IMAGE, $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
            $websitePageContent =  WebsitePageContent::where('section_name', 'home-account-section')->first();

            if ($websitePageContent->content_image) {
                Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE.'/' . $websitePageContent->content_image);
            }
            // if (Storage::exists('content-image/' . $websitePageContent->content_image)) {
            //     Storage::delete('content-image/' . $websitePageContent->content_image);
            // }
            WebsitePageContent::where('section_name', 'home-account-section')->update([
                'content' => $request->content,
                'content_image' => $imageName,
            ]);
        } else {
            WebsitePageContent::where('section_name', 'home-account-section')->update([
                'content' => $request->content,
            ]);
        }


        return Redirect::back()->with('success', 'Data save successfully');
    }


    public function flexibleSectionContent()
    {
        $websitePageContent =  WebsitePageContent::where('section_name', 'flexible-account-section')->first();
        return view('home-page-section.flexible-section', compact('websitePageContent'));
    }

    public function flexibleSectionContentStore(Request $request)
    {

        $request->validate([
            'content' => 'required',
            'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', 
        ]);

        if ($request->hasFile('content_image')) {
            // Get the file from the request
            $image = $request->file('content_image');
            $imageName = Str::uuid(). '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs(ProjectConstants::CONTENT_IMAGE, $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
            $websitePageContent =  WebsitePageContent::where('section_name', 'flexible-account-section')->first();

            if ($websitePageContent->content_image) {
                Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE.'/' . $websitePageContent->content_image);
            }

            // if (Storage::exists('content-image/' . $websitePageContent->content_image)) {
            //     Storage::delete('content-image/' . $websitePageContent->content_image);
            // }
            WebsitePageContent::where('section_name', 'flexible-account-section')->update([
                'content' => $request->content,
                'content_image' => $imageName,
            ]);
        } else {
            WebsitePageContent::where('section_name', 'flexible-account-section')->update([
                'content' => $request->content,
            ]);
        }


        return Redirect::back()->with('success', 'Data save successfully');
    }


    public function contractSectionContent()
    {
        $websitePageContents =  WebsitePageContent::where('section_name', 'contract-section-data')->get();
        return view('home-page-section.contract-section.index', compact('websitePageContents'));
    }

    public function contractSectionContentEdit($id)
    {
        $websitePageContent =  WebsitePageContent::where('id',$id)->where('section_name','contract-section-data')->first();
        return view('home-page-section.contract-section.edit', compact('websitePageContent'));
    }

    public function contractSectionContentUpdate(Request $request ,$id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $websitePageContent =  WebsitePageContent::where('id',$id)->where('section_name','contract-section-data')->first();
        $websitePageContent->title = $request->title;
        $websitePageContent->content = $request->content;
        $websitePageContent->save();
        return Redirect::route('contract-section.edit',$id)->with('success', 'Data save successfully');
    }


    public function learnHowToHireContent()
    {
        $websitePageContent =  WebsitePageContent::where('section_name', 'learn-how-to-hire')->first();
        return view('home-page-section.learn-how-to-hire', compact('websitePageContent'));
    }

    public function learnHowToHireContentStore(Request $request)
    {

        $request->validate([
            'content' => 'required',
            'content_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', 
        ]);

        if ($request->hasFile('content_image')) {
            $image = $request->file('content_image');
            $imageName = Str::uuid(). '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs(ProjectConstants::CONTENT_IMAGE, $imageName, 'public'); // 'images' is the folder within 'storage/app/public'
            $websitePageContent =  WebsitePageContent::where('section_name', 'learn-how-to-hire')->first();

            if ($websitePageContent->content_image) {
                Storage::disk('public')->delete(ProjectConstants::CONTENT_IMAGE.'/' . $websitePageContent->content_image);
            }
            // if (Storage::exists('content-image/' . $websitePageContent->content_image)) {
            //     Storage::delete('content-image/' . $websitePageContent->content_image);
            // }
            WebsitePageContent::where('section_name', 'learn-how-to-hire')->update([
                'content' => $request->content,
                'content_image' => $imageName,
            ]);
        } else {
            WebsitePageContent::where('section_name', 'learn-how-to-hire')->update([
                'content' => $request->content,
            ]);
        }


        return Redirect::back()->with('success', 'Data save successfully');
    }

    
}
