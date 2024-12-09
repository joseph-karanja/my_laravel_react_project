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
            return response()->json(['Message' => 'The district parameter is required'], 400);
        }
    
        $data = $request->json()->all();
    
        // Validate each item in the data array
        foreach ($data as $item) {
            $validator = Validator::make($item, [
                'TransactionId' => 'required|string',
                'BeneficiaryNo' => 'required|string',
                'PaymentStatus' => 'required|string',
                'ImageIDs' => 'required|array',
                'DateReceived' => 'required|date',
                'GpsLatitude' => 'required|numeric',
                'GpsLongitude' => 'required|numeric',
                'GpsAltitude' => 'required|numeric',
                'GpsTimestamp' => 'required|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
    
            // Format the GPS timestamp using the global namespace for DateTime
            $gpsTimestamp = new \DateTime($item['GpsTimestamp']);
            $formattedGpsTimestamp = $gpsTimestamp->format('Y-m-d H:i:s');
    
            // Create or update the beneficiary status
            BeneficiaryTransactionStatus::create([
                'transaction_id' => $item['TransactionId'],
                'beneficiary_no' => $item['BeneficiaryNo'],
                'payment_status' => $item['PaymentStatus'],
                'images' => implode(',', $item['ImageIDs']),
                'date_received' => $item['DateReceived'],
                'gps_latitude' => $item['GpsLatitude'],
                'gps_longitude' => $item['GpsLongitude'],
                'gps_altitude' => $item['GpsAltitude'],
                'gps_timestamp' => $formattedGpsTimestamp,
            ]);
        }
    
        return response()->json(['Message' => count($data) . ' records received successfully'], 200);
    }
    
    public function storeImage(Request $request)
    {
        // Check for district param
        $district = $request->query('district');
        if (empty($district)) {
            return response()->json(['Message' => 'The district parameter is required'], 400);
        }
    
        $data = $request->json()->all();
    
        // Validate each item in the data array
        foreach ($data as $item) {
            $validator = Validator::make($item, [
                'BeneficiaryNumber' => 'required|string',
                'ImageId' => 'required|string',
                'ImageUrl' => 'required|url',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
    
            // Transform the keys from PascalCase to snake_case for database insertion
            $transformedItem = [
                'beneficiary_number' => $item['BeneficiaryNumber'],
                'image_id' => $item['ImageId'],
                'image_url' => $item['ImageUrl'],
            ];
    
            // Create a new image record and save it
            $image = new BeneficiaryImage($transformedItem);
            $image->save();
        }
    
        return response()->json(['Message' => count($data) . ' image records sent successfully'], 201);
    }
}

