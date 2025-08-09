<?php

namespace App\Application\Contract\Services;

use App\Models\BusinessContract;
use App\Models\ContractTemplate;
use App\Models\ContractParty;
use App\Models\ContractSignature;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractService
{
    public function getAllContracts(array $filters = [])
    {
        $query = BusinessContract::with(['template', 'creator', 'parties', 'signatures']);

        if (isset($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (isset($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (isset($filters['signature_status'])) {
            $query->bySignatureStatus($filters['signature_status']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('contract_number', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getContractById(int $id)
    {
        return BusinessContract::with([
            'template', 
            'creator', 
            'approver', 
            'parties', 
            'signatures.signer'
        ])->findOrFail($id);
    }

    public function createContract(array $data)
    {
        DB::beginTransaction();
        try {
            $contract = new BusinessContract($data);
            $contract->contract_number = $contract->generateContractNumber();
            $contract->created_by = auth()->id();
            $contract->save();

            // Create parties if provided
            if (isset($data['parties'])) {
                foreach ($data['parties'] as $partyData) {
                    $contract->parties()->create($partyData);
                }
            }

            DB::commit();
            return $contract->load(['template', 'creator', 'parties']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateContract(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $contract = BusinessContract::findOrFail($id);
            $contract->update($data);

            // Update parties if provided
            if (isset($data['parties'])) {
                $contract->parties()->delete();
                foreach ($data['parties'] as $partyData) {
                    $contract->parties()->create($partyData);
                }
            }

            DB::commit();
            return $contract->load(['template', 'creator', 'parties']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteContract(int $id)
    {
        $contract = BusinessContract::findOrFail($id);
        
        // Delete associated file if exists
        if ($contract->file_path && Storage::exists($contract->file_path)) {
            Storage::delete($contract->file_path);
        }

        return $contract->delete();
    }

    public function approveContract(int $id, int $approverId)
    {
        $contract = BusinessContract::findOrFail($id);
        $contract->update([
            'approved_by' => $approverId,
            'approved_at' => now(),
            'status' => 'active'
        ]);

        return $contract;
    }

    public function addSignature(int $contractId, array $signatureData)
    {
        $contract = BusinessContract::findOrFail($contractId);
        
        $signature = new ContractSignature($signatureData);
        $signature->contract_id = $contractId;
        $signature->signer_id = auth()->id();
        $signature->signed_at = now();
        $signature->ip_address = request()->ip();
        $signature->user_agent = request()->userAgent();
        $signature->save();

        // Update contract signature status
        $this->updateContractSignatureStatus($contract);

        return $signature;
    }

    private function updateContractSignatureStatus(BusinessContract $contract)
    {
        $totalSignatures = $contract->signatures()->count();
        $expectedSignatures = $contract->parties()->count();

        if ($totalSignatures === 0) {
            $contract->update(['signature_status' => 'unsigned']);
        } elseif ($totalSignatures < $expectedSignatures) {
            $contract->update(['signature_status' => 'partially_signed']);
        } else {
            $contract->update(['signature_status' => 'fully_signed']);
        }
    }

    public function generateContractPDF(int $contractId)
    {
        $contract = $this->getContractById($contractId);
        
        // Generate PDF content (you can use libraries like DomPDF, mPDF, etc.)
        $pdfContent = $this->generatePDFContent($contract);
        
        $filename = 'contracts/' . $contract->contract_number . '.pdf';
        Storage::put($filename, $pdfContent);
        
        $contract->update(['file_path' => $filename]);
        
        return $filename;
    }

    private function generatePDFContent(BusinessContract $contract)
    {
        // This is a placeholder - you would implement actual PDF generation
        $content = "Contract: {$contract->title}\n";
        $content .= "Number: {$contract->contract_number}\n";
        $content .= "Status: {$contract->status}\n";
        $content .= "Value: {$contract->value} {$contract->currency}\n";
        
        return $content;
    }

    public function getContractStats()
    {
        return [
            'total' => BusinessContract::count(),
            'active' => BusinessContract::byStatus('active')->count(),
            'expired' => BusinessContract::byStatus('expired')->count(),
            'pending' => BusinessContract::byStatus('pending')->count(),
            'expiring_soon' => BusinessContract::expiringSoon()->count(),
            'unsigned' => BusinessContract::bySignatureStatus('unsigned')->count(),
            'partially_signed' => BusinessContract::bySignatureStatus('partially_signed')->count(),
            'fully_signed' => BusinessContract::bySignatureStatus('fully_signed')->count(),
        ];
    }

    // Template Management
    public function getAllTemplates(array $filters = [])
    {
        $query = ContractTemplate::with('creator');

        if (isset($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (isset($filters['active'])) {
            $query->active();
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function createTemplate(array $data)
    {
        $data['created_by'] = auth()->id();
        return ContractTemplate::create($data);
    }

    public function updateTemplate(int $id, array $data)
    {
        $template = ContractTemplate::findOrFail($id);
        $template->update($data);
        return $template;
    }

    public function deleteTemplate(int $id)
    {
        $template = ContractTemplate::findOrFail($id);
        return $template->delete();
    }
} 