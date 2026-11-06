<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\JsonResponse;

class WilayahController extends Controller
{
    /**
     * Dapatkan daftar kabupaten/kota berdasarkan kode provinsi.
     */
    public function getRegenciesByProvince(string $provinceId): JsonResponse
    {
        $regencies = Regency::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return response()->json($regencies);
    }
}
