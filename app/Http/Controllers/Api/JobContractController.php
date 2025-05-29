<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\JobProposalAccepted;
use App\Mail\MilestoneCompleteRequestMail;
use App\Mail\MilestonePaymentSuccess;
use App\Mail\ProposalAcceptedByFreelancer;
use App\Models\Milestone;
use App\Models\MilestonePaymentDetail;
use App\Models\MilestonPayment;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\TrackMilestoneCompleteRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;
use App\Services\MailService;

class JobContractController extends BaseController
{

    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

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

        DB::beginTransaction();
        try {
            $request->validate([
                'proposal_id' => 'required|integer',
                'proposal_status' => 'required|in:shortlisted,interview,hired,rejected',
                // 'started_at' => 'required_if:proposal_status,hired',
                // 'ended_at' => 'required_if:proposal_status,hired',
                'started_at' => 'required|date|before_or_equal:ended_at',
                'ended_at'   => 'required|date|after_or_equal:started_at|before:2100-01-01',
                'type' => 'required_if:proposal_status,hired|in:hourly,fixed',
                'amount' => 'required_if:proposal_status,hired',
                'payment_type' => 'required_if:proposal_status,hired|in:milestone,lump_sum',

            ], [
                'started_at.before_or_equal' => 'Start date must be before or equal to the end date.',
                'ended_at.after_or_equal' => 'End date must be after or equal to the start date.',
                'ended_at.before' => 'End date must be before the year 2100.',
            ]);
            $proposal =  Proposal::with('freelancerUser')->find($request->proposal_id);
            if (empty($proposal)) {
                throw new \Exception('something went wrong');
            }
            $proposal->status =  $request->proposal_status;
            $proposal->save();
            if ($request->proposal_status == 'hired') {
                $contract =  Contract::where('proposal_id', $proposal->id)->where('project_id', $proposal->project_id)->first();
                $projectId  = Project::find($proposal->project_id);
                if (empty($contract)) {
                    $contract =      Contract::create([
                        'project_id' => $proposal->project_id,
                        'proposal_id' => $proposal->id,
                        'freelancer_id' => $proposal->freelancer_id,
                        'client_id' => $projectId?->user_id ?? null,
                        'started_at' => $request->started_at,
                        'ended_at' => $request->ended_at,
                        'amount' => $request->amount,
                        'status' => 'pending',
                        'payment_type' => $request->payment_type
                    ]);
                    Proposal::where('project_id', $proposal->project_id)
                        ->where('id', '!=', $proposal->id)
                        ->update(['status' => 'rejected']);
                    Project::where('id', $proposal->project_id)
                        ->update(['project_status' => 5]);
                }
                $this->addProjectMilestone($request, $contract->id, $request->amount);
                // $this->mailService->safeSend($proposal->freelancerUser->email,new JobProposalAccepted($projectId, $proposal, $contract),"responseToProposal freelancer mail");
                // $this->mailService->safeSend(@User::find($projectId?->user_id)->email,new ProposalAcceptedByFreelancer($projectId, $proposal, $contract),"responseToProposal client mail");
                // Mail::to($proposal->freelancerUser->email)->send(new JobProposalAccepted($projectId, $proposal, $contract));
                // Mail::to(@User::find($projectId?->user_id)->email)->send(new ProposalAcceptedByFreelancer($projectId, $proposal, $contract));
            }
            DB::commit();
            $data['proposal'] = $proposal;
            $data['contract'] = $contract;
            return  $this->sendCommonResponse('false', "", $data, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('responseToProposal  Api log \n: ' . $e->getMessage());
            DB::rollBack();
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function setChargesOnMilestone($contractAmount, $milestoneCount, $mileStoneAmount)
    {
        $contractAmount = $contractAmount;
        $stripeFeePercent = env('STRIPE_FEE_PERCENT'); // 2.9%
        $stripeFeeFixed = env('STRIPE_FEE_FIXED');    // $0.30 fixed fee

        // Calculate the total charge needed to cover the Stripe fee and get exactly $contractAmount after fees
        $totalCharge = ($contractAmount + $stripeFeeFixed) / (1 - $stripeFeePercent);

        // Round up to nearest cent
        $totalCharge = $totalCharge * 100 / 100;

        // Calculate the fee that will be taken by Stripe (for storing)
        $estimatedFee = $totalCharge - $contractAmount;
        $amountCharges = $estimatedFee / $milestoneCount;
        $amount =  $mileStoneAmount - $amountCharges;
        //  floor($amount * 100) / 100;
        return [
            "payedAmount" => floor($amount * 100) / 100,
            "charges" =>  floor($amountCharges * 100) / 100
        ];
    }

    public function addProjectMilestone($request, $contractId, $contractAmount)
    {
        $milestones = $request->milestones;
        $data = [];

        foreach ($milestones as $key => $value) {
            $chargesArray = $this->setChargesOnMilestone($contractAmount, count($milestones), $value['amount']);
            $data[] = [
                'title' => $value['title'],
                'amount' =>  $value['amount'],
                'actual_payed_amount' =>  $chargesArray['payedAmount'],
                'platform_fee_charges' =>  $chargesArray['charges'],
                'description' => $value['description'],
                'contract_id' => $contractId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Milestone::insert($data);
    }


    public function getContractDetails(Request $request)
    {
        try {
            $contract_id =  $request->contract_id;
            $contractData = Contract::with(['proposal', 'project', 'freelancer', 'client'])->find($contract_id);
            $data['contract'] = $contractData;
            $data['milestones'] = Milestone::with('milestonePayments','trackMilestoneRequest')->where('contract_id', $contract_id)->get();

            $milestonePayments = MilestonePaymentDetail::with('milestone')->where('contract_id', $contract_id)->get();
            if (!empty($milestonePayments)) {
                $totalAmount = $milestonePayments->sum(function ($paymentDetail) {
                    return $paymentDetail->milestone->amount ?? 0;
                });
            } else {
                $totalAmount = 0;
            }

            $data['contractPaymentDetail'] = (object) [
                'in_enscrow' =>  $contractData->amount - $totalAmount,
                'total_earning' =>  $totalAmount,
            ];
            return  $this->sendCommonResponse('false', "", $data, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getJobs  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function requestToReleaseMilestonePayment(Request $request)
    {
        try {
            $request->validate([
                'milestone_id' => 'required|integer',
            ]);
            $milestone = Milestone::with([
                'contract.client',
                'contract.project',
                'contract.freelancer'

            ])->find($request->milestone_id);
            if ($milestone) {
                TrackMilestoneCompleteRequest::create([
                    "milestone_id" => $milestone->id,
                    "contract_id" => $milestone?->contract?->id,
                    "freelancer_id" => $milestone?->contract?->freelancer_id,
                    "project_id" => $milestone?->contract?->project_id
                ]);
                $this->mailService->safeSend($milestone->contract?->client?->email,new MilestoneCompleteRequestMail($milestone),'requestToReleaseMilestonePayment');
                // Mail::to($milestone->contract?->client?->email)->send(new MilestoneCompleteRequestMail($milestone));
            }
            return  $this->sendCommonResponse('false', "", [], 'Request send Successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('requestToReleaseMilestonePayment  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    // public function makeMilestonePayment(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'milestone_id' => 'required|integer',
    //             'contract_id' => 'required|integer',
    //         ]);
    //         return DB::transaction(function () use ($request) {
    //             $contract_id = $request->contract_id;
    //             $milestone_id = $request->milestone_id;

    //             $milestone = Milestone::where('contract_id', $contract_id)->where('id', $milestone_id)->first();

    //             if (!$milestone) {
    //                 throw new \Exception('Milestone not found.');
    //             }

    //             // Update milestone status
    //             $milestone->status = 'paid';
    //             $milestone->save();

    //             // Create a new payment record
    //             $milestonPayment = MilestonPayment::create([
    //                 'milestone_id'   => $milestone_id,
    //                 'payment_status' => 'paid',
    //                 'paid_at'        => now(),
    //                 'transaction_id' => Str::random(16),
    //                 'amount'         => $milestone->amount
    //             ]);
    //             $contract = Contract::find($contract_id);
    //             $freelancer = User::find($contract->freelancer_id);

    //             Mail::to($freelancer->email)->send(new MilestonePaymentSuccess($freelancer, $milestone));
    //             return $this->sendCommonResponse(false, "", $milestonPayment, 'Payment successfully processed', "", ProjectConstants::HTTP_OK);
    //         });
    //     } catch (\Exception $e) {
    //         Log::channel('daily')->error('makeMilestonePayment API error: ' . $e->getMessage());
    //         return $this->sendCommonResponse(true, 'error', '', 'Something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
}
