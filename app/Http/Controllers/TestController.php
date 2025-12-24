<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractPayment;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{


    public function generatePdf(Request $request)
    {
        return  $this->createPdfFileForContract();
    }

    // public function createPdfFileForContract()
    // {
    //     $contract =  Contract::with(['proposal', 'project', 'freelancer', 'client'])->find(23);
    //     $contractPayment =  ContractPayment::where('contract_id', $contract->id)->first();
    //     $milestones = Milestone::where('contract_id', $contract->id)->get();
    //     $pdf = Pdf::loadView('pdf.invoice', [
    //         'contract' => $contract,
    //         'contractPayment' => $contractPayment,
    //         'milestones'  => $milestones
    //     ]);

    //     $pdf->setPaper('A4', 'portrait');

    //     return $pdf->stream('invoice-' . $contractPayment->id . '.pdf');
    // }

    public function createPdfFileForContract($contractId)
    {
        $contract = Contract::with(['proposal', 'project', 'freelancer', 'client'])
            ->findOrFail($contractId);

        $contractPayment = ContractPayment::where('contract_id', $contractId)
            ->firstOrFail();

        $milestones = Milestone::where('contract_id', $contractId)->get();

        $pdf = Pdf::loadView('pdf.invoice', [
            'contract'         => $contract,
            'contractPayment' => $contractPayment,
            'milestones'      => $milestones
        ])->setPaper('A4', 'portrait');
        $fileName = 'invoice-' . $contractPayment->id . '.pdf';
        $filePath = 'invoices/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
     
        return $filePath;
    }
}
