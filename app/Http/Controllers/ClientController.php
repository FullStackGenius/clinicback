<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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

        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);
            $user->userDetails()->delete();
            $projects = Project::where('user_id', $id)->get();
            if ($projects->isNotEmpty()) {
                $projects->each(function ($project) {
                    $project->projectSkill()->detach();
                    $project->delete();
                });
            }
            $user->delete();
            DB::commit();
            return redirect()->route('client.index')->with('success', 'User and related data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('client.index')->with('error', 'Something went wrong, please try again.');
        }
    }
}
