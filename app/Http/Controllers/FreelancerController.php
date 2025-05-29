<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FreelancerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->where('id', '!=', 1)->where('role_id', 3)->orderBy('id', 'desc')->paginate(10);
        return view('freelancer.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::with(['role', 'userDetails', 'country', 'skills', 'subCategory', 'getHowLikeToWork', 'getHowLikeToWork.howLikeTowork'])->find($id);
            if (!$user) {
                throw new \Exception('something went wrong');
            }
            return view('freelancer.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('error.page')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $user = User::where('role_id', 3)->where('id', $id)->first();
        return view('freelancer.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:0,1',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image validation
            'star_rating' => 'nullable|numeric|between:0,5',
            'total_hours' => 'nullable|integer|min:0',
        ]);

        // Find user
        $user = User::findOrFail($id);

        // Default image (keep existing)
        $imageName = $user->profile_image;

        // Process and store new image if uploaded
        if ($request->hasFile('profile_image')) {
            try {
                $manager = new ImageManager(new Driver());

                $image = $request->file('profile_image');
                $imageName = Str::uuid() . '.webp';

                // Convert image to WebP format
                $processedImage = $manager->read($image->getRealPath())
                    ->encode(new WebpEncoder(quality: 80));

                // Store new image
                Storage::disk('public')->put('user-image/' . $imageName, $processedImage);

                // Delete old image if exists
                if (!empty($user->profile_image)) {
                    Storage::disk('public')->delete('user-image/' . $user->profile_image);
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }

        // Update user details
        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'user_status' => $request->status,
            'profile_image' => $imageName,
            'star_rating' => $request->star_rating,
            'total_hours' => $request->total_hours,
        ]);

        return redirect()->route('freelancer.edit', $id)->with('success', 'Freelancer details updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     // Start a database transaction
    //     DB::beginTransaction();

    //     try {
    //         // Find the user
    //         $user = User::findOrFail($id);

    //         // Delete related data for the user
    //         $user->userDetails()->delete();
    //         $user->userEducation()->delete();
    //         $user->userExperiences()->delete();
    //         $user->userLanguage()->delete();
    //         $user->resume()->delete();
    //         $user->skills()->detach();
    //         $user->subCategory()->detach();
    //         $user->getHowLikeToWork()->detach();

    //         // Delete the user
    //         $user->delete();

    //         // Commit the transaction
    //         DB::commit();

    //         // Redirect back with success message
    //         return Redirect::route('freelancer.index')->with('success', 'Freelancer deleted successfully.');
    //     } catch (\Exception $e) {


    //         // Rollback the transaction if something goes wrong
    //         DB::rollBack();

    //         // Redirect back with error message
    //         return Redirect::route('freelancer.index')->with('error', 'Something went wrong. Please try again.');
    //     }
    // }

    public function destroy(string $id)
    {
        DB::beginTransaction(); // Start transaction

        try {
            // Find the user
            $user = User::findOrFail($id);

            // Delete related HasOne records
            if ($user->userDetails) $user->userDetails()->delete();
            if ($user->resume) $user->resume()->delete();

            // Delete related HasMany records
            if ($user->userEducation()->exists()) $user->userEducation()->delete();
            if ($user->userExperiences()->exists()) $user->userExperiences()->delete();
            if ($user->getHowLikeToWork()->exists()) $user->getHowLikeToWork()->delete();

            // Many-to-Many relationships (Use detach)
            if ($user->skills()->exists()) $user->skills()->detach();
            if ($user->subCategory()->exists()) $user->subCategory()->detach();

            UserLanguage::where('user_id', $id)->delete();
            // if ($user->userLanguage()->exists()) $user->userLanguage()->detach();

            // Get related proposals, contracts, milestones, and chats
            $proposalIds = DB::table('proposals')->where('freelancer_id', $id)->pluck('id');
            $contractIds = DB::table('contracts')->whereIn('proposal_id', $proposalIds)->pluck('id');
            $milestoneIds = DB::table('milestones')->whereIn('contract_id', $contractIds)->pluck('id');
            $chatIds = DB::table('chats')->where('user_one_id', $id)->orWhere('user_two_id', $id)->pluck('id');

            // 🔹 Fix: Delete ratings before contracts
            if ($contractIds->isNotEmpty()) {
                DB::table('ratings')->whereIn('contract_id', $contractIds)->delete();
            } else {
                DB::table('ratings')->where('user_id', $id)->delete();
            }

            // Delete related data only if IDs exist
            if ($chatIds->isNotEmpty()) {
                // Fetch messages that contain media files (not text)
                $messagesWithFiles = DB::table('messages')
                    ->whereIn('chat_id', $chatIds)
                    ->where('file_type', '!=', 'text')
                    ->get();

                // Delete files from storage
                foreach ($messagesWithFiles as $message) {
                    if (!empty($message->file_path)) {
                        Storage::disk('public')->delete("messages/{$message->file_path}");
                    }
                }

                // Now delete messages and chats
                DB::table('messages')->whereIn('chat_id', $chatIds)->delete();
                DB::table('chats')->whereIn('id', $chatIds)->delete();
            }

            if ($milestoneIds->isNotEmpty()) {
                DB::table('mileston_payments')->whereIn('milestone_id', $milestoneIds)->delete();
                DB::table('milestones')->whereIn('id', $milestoneIds)->delete();
            }

            if ($contractIds->isNotEmpty()) {
                DB::table('contracts')->whereIn('id', $contractIds)->delete();
            }



            if ($proposalIds->isNotEmpty()) {
                DB::table('proposals')->whereIn('id', $proposalIds)->delete();
            }
            if ($user->profile_image != "") {
                Storage::disk('public')->delete("user-image/{$user->profile_image}");
            }
            // Delete the user
            $user->delete();

            DB::commit(); // Commit transaction

            return Redirect::route('freelancer.index')->with('success', 'Freelancer and related data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if any error occurs
            return Redirect::route('freelancer.index')->with('error', $e->getMessage());
        }
    }


    public function changeStatus($id)
    {

        if (empty($id)) {
            return back()->with('error', 'Some thing went wrong');
        }
        $user = User::find($id);
        if (empty($user)) {
            return back()->with('error', 'Some thing went wrong');
        }
        $user->user_status = !$user->user_status;
        $user->save();
        return back()->with('success', 'Status change successfully.');
        // return Redirect::route('freelancer.show',$id)->with('success', 'Profile image update successfully');
    }
}
