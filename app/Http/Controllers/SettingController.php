<?php

namespace App\Http\Controllers;

use App\Constants\ProjectConstants;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    
    public function index()
    {
        $setting = Setting::where('id',1)->first();
        return view('settings.index',compact('setting'));
    }

    
    public function store(Request $request)
    {
       
        $request->validate([
            'facebook_url' => 'required|url',
            'instagram_url' => 'required|url',
            'twitter_url' => 'required|url',
            'linkedin_url' => 'required|url',
            'website_logo' => 'sometimes|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
       
        if ($request->hasFile('website_logo')) {
            // Get the file from the request
            $image = $request->file('website_logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Store the image in the storage folder
            $path = $image->storeAs('website-logo', $imageName, 'public'); // 'images' is the folder within 'storage/app/public' 
        }

            $setting = Setting::find(ProjectConstants::SETTING_TABLE_ID);
            if(empty($setting)){
                return Redirect::back()->with('error', 'something went wrong');
            }
            $setting->facebook_link = $request->facebook_url;
            $setting->instagram_link = $request->instagram_url;
            $setting->twitter_link = $request->twitter_url;
            $setting->linkedin_link = $request->linkedin_url;
            $setting->website_logo = isset($imageName)?$imageName:$setting->website_logo;
            $setting->save();
          
        return Redirect::back()->with('success', 'Data save successfully');
    }

    
}
