<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Hiburan;
use App\Models\Fnb;

class DataStreamController extends Controller
{
    public function streamData()
    {
        try {
            // Set up a streamed response
            $response = new StreamedResponse(function () {
                echo "event: message\n";
                echo 'data: {"message": "Memulai pengiriman data..."}';
                echo "\n\n";
                flush();

                // Simulasi pengambilan data hotel
                $hotelData = Hotel::all();
                foreach ($hotelData as $hotel) {
                    echo "event: hotel\n";
                    echo 'data: ' . json_encode($hotel) . "\n\n";
                    flush();
                }

                // Simulasi pengambilan data hiburan
                $hiburanData = Hiburan::all();
                foreach ($hiburanData as $hiburan) {
                    echo "event: hiburan\n";
                    echo 'data: ' . json_encode($hiburan) . "\n\n";
                    flush();
                }

                // Simulasi pengambilan data fnb
                $fnbData = Fnb::all();
                foreach ($fnbData as $fnb) {
                    echo "event: fnb\n";
                    echo 'data: ' . json_encode($fnb) . "\n\n";
                    flush();
                }

                // Mengirim event akhir
                echo "event: complete\n";
                echo 'data: {"message": "Pengiriman data selesai."}';
                echo "\n\n";
                flush();
            });

            // Set response headers
            $response->headers->set('Content-Type', 'text/event-stream');
            $response->headers->set('Cache-Control', 'no-cache');
            $response->headers->set('Connection', 'keep-alive');

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }
}

