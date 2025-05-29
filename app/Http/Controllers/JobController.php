<?php

namespace App\Http\Controllers;

use App\Constants\ProjectConstants;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Mail\JobProposalAccepted;
use App\Mail\ProposalAcceptedByFreelancer;
use App\Models\Category;
use App\Models\ProjectDuration;
use App\Models\ProjectExperience;
use App\Models\ProjectType;
use App\Models\Skill;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;

class JobController extends Controller
{

    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function index($userId = null)
    {
        if (isset($userId) && !empty($userId)) {
            $jobs = Project::where('user_id', $userId)->where('project_status', 3)->with('clientUser')->paginate(10);
        } else {
            $jobs = Project::where('project_status', 3)->with('clientUser')->paginate(10);
        }
        // $jobs = Project::where('project_status', 3)->with('clientUser')->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        try {
            $jobDetails = Project::with(['contracts.proposal.freelancerUser', 'clientUser', 'projectSkill', 'projectScope', 'projectCategory', 'projectSubCategory', 'projectDuration', 'projectExperience'])->find($id);
            if (!$jobDetails) {
                throw new \Exception('something went wrong');
            }
            $freelancers = User::where('role_id', ProjectConstants::ROLE_CLIENT)->where('user_status', ProjectConstants::STATUS_ACTIVE)->get();

            return view('jobs.show', compact(['jobDetails', 'freelancers']));
        } catch (\Exception $e) {
            return redirect()->route('error.page')->with('error', $e->getMessage());
        }
    }

    public function jobProposal($id)
    {
        try {
            //  $excludedProposalIds = Contract::pluck('proposal_id')->toArray();
            $proposals = Proposal::with(['contract'])->where('project_id', $id)
                //  ->whereNotIn('id', $excludedProposalIds)
                ->paginate(10);
            $project = Project::find($id);
            //$freelancers = User::where('role_id', ProjectConstants::ROLE_CLIENT)->where('user_status', ProjectConstants::STATUS_ACTIVE)->get();
            return view('jobs.jobs-proposal', compact(['proposals', 'project']));
        } catch (\Exception $e) {
            return redirect()->route('error.page')->with('error', $e->getMessage());
        }
    }

    public function assignJobToFreeklancer(Request $request)
    {

        //$futureDate = now()->addDays(10);

        $request->validate([
            'project_id' => 'required',
            'proposal_id' => 'required',
            'project_start_date' => 'required',
            'project_end_date' => 'required',
            'contract_type' => 'required',
            'amount' => 'required',
            'payment_type' => 'required'

        ]);


        $project = Project::with('clientUser')->find($request->project_id);
        if (!empty($project)) {
            if ($project->project_status != 3) {
                return Redirect::back()->with('error', 'only publish project will assign to freelancer. ');
            }
        }
        $proposal = Proposal::with('freelancerUser')->find($request->proposal_id);
        $contract =  Contract::create([
            'project_id' => $request->project_id,
            'proposal_id' => $request->proposal_id,
            'freelancer_id' => $proposal->freelancer_id,
            'started_at' => $request->project_start_date,
            'ended_at' => $request->project_end_date,
            'type' =>  $request->contract_type,
            'status' => 'active',
            'amount' => $request->amount,
            'payment_type' => $request->payment_type
        ]);
        $contract = Contract::find($contract->id);
        $this->mailService->safeSend($proposal->freelancerUser->email,new JobProposalAccepted($project, $proposal, $contract),'assignJobToFreeklancer 1');
        $this->mailService->safeSend(@User::find(1)->email,new ProposalAcceptedByFreelancer($project, $proposal, $contract),'assignJobToFreeklancer 2');
        // Mail::to($proposal->freelancerUser->email)->send(new JobProposalAccepted($project, $proposal, $contract));
        // Mail::to(@User::find(1)->email)->send(new ProposalAcceptedByFreelancer($project, $proposal, $contract));
        return Redirect::back()->with('success', 'Project assignto user Successfully');
    }

    public function getContractDetailAjax(Request $request)
    {
        try {
            $contract = Contract::with(['proposal', 'project', 'project.clientUser', 'proposal.freelancerUser'])->find($request->contract_id);
            // Sample successful response
            return response()->json([
                'success' => true,
                'message' => 'Data processed successfully!',
                'data' => $contract
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors or exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500); // 500 is the HTTP status code for server errors
        }
    }


    public function edit($id)
    {

        $project  = Project::with(['projectSkill', 'projectCategory', 'projectSubCategory',])->find($id);
        $skills  = SubCategory::where('subcategory_status', 1)->get();
        $sectors  = Category::where('category_status', 1)->get();
        $certifications  = Skill::where('status', 1)->get();
        $projectExperiences  = ProjectExperience::where('status', 1)->get();
        $projectDurations  = ProjectDuration::where('status', 1)->get();
        $projectTypes  = ProjectType::where('status', 1)->get();

        return view('jobs.edit', compact(['project', 'skills', 'sectors', 'certifications', 'projectExperiences', 'projectDurations', 'projectTypes']));
    }


    public function update(Request $request, $id)
    {
        if (isset($request->description) && trim($request->description) === "<br>") {
            $request->merge(['description' => '']);
        }

        $request->validate([
            'title' => 'required',
            'projectType' => 'required',
            'skills' => 'required',
            'description' => 'required',
            'sectors' => 'required',
            'certifications' => 'required',
            'projectExperience' => 'required',
            'projectDuration' => 'required',
            'status' => 'required'
        ]);
        try {

            $project = Project::find($id);
            if (!$project) {
                throw new \Exception('something went wrong');
            }
            if (!empty($request->certifications)) {
                $project->projectSkill()->sync($request->certifications);
            }
            if (!empty($request->sectors)) {
                $project->projectCategory()->sync($request->sectors);
            }
            if (!empty($request->skills)) {
                $project->projectSubCategory()->sync($request->skills);
            }
            $project->title = $request->title;
            $project->description = $request->description;
            $project->project_status = $request->status;
            $project->project_type_id = $request->projectType;
            $project->project_duration_id = $request->projectDuration;
            $project->project_experience_id = $request->projectExperience;
            $project->save();
            $project->accounting_certifications = $project->projectSkill;
            $project->accounting_sectors = $project->projectCategory;
            $project->accounting_skills = $project->projectSubCategory;

            return Redirect::route('jobs.edit', $id)->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            Log::channel('daily')->info('update job from admin log \n: ' . $e->getMessage());
            return Redirect::route('jobs.edit', $id)->with('error', 'somethings went worng');
        }
    }

    public function destroy($id)
    {

        DB::beginTransaction();

        try {
            $projectId =  $id;
            // Get all contract IDs related to the project
            $contractIds = DB::table('contracts')
                ->where('project_id', $projectId)
                ->pluck('id');

            // Get all milestone IDs related to those contracts
            $milestoneIds = DB::table('milestones')
                ->whereIn('contract_id', $contractIds)
                ->pluck('id');

            // Delete milestone payments
            DB::table('mileston_payments')
                ->whereIn('milestone_id', $milestoneIds)
                ->delete();

            // Delete milestones
            DB::table('milestones')
                ->whereIn('contract_id', $contractIds)
                ->delete();

            // Delete ratings
            DB::table('ratings')
                ->whereIn('contract_id', $contractIds)
                ->delete();

            // Delete contracts
            DB::table('contracts')
                ->where('project_id', $projectId)
                ->delete();

            // Delete proposals
            DB::table('proposals')
                ->where('project_id', $projectId)
                ->delete();

            // Delete from pivot tables
            DB::table('project_categories')
                ->where('project_id', $projectId)
                ->delete();

            DB::table('project_skills')
                ->where('project_id', $projectId)
                ->delete();

            DB::table('project_sub_categories')
                ->where('project_id', $projectId)
                ->delete();

            // Finally delete the project
            DB::table('projects')
                ->where('id', $projectId)
                ->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Project and all related data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('daily')->info('deleted job and their related data from admin log \n: ' . $e->getMessage() . ' \n ');
            return redirect()->back()->with('error', 'Failed to delete project and related data.');
        }
    }
}
