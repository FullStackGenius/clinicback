<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Contract;
use App\Models\Proposal;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FreelancerController extends BaseController
{
    public function sendProjectProposal(Request $request){

        try {
         
            $request->validate([
                'project_id' => 'required|integer',
                'bid_amount' => 'required|integer',
                'cover_letter' => 'required',
            ]);
             $user = Auth::user();
            $proposal =  Proposal::where('project_id',$request->project_id)->where('freelancer_id',$user->id)->first();
            if(!empty($proposal)){
                $proposal->bid_amount  = $request->bid_amount;
                $proposal->cover_letter  = $request->cover_letter;
                $proposal->save();
            }else{
                $proposal = Proposal::create([
                    'project_id' => $request->project_id,
                    'bid_amount'  => $request->bid_amount,
                    'cover_letter'  => $request->cover_letter,
                    'freelancer_id' => $user->id
                ]);
            }
            
          
            return  $this->sendCommonResponse('false', "", $proposal, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('sendProjectProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function getFreelancerProjectProposal(Request $request){

        try {
             $user = Auth::user();
            $proposals =  Proposal::with('project.clientUser')->where('freelancer_id',$user->id)->get();
            return  $this->sendCommonResponse('false', "", $proposals, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getFreelancerProjectProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function getFreelancerContract(Request $request){

        try {
             $user = Auth::user();
            $contracts =  Contract::with(['project','proposal','freelancer'])->where('freelancer_id',$user->id)->get();
            return  $this->sendCommonResponse('false', "", $contracts, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getFreelancerContract  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
