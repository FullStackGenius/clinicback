<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\ProjectProposalMail;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;

class FreelancerController extends BaseController
{

    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function sendProjectProposal(Request $request)
    {

        try {

            $request->validate([
                'project_id' => 'required|integer',
                // 'bid_amount' => 'required|integer',
                'cover_letter' => 'required',
            ]);
            $user = Auth::user();
            $proposal =  Proposal::where('project_id', $request->project_id)->where('freelancer_id', $user->id)->first();
            $project = Project::with(['clientUser', 'projectScope', 'projectDuration'])->find($request->project_id);
            if (!empty($proposal)) {
                $proposal->bid_amount  = 0;
                $proposal->cover_letter  = $request->cover_letter;
                $proposal->save();
            } else {
                $proposal = Proposal::create([
                    'project_id' => $request->project_id,
                    'bid_amount'  => 0,
                    'cover_letter'  => $request->cover_letter,
                    'freelancer_id' => $user->id
                ]);
            }
            $this->mailService->safeSend(User::find($project->user_id)->email,new ProjectProposalMail($project, $proposal, $user),'sendProjectProposal mail');
           // Mail::to(User::find($project->user_id)->email)->send(new ProjectProposalMail($project, $proposal, $user));

            return  $this->sendCommonResponse('false', "", $proposal, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('sendProjectProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getFreelancerProjectProposal(Request $request)
    {

        try {
            $user = Auth::user();
            $status = $request->get_status;
            if ($status == "all" || $status == "") {
                $proposals =  Proposal::with('project.clientUser')->where('freelancer_id', $user->id)->orderBy('id', 'desc')->paginate($request->per_page);
            } else {
                $proposals =  Proposal::with('project.clientUser')->where('freelancer_id', $user->id)->where('status', $status)->orderBy('id', 'desc')->paginate($request->per_page);
            }

            $data['proposals'] = $proposals;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getFreelancerProjectProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getFreelancerProjectProposalData(Request $request)
    {

        try {
            $user = Auth::user();
            $proposals =  Proposal::select(['project_id', 'freelancer_id'])->where('freelancer_id', $user->id)->get();
            $data['proposals'] = $proposals;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getFreelancerProjectProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getFreelancerContract(Request $request)
    {

        try {
            $user = Auth::user();
            $status = $request->get_status;
            if ($status == "all" || $status == "") {
                $contracts =  Contract::with(['client', 'project', 'proposal', 'freelancer'])->where('freelancer_id', $user->id)->orderBy('id', 'desc')->paginate($request->per_page);
            } else {
                $contracts =  Contract::with(['client', 'project', 'proposal', 'freelancer'])->where('freelancer_id', $user->id)->orderBy('id', 'desc')->where('status', $status)->paginate($request->per_page);
            }
            $data['contracts'] = $contracts;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getFreelancerContract  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getClientContract(Request $request)
    {

        try {
            $user = Auth::user();
            $status = $request->get_status;
            if ($status == "all" || $status == "") {
                $contracts =  Contract::with(['client', 'project', 'proposal', 'freelancer'])->where('client_id', $user->id)->orderBy('id', 'desc')->paginate($request->per_page);
            } else {
                $contracts =  Contract::with(['client', 'project', 'proposal', 'freelancer'])->where('client_id', $user->id)->orderBy('id', 'desc')->where('status', $status)->paginate($request->per_page);
            }

            $data['contracts'] = $contracts;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getFreelancerContract  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function editProjectProposal(Request $request)
    {

        try {

            $request->validate([
                'proposal_id' => 'required|integer',
                'cover_letter' => 'required',
                'isEdit' => 'required',
            ]);
            $user = Auth::user();
            $proposal =  Proposal::where('id', $request->proposal_id)->where('freelancer_id', $user->id)->first();
            if (empty($proposal)) {
               
                throw new \Exception('proposal not found');
            } 
            if($request->isEdit == false){
                $proposal->status  = 'pending';
            }
            $proposal->cover_letter  = $request->cover_letter;
            $proposal->save();
            return  $this->sendCommonResponse(false, "", $proposal, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('editProjectProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
