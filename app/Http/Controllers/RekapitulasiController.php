<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Hiburan;
use App\Models\Fnb;
use Illuminate\Http\Request;

class RekapitulasiController extends Controller
{
    public function rekapitulasi(Request $request)
    {
        try {
            // Menentukan jumlah item per halaman
            $perPage = $request->input('per_page', 15);
            $searchQuery = $request->input('search', '');

            // Mendapatkan data hotel dengan pencarian di semua field yang dipanggil dan paginasi
            $hotelData = Hotel::select([
                'id',
                'nib',
                'namaHotel',
                'alamat',
                'namaPj',
                'teleponPj'
            ])
            ->where(function($query) use ($searchQuery) {
                $query->where('nib', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('namaHotel', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('alamat', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('namaPj', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('teleponPj', 'LIKE', '%' . $searchQuery . '%');
            })
            ->paginate($perPage, ['*'], 'hotel_page');

            // Mendapatkan data hiburan dengan pencarian di semua field yang dipanggil dan paginasi
            $hiburanData = Hiburan::select([
                'id',
                'nib',
                'namaHiburan',
                'alamat',
                'namaPj',
                'teleponPj'
            ])
            ->where(function($query) use ($searchQuery) {
                $query->where('nib', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('namaHiburan', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('alamat', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('namaPj', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('teleponPj', 'LIKE', '%' . $searchQuery . '%');
            })
            ->paginate($perPage, ['*'], 'hiburan_page');

            // Mendapatkan data fnb dengan pencarian di semua field yang dipanggil dan paginasi
            $fnbData = Fnb::select([
                'id',
                'nib',
                'namaFnb',
                'alamat',
                'namaPj',
                'teleponPj'
            ])
            ->where(function($query) use ($searchQuery) {
                $query->where('nib', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('namaFnb', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('alamat', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('namaPj', 'LIKE', '%' . $searchQuery . '%')
                      ->orWhere('teleponPj', 'LIKE', '%' . $searchQuery . '%');
            })
            ->paginate($perPage, ['*'], 'fnb_page');

            $allData = [
                "hotel" => $hotelData,
                "hiburan" => $hiburanData,
                "fnb" => $fnbData
            ];

            return response()->json([
                "statusCode" => 200,
                "data" => $allData,
                "message" => 'Get semua data success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }
}
