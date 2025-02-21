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
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    public function index($userId=null)
    {
        if(isset($userId) && !empty($userId)){
            $jobs = Project::where('user_id',$userId)->where('project_status', 3)->with('clientUser')->paginate(10);
        }else{
            $jobs = Project::where('project_status',3)->with('clientUser')->paginate(10);
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
        Mail::to($proposal->freelancerUser->email)->send(new JobProposalAccepted($project, $proposal, $contract));
        Mail::to(@User::find(1)->email)->send(new ProposalAcceptedByFreelancer($project, $proposal, $contract));
        return Redirect::back()->with('success', 'Project assignto user Successfully');
    }

    public function getContractDetailAjax(Request $request)
    {
        try {
          $contract = Contract::with(['proposal','project','project.clientUser','proposal.freelancerUser'])->find($request->contract_id);
            // Sample successful response
            return response()->json([
                'success' => true,
                'message' => 'Data processed successfully!',
                'data' => $contract
            ],200);
            
        } catch (\Exception $e) {
            // Handle any errors or exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500); // 500 is the HTTP status code for server errors
        }
    }
}
