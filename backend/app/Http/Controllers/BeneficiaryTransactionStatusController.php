<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BeneficiaryTransactionStatus;
use App\Models\BeneficiaryImage;

class BeneficiaryTransactionStatusController extends Controller
{
    
    public function store(Request $request)
    {
        // Check for district header
        $district = $request->query('district');
        if (empty($district)) {
            return response()->json(['message' => 'The district parameter is required'], 400);
        }

        $data = $request->json()->all();

        // Validate each item in the data array
        foreach ($data as $item) {
            $validator = Validator::make($item, [
                'transaction_id' => 'required|string',
                'beneficiary_no' => 'required|string',
                'payment_status' => 'required|string',
                'images' => 'required|array',
                'date_received' => 'required|date',
                'gps_latitude' => 'required|numeric',
                'gps_longitude' => 'required|numeric',
                'gps_altitude' => 'required|numeric',
                'gps_timestamp' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Create or update the beneficiary status
            BeneficiaryTransactionStatus::create($item);
        }

        return response()->json(['message' => count($data) . ' records received successfully'], 200);
    }


    public function storeImage(Request $request)
    {
        // Check for district header
        $district = $request->query('district');
        if (empty($district)) {
            return response()->json(['message' => 'The district parameter is required'], 400);
        }

        $data = $request->json()->all();

        // Validate each item in the data array
        foreach ($data as $item) {
            $validator = Validator::make($item, [
                'beneficiary_number' => 'required|string',
                'image_id' => 'required|string',
                'image_url' => 'required|url',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Create a new image record and save it
            $image = new BeneficiaryImage([
                'beneficiary_number' => $item['beneficiary_number'],
                'image_id' => $item['image_id'],
                'image_url' => $item['image_url'],
            ]);

            $image->save();
        }

        return response()->json(['message' => count($data) . ' image records sent successfully'], 201);
    }

}

