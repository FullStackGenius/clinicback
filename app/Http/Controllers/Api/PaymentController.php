<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Transfer;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\JobProposalAccepted;
use App\Mail\MilestonePaymentSuccess;
use App\Mail\ProposalAcceptedByFreelancer;
use App\Models\AllPaymentTransaction;
use App\Models\Contract;
use App\Models\ContractPayment;
use App\Models\Milestone;
use App\Models\MilestonePaymentDetail;
use App\Models\MilestonPayment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Stripe\Account;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\MailService;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class PaymentController extends BaseController
{
    // public function __construct(private StripeService $stripeService) {}
    protected $mailService;
    public function __construct(private StripeService $stripeService, MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function createAccount(Request $request)
    {

        try {
            $loginUserData = Auth::user();
            if ($loginUserData->stripe_account_id == "") {
                $account = $this->stripeService->createCustomAccount($loginUserData->email);
                $user = User::find($loginUserData->id);
                $user->stripe_account_id = $account->id;
                $user->save();
                $account = $account->id;
            } else {
                $account = $loginUserData->stripe_account_id;
            }


            $link = $this->onboardingLink($account);
            $data = ['onboard_link' => $link->url, 'account_id' => $account];
            return $this->sendCommonResponse(
                false,
                'Stripe account created successfully.',
                $data,
                '',
                '',
                ProjectConstants::HTTP_OK
            );
        } catch (\Exception $e) {
            Log::error('Error in createAccount: ' . $e->getMessage());

            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'Something went wrong while creating Stripe account.',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getStripeAccountInfo(Request $request)
    {

        try {
            $user  = User::find(Auth::user()->id);
            $data['user'] = $user;
            $data['checkFreeLancerAccountFitToTransfer'] =  false;
            $data['regenerateOnboardingLink'] = "";
            if ($user->stripe_account_id != "") {
                if ($this->checkFreeLancerAccountFitToTransfer($user->stripe_account_id)) {
                    $data['checkFreeLancerAccountFitToTransfer'] =  false;
                    $link =  $this->onboardingLink($user->stripe_account_id);
                    $data['regenerateOnboardingLink'] =   $link?->url;
                } else {
                    $data['checkFreeLancerAccountFitToTransfer'] =  true;
                    $data['regenerateOnboardingLink'] = "";
                }
            } else {
                $data['checkFreeLancerAccountFitToTransfer'] =  false;
                $data['regenerateOnboardingLink'] = "";
            }

            return $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getStripeAccountInfo: ' . $e->getMessage());
            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'Something went wrong',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function onboardingLink($account)
    {

        $link = $this->stripeService->generateAccountLink(
            $account,
            ProjectConstants::FRONTEND_PATH,
            ProjectConstants::FRONTEND_PATH
        );

        return  $link;
    }


    // public function onboardingLink(Request $request)
    // {
    //     $request->validate(['account_id' => 'required']);
    //     $link = $this->stripeService->generateAccountLink(
    //         $request->account_id,
    //         route('stripe.refresh'),
    //         route('stripe.return')
    //     );

    //     return response()->json(['url' => $link->url]);
    // }

    // public function releasePayment(Request $request)
    // {
    //     $request->validate([
    //         'freelancer_id' => 'required|exists:users,id',
    //         'amount' => 'required|numeric',
    //         'transfer_group' => 'required',
    //     ]);

    //     $freelancer = User::find($request->freelancer_id);

    //     if (!$freelancer->stripe_account_id) {
    //         return response()->json(['error' => 'Freelancer not connected to Stripe'], 422);
    //     }

    //     $transfer = $this->stripeService->transferToFreelancer(
    //         $request->amount,
    //         $freelancer->stripe_account_id,
    //         $request->transfer_group
    //     );

    //     return response()->json($transfer);
    // }


    // public function return()
    // {
    //     return response()->json(['message' => 'Onboarding complete']);
    // }

    // public function refresh()
    // {
    //     return response()->json(['message' => 'Please try again']);
    // }

    public function createPaymentIntent(Request $request)
    {


        try {
            $request->validate([
                'amount' => 'required|numeric',
                'contract_id' => 'required',
            ]);

            Stripe::setApiKey(env('STRIPE_SECRET'));
            $amount = $request->amount;
            $contract_id = $request->contract_id;
            $contractData = Contract::with('client')->find($contract_id);
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => 'Escrow Payment for Contract #' . $contract_id,
                'transfer_group' => 'CONTRACT_' . $contract_id,
                'metadata' => [
                    'client_id' => $contractData->client->id,
                    'contract_id' => $contractData->id,
                    'client_email' => $contractData->client->email,
                ]
            ]);

            $data['clientSecret'] = $paymentIntent->client_secret;
            return  $this->sendCommonResponse('false', "", $data, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('createPaymentIntent  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }




        // return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }


    // public function releaseToFreelancer(Request $request)
    // {
    //     $request->validate([
    //         'contract_id' => 'required|exists:contracts,id',
    //     ]);

    //     // $contract = Contract::with('freelancer')->findOrFail($request->contract_id);

    //     $contract = 22;
    //     $is_released = false;
    //     // Check if already paid
    //     if ($is_released) {
    //         return response()->json(['message' => 'Payment already released.'], 400);
    //     }

    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     // Assume contract has `amount` and freelancer has `stripe_account_id`
    //     // $transfer = Transfer::create([
    //     //     'amount' => $contract->amount * 100, // in cents
    //     //     'currency' => 'usd',
    //     //     'destination' => $contract->freelancer->stripe_account_id,
    //     //     'transfer_group' => 'CONTRACT_' . $contract->id,
    //     // ]);

    //     // // Optionally mark as paid
    //     // $contract->is_released = true;
    //     // $contract->save();

    //     $transfer = Transfer::create([
    //         'amount' => 200 * 100, // in cents
    //         'currency' => 'usd',
    //         'destination' => 'pi_3RBEZ9KV1lSSoArc1NtfGxgu',
    //         'transfer_group' => 'CONTRACT_' . 22,
    //     ]);

    //     // Optionally mark as paid
    //     // $contract->is_released = true;
    //     // $contract->save();

    //     return response()->json(['message' => 'Payment released to freelancer.', 'transfer_id' => $transfer]);
    // }



    public function storeContractPaymentResponse(Request $request)
    {

        try {
            $request->validate([
                'payment_intent_id' => 'required|string',
                'contract_id' => 'required|exists:contracts,id',
                'amount' => 'required|numeric',
            ]);

            $contract =  Contract::with(['proposal', 'project', 'freelancer', 'client'])->find($request->contract_id);
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $intent = PaymentIntent::retrieve($request->payment_intent_id);

            $contractPayment =  ContractPayment::updateOrCreate(
                ['payment_intent_id' => $intent->id],
                [
                    'contract_id' => $contract->id,
                    'project_id' => $contract->project_id,
                    'client_id' => Auth::user()->id,
                    'amount' => $intent->amount / 100,
                    'currency' => $intent->currency,
                    'status' => $intent->status,
                    'paid_at' => now(),
                    'transfer_group' => $intent->transfer_group,
                    'stripe_response' => json_encode($intent),
                ]
            );
            $contract->status = 'active';
            $contract->save();
            $this->saveDataInAllTransactionTable($contractPayment, 'contract', $contract->amount, Auth::user()->id, $receiverId = null);
            $contractSummaryLink = $this->createPdfFileForContract($contract->id);
            $contract->contractSummaryLink = $contractSummaryLink;
            $this->mailService->safeSend($contract->freelancer->email, new JobProposalAccepted($contract->project, $contract->proposal, $contract), "responseToProposal freelancer mail");
            $this->mailService->safeSend($contract->client->email, new ProposalAcceptedByFreelancer($contract->project, $contract->proposal, $contract), "responseToProposal client mail");
            $data['contract_payment'] = $contractPayment;
            return  $this->sendCommonResponse('false', "", $data, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('storeContractPaymentResponse  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }





    public function releasePaymentToFreelancer(Request $request)
    {

        DB::beginTransaction();

        try {
            $request->validate([
                'milestone_id' => 'required|integer',
                'contract_id' => 'required|integer',
            ]);

            $milestone = Milestone::with([
                'contract.freelancer',
                'contract.contractPayment'
            ])->find($request->milestone_id);

            if (!$milestone) {
                throw new \Exception('Something went wrong. Milestone not found.');
            }
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $destination = $milestone->contract->freelancer->stripe_account_id;
            if ($destination == "") {
                throw new \Exception('Freelancer is not connected to Stripe');
            }
            $checkFreeLancerAccountFitToTransfer = $this->checkFreeLancerAccountFitToTransfer($destination);
            if ($checkFreeLancerAccountFitToTransfer) {
                throw new \Exception('Freelancer Stripe account is not fully enabled for payouts.');
            }

            $transfer_group = $milestone->contract->contractPayment->transfer_group;
            $currency = $milestone->contract->contractPayment->currency;
            // $amount = $milestone->amount;
            $amount = $milestone->actual_payed_amount;



            // Stripe Transfer
            $transfer = Transfer::create([
                'amount' => $amount * 100, // cents
                'currency' => $currency,
                'destination' => $destination,
                'transfer_group' => $transfer_group,
            ]);

            // Save Transfer Info
            $MilestonePaymentDetail = MilestonePaymentDetail::create([
                'milestone_id' => $milestone->id,
                'contract_id' => $milestone->contract->id,
                'project_id' => $milestone->contract->project_id,
                'freelancer_id' => $milestone->contract->freelancer->id,
                'transfer_id' => $transfer->id,
                'destination_account' => $transfer->destination,
                'destination_payment' => $transfer->destination_payment ?? null,
                'balance_transaction_id' => $transfer->balance_transaction,
                'currency' => $transfer->currency,
                'amount' => $transfer->amount / 100,
                'actual_milestone_amount' => $milestone->amount,
                'platform_fee_charges' => $milestone->platform_fee_charges,
                'transfer_group' => $transfer->transfer_group,
                'reversed' => $transfer->reversed,
                'transferred_at' => now(),
                'raw_data' => json_encode($transfer->toArray()),
            ]);

            $milestone->status = 'paid';
            $milestone->save();
            $this->saveDataInAllTransactionTable($MilestonePaymentDetail, 'milestone', $milestone->amount, Auth::user()->id,  $milestone->contract->freelancer->id);
            DB::commit(); // All DB actions were successful, commit now

            // Send Email
            $freelancer = $milestone->contract->freelancer;
            // Mail::to($freelancer->email)->send(new MilestonePaymentSuccess($freelancer, $milestone));
            $this->mailService->safeSend($milestone->contract->freelancer->email, new MilestonePaymentSuccess($freelancer, $milestone), 'releasePaymentToFreelancer mail');
            Log::channel('daily')->error('releasePaymentToFreelancer response: ' . json_encode($transfer));
            return $this->sendCommonResponse(false, "", $transfer, 'Milestone payment successfully released', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('daily')->error('releasePaymentToFreelancer ERROR: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', $e->getMessage(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkFreeLancerAccountFitToTransfer($data)
    {
        $account = Account::retrieve($data);

        if (
            $account->capabilities->transfers !== 'active' ||
            $account->charges_enabled !== true ||
            $account->payouts_enabled !== true
        ) {
            return true;
        } else {
            return false;
        }
    }


    public function getContractBalance(Request $request)
    {

        try {
            $request->validate([
                'contract_id' => 'required|integer',
            ]);

            $contractId = $request->contract_id;
            // Stripe::setApiKey(env('STRIPE_SECRET'));
            $contractPaymentDetail = ContractPayment::where('contract_id', $contractId)->first();
            // dd($contractPaymentDetail);
            $transferGroup = $contractPaymentDetail->transfer_group;

            // Get all PaymentIntents with this transfer group
            $paymentIntents = PaymentIntent::all(['limit' => 100]); // adjust limit if needed

            $totalFunded = 0;
            foreach ($paymentIntents->data as $intent) {
                if (isset($intent->transfer_group) && $intent->transfer_group === $transferGroup && $intent->status === 'succeeded') {
                    $totalFunded += $intent->amount;
                }
            }

            // Get all Transfers with this transfer group
            $transfers = Transfer::all([
                'transfer_group' => $transferGroup,
                'limit' => 100,
            ]);

            $totalReleased = 0;
            foreach ($transfers->data as $transfer) {
                $totalReleased += $transfer->amount;
            }

            $escrowHeld = $totalFunded - $totalReleased;
            $data  = [
                'contract_id' => $contractId,
                'transfer_group' => $transferGroup,
                'currency' => 'usd',
                'total_funded' => $totalFunded / 100,
                'total_released' => $totalReleased / 100,
                'escrow_held' => $escrowHeld / 100,
            ];

            return $this->sendCommonResponse(false, "", $data, 'Contract  Balance detail', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::error('Error in getContractBalance: ' . $e->getMessage());
            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'Something went wrong',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getPaymentTransactionDetail(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string',
                'data_id' => 'required|integer',
            ]);
            // dd($request->all());
            if ($request->type == "contract") {
                $data['type'] = "contract";
                $data['detail'] = ContractPayment::where('contract_id', $request->data_id)->first();
            } else {
                $data['type'] = "milestone";
                $data['detail'] = MilestonePaymentDetail::where('milestone_id', $request->data_id)->first();
            }
            $contract = ContractPayment::find(1);
            //  Log::channel('daily')->error('releasePaymentToFreelancer response: ' . json_encode($transfer));
            return $this->sendCommonResponse(false, "", $data, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {

            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {

            Log::channel('daily')->error('releasePaymentToFreelancer ERROR: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', $e->getMessage(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showAllTransaction()
    {
        try {
            $user = Auth::user();
            if ($user->role_id === 2) {
                $allPaymentTransaction =  AllPaymentTransaction::with(['payer', 'receiver', 'payable'])->where('payer_id', $user->id)->paginate(5);
            } else {
                $allPaymentTransaction =  AllPaymentTransaction::with(['payer', 'receiver', 'payable'])->where('receiver_id', $user->id)->paginate(5);
            }

            return $this->sendCommonResponse(false, "", $allPaymentTransaction, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->error('showAllTransaction ERROR: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', $e->getMessage(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveDataInAllTransactionTable($relationObject, $paymentFor, $amount, $payerId, $receiverId)
    {
        try {
            $transaction = new AllPaymentTransaction([
                'amount' => $amount,
                'payment_for' => $paymentFor,
                'payer_id' => $payerId, // client user sending money
                'receiver_id' => $receiverId, // freelancer receiving money
            ]);
            $relationObject->transaction()->save($transaction);
        } catch (\Exception $e) {
            Log::channel('daily')->error('saveDataInAllTransactionTable ERROR: ' . $e->getMessage());
        }
    }

    public function createPdfFileForContract($contractId)
    {
        $contract = Contract::with(['proposal', 'project', 'freelancer', 'client'])
            ->findOrFail($contractId);

        $contractPayment = ContractPayment::where('contract_id', $contractId)
            ->firstOrFail();

        $milestones = Milestone::where('contract_id', $contractId)->get();

        $pdf = Pdf::loadView('pdf.contract_details', [
            'contract'         => $contract,
            'contractPayment' => $contractPayment,
            'milestones'      => $milestones
        ])->setPaper('A4', 'portrait');
        $fileName = 'contract-detail-' . $contractPayment->id . '.pdf';
        $filePath = 'contract-detail/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        $saveContactPdf = ContractPayment::where('contract_id', $contractId)->first();
        $saveContactPdf->contract_detail_pdf = $filePath;
        $saveContactPdf->save();

        return $filePath;
    }


    public function getPaymentTransactionInvoice(Request $request)
    {
        try {
            $milestone_id = $request->milestone_id;

            $milestonePaymentDetail = MilestonePaymentDetail::with([
                'milestone',
                'milestone.contract',
                'milestone.contract.freelancer',
                'milestone.contract.client',
                'milestone.contract.project'
            ])->where('milestone_id', $milestone_id)->firstOrFail();

            $fileName = 'milestone-invoice-' . $milestone_id . '.pdf';
            $filePath = 'milestone-invoice/' . $fileName;

            // ✅ CHECK: If PDF already exists
            if (!Storage::disk('public')->exists($filePath)) {

                $pdf = Pdf::loadView('pdf.single-milestone-invoice', [
                    'milestonePaymentDetail' => $milestonePaymentDetail,
                ])->setPaper('A4', 'portrait');

                Storage::disk('public')->put($filePath, $pdf->output());
            }

            return $this->sendCommonResponse(
                false,
                "",
                [
                    'milestone_invoice_link' => config('app.url') . "/storage/" . $filePath,
                    'milestonePaymentDetail' => $milestonePaymentDetail
                ],
                'Data fetched successfully',
                "",
                ProjectConstants::HTTP_OK
            );
        } catch (\Exception $e) {
            Log::channel('daily')->error('Milestone Invoice ERROR: ' . $e->getMessage());

            return $this->sendCommonResponse(
                true,
                'error',
                '',
                $e->getMessage(),
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
