<?php

namespace App\Http\Controllers;

use App\Models\CompanyAddress;
use Illuminate\Http\Request;

class CompanyAddressController extends Controller
{
    public function index()
    {
        try {
            $companyAddresses = CompanyAddress::with(['company:id,company_name', 'country:id,iso,name'])->get();

            $data = $companyAddresses->transform(function ($address) {
                return [
                    'id' => $address->id,
                    'company' => [
                        'id' => $address->company_id ?? null,
                        'company_name' => $address->company->company_name ?? null,
                    ],
                    'country' => [
                        'id' => $address->country_id ?? null,
                        'iso' => $address->country->iso ?? null,
                        'name' => $address->country->name ?? null,
                    ],
                    'address' => $address->address,
                    'is_default' => $address->is_default,
                    'location' => $address->location,
                    'tax_number' => $address->tax_number,
                    'tax_name' => $address->tax_name,
                    'longitude' => $address->longitude,
                    'latitude' => $address->latitude,
                ];
            });

            // Custom pagination response format
            return response()->json([
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Mengambil data Company Address.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
