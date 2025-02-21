<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills  = Skill::orderBy('id', 'desc')->paginate(10);
        return view('skill.index', compact('skills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('skill.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:skills,name',
        ]);
        Skill::create([
            'name' => $request->name
        ]);
        return Redirect::route('skill.index')->with('success', 'Skill create successfully');
        // return Redirect::route('home')->with('error', 'You are not authorized.');
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
        $skill  = Skill::find($id);
        return view('skill.edit', compact('skill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:skills,name,' . $id,
        ]);
        $skill  = Skill::find($id);
        $skill->name = $request->name;
        $skill->status = $request->status;
        $skill->save();
        return Redirect::back()->with('success', 'Skill update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill  = Skill::find($id);
        $skill->delete();
        return Redirect::route('skill.index')->with('success', 'Skill delete successfully');
    }
}
