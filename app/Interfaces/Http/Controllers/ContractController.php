<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Contract\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContractController
{
    public function __construct(
        private ContractService $contractService
    ) {}

    /**
     * Get all contracts
     */
    public function getContracts(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'type', 'signature_status', 'search']);
        $contracts = $this->contractService->getAllContracts($filters);
        
        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Get contract by ID
     */
    public function getContract(int $id): JsonResponse
    {
        $contract = $this->contractService->getContractById($id);
        
        return response()->json([
            'success' => true,
            'data' => $contract
        ]);
    }

    /**
     * Create new contract
     */
    public function createContract(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:employment,service,partnership,client,vendor,other',
            'template_id' => 'nullable|exists:contract_templates,id',
            'client_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:draft,pending,active,expired,terminated,completed',
            'terms' => 'nullable|array',
            'parties' => 'nullable|array',
            'parties.*.party_type' => 'required|in:client,vendor,partner,employee',
            'parties.*.name' => 'required|string',
            'parties.*.email' => 'required|email',
            'parties.*.phone' => 'nullable|string',
            'parties.*.address' => 'nullable|string',
            'parties.*.company_name' => 'nullable|string',
            'parties.*.tax_number' => 'nullable|string',
            'parties.*.representative_name' => 'nullable|string',
            'parties.*.representative_title' => 'nullable|string',
            'parties.*.is_primary' => 'nullable|boolean',
        ]);

        $contract = $this->contractService->createContract($data);

        return response()->json([
            'success' => true,
            'message' => 'Contract created successfully',
            'data' => $contract
        ], 201);
    }

    /**
     * Update contract
     */
    public function updateContract(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:employment,service,partnership,client,vendor,other',
            'template_id' => 'nullable|exists:contract_templates,id',
            'client_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'sometimes|required|in:draft,pending,active,expired,terminated,completed',
            'terms' => 'nullable|array',
            'parties' => 'nullable|array',
            'parties.*.party_type' => 'required|in:client,vendor,partner,employee',
            'parties.*.name' => 'required|string',
            'parties.*.email' => 'required|email',
            'parties.*.phone' => 'nullable|string',
            'parties.*.address' => 'nullable|string',
            'parties.*.company_name' => 'nullable|string',
            'parties.*.tax_number' => 'nullable|string',
            'parties.*.representative_name' => 'nullable|string',
            'parties.*.representative_title' => 'nullable|string',
            'parties.*.is_primary' => 'nullable|boolean',
        ]);

        $contract = $this->contractService->updateContract($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'data' => $contract
        ]);
    }

    /**
     * Delete contract
     */
    public function deleteContract(int $id): JsonResponse
    {
        $this->contractService->deleteContract($id);

        return response()->json([
            'success' => true,
            'message' => 'Contract deleted successfully'
        ]);
    }

    /**
     * Approve contract
     */
    public function approveContract(int $id): JsonResponse
    {
        $contract = $this->contractService->approveContract($id, auth()->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Contract approved successfully',
            'data' => $contract
        ]);
    }

    /**
     * Add signature to contract
     */
    public function addSignature(Request $request, int $contractId): JsonResponse
    {
        $data = $request->validate([
            'signer_name' => 'required|string',
            'signer_email' => 'required|email',
            'signer_title' => 'nullable|string',
            'signature_type' => 'required|in:digital,electronic,handwritten',
            'signature_data' => 'required|string',
        ]);

        $signature = $this->contractService->addSignature($contractId, $data);

        return response()->json([
            'success' => true,
            'message' => 'Signature added successfully',
            'data' => $signature
        ]);
    }

    /**
     * Generate contract PDF
     */
    public function generatePDF(int $id): JsonResponse
    {
        $filePath = $this->contractService->generateContractPDF($id);

        return response()->json([
            'success' => true,
            'message' => 'PDF generated successfully',
            'data' => [
                'file_path' => $filePath
            ]
        ]);
    }

    /**
     * Get contract statistics
     */
    public function getStats(): JsonResponse
    {
        $stats = $this->contractService->getContractStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get all templates
     */
    public function getTemplates(Request $request): JsonResponse
    {
        $filters = $request->only(['type', 'active']);
        $templates = $this->contractService->getAllTemplates($filters);

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Create template
     */
    public function createTemplate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:employment,service,partnership,client,vendor,other',
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $template = $this->contractService->createTemplate($data);

        return response()->json([
            'success' => true,
            'message' => 'Template created successfully',
            'data' => $template
        ], 201);
    }

    /**
     * Update template
     */
    public function updateTemplate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:employment,service,partnership,client,vendor,other',
            'content' => 'sometimes|required|string',
            'variables' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $template = $this->contractService->updateTemplate($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully',
            'data' => $template
        ]);
    }

    /**
     * Delete template
     */
    public function deleteTemplate(int $id): JsonResponse
    {
        $this->contractService->deleteTemplate($id);

        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully'
        ]);
    }
} 