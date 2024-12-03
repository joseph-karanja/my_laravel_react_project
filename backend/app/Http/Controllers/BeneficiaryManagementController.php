<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeneficiaryPaymentBatch;
use App\Models\SchoolPaymentList;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class BeneficiaryManagementController extends Controller
{
    /**
     * Fetch beneficiaries by district with optional pagination.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBeneficiariesByDistrict(Request $request)
    {
        $district = $request->query('district'); // or use $request->input('district')
        $perPage = $request->query('perPage', null);

        if (empty($district)) {
            return response()->json(['message' => 'Please provide district'], 400);
        }

        $query = BeneficiaryPaymentBatch::where('school_district', $district);

        if ($perPage) {
            $beneficiaries = $query->paginate($perPage);
        } else {
            $beneficiaries = $query->get();
        }

        if ($beneficiaries->isEmpty()) {
            return response()->json(['message' => 'No beneficiaries found for this district.'], 404);
        }

        // Transform the data to match the desired JSON structure
        $transformed = $beneficiaries->map(function ($beneficiary) {
            return [
                'TransactionID' => $beneficiary->transaction_id,
                'BeneficiaryNumber' => $beneficiary->beneficiary_no,
                'FirstName' => $beneficiary->first_name,
                'LastName' => $beneficiary->last_name,
                'School' => $beneficiary->school_name,
                'SchoolDistrict' => $beneficiary->school_district,
                'Province' => $beneficiary->school_province,
                'GuardianPhoneNumber' => $beneficiary->mobile_phone_parent_guardian,
                'GuardianNRC' => $beneficiary->hhh_nrc_number,
                'GuardianFirstName' => $beneficiary->hhh_fname,
                'GuardianLastName' => $beneficiary->hhh_lname,
                'EducationGrantAmount' => $beneficiary->school_fees,
                'TransactionInitiatedAt' => $beneficiary->transaction_time_initiated ? $beneficiary->transaction_time_initiated->toDateTimeString() : null,
            ];
        });

        return response()->json($transformed);
    }

    public function generateTransactionIds()
    {
        // Fetch records where transaction_id is null
        $beneficiaries = BeneficiaryPaymentBatch::whereNull('transaction_id')->get();

        if ($beneficiaries->isEmpty()) {
            return response()->json(['message' => 'All records already have transaction IDs.'], 404);
        }

        foreach ($beneficiaries as $beneficiary) {
            $uuid = Str::uuid();
            $timestamp = Carbon::now()->timestamp;

            // Format the transaction ID
            $transactionId = "KGS_TID_{$uuid}_{$timestamp}";

            // Update the beneficiary record
            $beneficiary->transaction_id = $transactionId;
            $beneficiary->transaction_time_initiated = Carbon::now();
            $beneficiary->save();
        }

        return response()->json([
            'message' => 'Generated transaction IDs for ' . count($beneficiaries) . ' records',
            'updated_records' => count($beneficiaries)
        ]);
    }

    public function getApprovedPaymentSchools(Request $request)
    {
        $district = $request->query('district'); // or use $request->input('district')

        if (!$district) {
            return response()->json(['Message' => 'District parameter is required'], 400);
        }

        $schools = SchoolPaymentList::where('district', $district)->get();

        if ($schools->isEmpty()) {
            return response()->json(['Message' => 'No schools found for this district'], 404);
        }

        $transformed = $schools->map(function ($school) {
            return [
                'School' => $school->school,
                'SchoolEmis' => $school->school_emis,
                'Province' => $school->province,
                'District' => $school->district,
                'SchoolBank' => $school->school_bank,
                'SchoolBankBranch' => $school->school_bank_branch,
                'SchoolBankBranchCode' => $school->school_bank_branch_code,
                'SchoolBankAccount' => $school->school_bank_account,
                'DistrictGrantBank' => $school->district_grant_bank,
                'DistrictGrantBankBranch' => $school->district_grant_bank_branch,
                'DistrictGrantBankBranchCode' => $school->district_grant_bank_branch_code,
                'DistrictGrantBankAccount' => $school->district_grant_bank_account,
                'DistrictAdministrationBank' => $school->district_administration_bank,
                'DistrictAdministrationBankBranch' => $school->district_administration_bank_branch,
                'DistrictAdministrationBankBranchCode' => $school->district_administration_bank_branch_code,
                'DistrictAdministrationBankAccount' => $school->district_administration_bank_account
            ];
        });

        return response()->json($transformed);
    }


}






