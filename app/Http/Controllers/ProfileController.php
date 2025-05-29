<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;


class ProfileController extends Controller
{


    public function dashboard(){
        $client = User::where('id','!=',1)->where('role_id',2)->count();
        $freelancer = User::where('id','!=',1)->where('role_id',3)->count();
        $jobs = Project::where('project_status',3)->count();
        return view('dashboard',compact('client','freelancer','jobs'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    // public function updateProfileImage(Request $request)
    // {
    //     $validated = $request->validate([
    //         'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max size: 2MB
    //     ]);

    //     // If validation passes, store the image in the 'public/user-image' folder
    //     $image = $request->file('profile_image');
    //     $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

    //     // Store the image in the storage folder
    //     $path = $image->storeAs('user-image', $imageName, 'public');
    //      $user = Auth::user();

    //     if ($user->profile_image) {
    //         Storage::disk('public')->delete('user-image/' . $user->profile_image);
    //     }
    //     $id = $user->id;
    //     User::where('id', $id)->update([
    //         'profile_image' => $imageName
    //     ]);

    //    return Redirect::route('profile.edit')->with('success', 'Profile image update successfully');
    // }

    public function updateProfileImage(Request $request)
{
    $validated = $request->validate([
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max size: 2MB
    ]);

    try {
        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return Redirect::route('profile.edit')->with('error', 'User not found');
        }

        // Initialize Image Manager
        $manager = new ImageManager(new Driver());

        // Get the uploaded image
        $image = $request->file('profile_image');

        // Generate a unique filename with WebP extension
        $imageName = Str::uuid() . '.webp';

        // Process & encode the image to WebP format
        $processedImage = $manager->read($image->getRealPath())
                                  ->encode(new WebpEncoder(quality: 80));

        // Store the optimized image
        Storage::disk('public')->put("user-image/{$imageName}", $processedImage);

        // Delete the old profile image if it exists
        if (!empty($user->profile_image)) {
            Storage::disk('public')->delete("user-image/{$user->profile_image}");
        }

        // Update user's profile image
        $user->update([
            'profile_image' => $imageName
        ]);

        return Redirect::route('profile.edit')->with('success', 'Profile image updated successfully');

    } catch (\Exception $e) {
        return Redirect::route('profile.edit')->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
}
