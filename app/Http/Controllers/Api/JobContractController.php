<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Proposal;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class JobContractController extends BaseController
{

    public function getJobs(Request $request)
    {
        try {
            $status =  $request->status;
            $data['jobs'] = Contract::with([
                'project.projectExperience:name,description,id',
                'project.projectSkill:name,id',
                'project.projectScope:name,description,id',
                'project.projectDuration:name,id',
                'freelancer:name,id,email'
            ])->with('freelancer:name,id,email')
                ->when(!empty($status), function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->get();
            return  $this->sendCommonResponse('false', "", $data, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getJobs  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function responseToProposal(Request $request)
    {
        try {
            $request->validate([
                'proposal_id' => 'required|integer',
                'proposal_status' => 'required|in:shortlisted,interview,hired,rejected',
                'started_at' => 'required_if:proposal_status,hired',
                'ended_at' => 'required_if:proposal_status,hired',
                'type' => 'required_if:proposal_status,hired|in:hourly,fixed',
                'amount' => 'required_if:proposal_status,hired',
                'payment_type' => 'required_if:proposal_status,hired|in:milestone,lump_sum',

            ]);
            $proposal =  Proposal::find($request->proposal_id);
            if (empty($proposal)) {
                throw new \Exception('something went wrong');
            }
            $proposal->status =  $request->proposal_status;
            $proposal->save();
            if ($request->proposal_status == 'hired') {
                $contract =  Contract::where('proposal_id', $proposal->id)->where('project_id', $proposal->project_id)->first();
                if (empty($contract)) {
                    Contract::create([
                        'project_id' => $proposal->project_id,
                        'proposal_id' => $proposal->id,
                        'started_at' => $request->started_at,
                        'ended_at' => $request->ended_at,
                        'amount' => $request->amount,
                        'status' => 'active',
                        'payment_type' => $request->payment_type
                    ]);
                }
            }

            return  $this->sendCommonResponse('false', "", $proposal, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('responseToProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
