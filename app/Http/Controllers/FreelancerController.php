<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the user
            $user = User::findOrFail($id);

            // Delete related data for the user
            $user->userDetails()->delete();
            $user->userEducation()->delete();
            $user->userExperiences()->delete();
            $user->userLanguage()->delete();
            $user->resume()->delete();
            $user->skills()->detach();
            $user->subCategory()->detach();
            $user->getHowLikeToWork()->detach();

            // Delete the user
            $user->delete();

            // Commit the transaction
            DB::commit();

            // Redirect back with success message
            return Redirect::route('freelancer.index')->with('success', 'Freelancer deleted successfully.');
        } catch (\Exception $e) {


            // Rollback the transaction if something goes wrong
            DB::rollBack();

            // Redirect back with error message
            return Redirect::route('freelancer.index')->with('error', 'Something went wrong. Please try again.');
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
