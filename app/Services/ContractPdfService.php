<?php

namespace App\Services;

use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContractPdfService
{
    /**
     * Generate contract PDF and store it.
     */
    public function generate(Contract $contract): string
    {
        $contract->load('user', 'room.roomType', 'booking', 'paymentSchedules');

        $data = [
            'contract' => $contract,
            'user' => $contract->user,
            'room' => $contract->room,
            'roomType' => $contract->room->roomType,
            'booking' => $contract->booking,
            'schedules' => $contract->paymentSchedules,
        ];

        $pdf = Pdf::loadView('pdf.contract', $data);
        $pdf->setPaper('a4');

        $filename = "contracts/{$contract->contract_number}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        // Update contract with PDF URL
        $contract->update([
            'contract_pdf_url' => Storage::disk('public')->url($filename),
        ]);

        return $filename;
    }
}
