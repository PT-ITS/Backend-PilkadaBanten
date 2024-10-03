<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\User;
use App\Models\KTA;

class EmailController extends Controller
{

    // api
    public function index($id)
    {
        // Mengambil pengguna dengan ID yang sesuai dan status 0
        $user = User::where('id', $id)
                    ->where('status', '0')
                    ->first();
    
        // Memeriksa apakah pengguna ditemukan
        if (!$user) {
            return response()->json([
                "message" => "User not found or status is not 0",
                "code" => 404
            ]);
        }
    
        // Mengirim email kepada pengguna
        Mail::to($user->email)->send(new SendEmail($user));
    
        return response()->json([
            "message" => "Email sent successfully",
            "code" => 200
        ]);
    }

    
        // web
    public function password($id)
    {
        $id = base64_decode($id);
        return view('verifikasi', compact('id'));
        // return $id;
    }

    public function profile()
    {
        return view('profile');
    }

    public function aktivasi(Request $request)
    {
        $user = User::find($request->id);
        if ($user->status === '1') {
            return "akun sudah di aktivasi sebelumnya!!";
        }
        $user->password = bcrypt($request->password);
        $user->status = '1';
        $user->save();

        // generate KTA
        $tanggal_lahir = date('ymd', strtotime($request->tanggal_lahir));
        $tanggal_bergabung = date('ymd');
        $id = str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $ktaNumber = $tanggal_lahir . $tanggal_bergabung . $id;

        $createKTA = KTA::create([
            'no_kta' => $ktaNumber,
            'jenis_kta' => 'pertama',
            'user_id' => $user->id
        ]);

        $createKTA->save();
        

        // alihkan ke vue js
        return "
        <p>Aktivasi akun berhasil, silahkan login di aplikasi!</p>
        <a href='https://alicornbot.com/login'>
            <button>Login</button>
        </a>
    ";
    }
}
