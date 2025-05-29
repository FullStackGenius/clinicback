<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Resume;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserLanguage;
use App\Models\UserLikeToWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinetLists =  User::with('role')->where('id', '!=', 1)->where('role_id', 2)->orderBy('id', 'desc')->paginate(10);
        return view('client.index', compact('clinetLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $client =  User::with('role')->with('country')->find($id);
            if (!$client) {
                throw new \Exception('something went wrong');
            }
            return view('client.show', compact('client'));
        } catch (\Exception $e) {
            return redirect()->route('error.page')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('role_id', 2)->where('id', $id)->first();
        return view('client.edit', compact('user'));
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
        ]);

        return redirect()->route('client.edit', $id)->with('success', 'client details updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);
            // $user->userDetails()->delete();
            // $projects = Project::where('user_id', $id)->get();
            // if ($projects->isNotEmpty()) {
            //     $projects->each(function ($project) {
            //         $project->projectSkill()->detach();
            //         $project->delete();
            //     });
            // }
            // $user->delete();
            // DB::commit();
            $userId = $id;
            // Step 1: Fetch the user's projects
            $projectIds = DB::table('projects')->where('user_id', $userId)->pluck('id');

            if ($projectIds->isNotEmpty()) {
                // Step 2: Delete project-related data
                DB::table('project_categories')->whereIn('project_id', $projectIds)->delete();
                DB::table('project_skills')->whereIn('project_id', $projectIds)->delete();
                DB::table('project_sub_categories')->whereIn('project_id', $projectIds)->delete();

                // Step 3: Get proposal and contract IDs related to projects
                $proposalIds = DB::table('proposals')->whereIn('project_id', $projectIds)->pluck('id');
                $contractIds = DB::table('contracts')->whereIn('proposal_id', $proposalIds)->pluck('id');

                // Step 4: Delete contract-related data
                if ($contractIds->isNotEmpty()) {
                    DB::table('ratings')->whereIn('contract_id', $contractIds)->delete();
                    DB::table('milestones')->whereIn('contract_id', $contractIds)->delete();
                    DB::table('contracts')->whereIn('id', $contractIds)->delete();
                } else {
                    DB::table('ratings')->where('user_id', $userId)->delete();
                }

                // Step 5: Delete proposals
                if ($proposalIds->isNotEmpty()) {
                    DB::table('proposals')->whereIn('id', $proposalIds)->delete();
                }

                // Step 6: Delete the projects
                DB::table('projects')->whereIn('id', $projectIds)->delete();
            }

            // Step 7: Get chat IDs
            $chatIds = DB::table('chats')->where('user_one_id', $userId)->orWhere('user_two_id', $userId)->pluck('id');

            // Step 8: Delete messages and their media files
            if ($chatIds->isNotEmpty()) {
                $messagesWithFiles = DB::table('messages')->whereIn('chat_id', $chatIds)->where('file_type', '!=', 'text')->get();

                foreach ($messagesWithFiles as $message) {
                    if (!empty($message->file_path)) {
                        Storage::disk('public')->delete("messages/{$message->file_path}");
                    }
                }

                DB::table('messages')->whereIn('chat_id', $chatIds)->delete();
                DB::table('chats')->whereIn('id', $chatIds)->delete();
            }

            // Step 9: Delete the user
            DB::table('user_skill')->where('user_id',$userId)->delete();
            UserLikeToWork::where('user_id', $userId)->delete();
            UserLanguage::where('user_id', $userId)->delete();
            UserExperience::where('user_id', $userId)->delete();
            UserEducation::where('user_id', $userId)->delete();
            DB::table('user_subcategory')->where('user_id', $userId)->delete();
            Resume::where('user_id', $userId)->delete();
            UserDetail::where('user_id', $userId)->delete();
            if ($user->profile_image != "") {
                Storage::disk('public')->delete("user-image/{$user->profile_image}");
            }
            User::where('id', $userId)->delete();

            DB::commit();



            return redirect()->route('client.index')->with('success', 'Client and related data deleted successfully.');
        } catch (\Exception $e) {
            Log::channel('daily')->info('destroy client  Api log \n: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->route('client.index')->with('error','Something went wrong, please try again.');
        }
    }
}
