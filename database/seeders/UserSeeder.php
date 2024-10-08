<?php

namespace Database\Seeders;

use App\Models\bantuan_relawan;
use App\Models\data_pemilih;
use App\Models\data_rt;
use App\Models\data_rw;
use App\Models\pemuka_agama;
use App\Models\relawan;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // pengurus
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345'),
            'created_at' => Carbon::create(2024, 10, 3, 12, 0, 0), // Format: (year, month, day, hour, minute, second)
            'updated_at' => Carbon::create(2024, 10, 3, 12, 0, 0) // Format: (year, month, day, hour, minute, second)
        ]);
        relawan::create([
            'nik' => '3601',
            'nama' => 'Mang Ari',
            'alamat' => 'Jl. Raya Ciboleger',
            'kota' => 'Kabupaten Lebak',
            'kec' => 'Kec. Leuwidamar',
            'kel' => 'Cisimeut',
            'rt_rw' => 'RT 09/RW 09',
            'jumlah_data' => '1000',
        ]);
        data_pemilih::create([
            'nik' => '3602',
            'nama' => 'Mang Yayan',
            'alamat' => 'Jl. Raya Ciboleger',
            'kota' => 'Kabupaten Lebak',
            'kec' => 'Kec. Leuwidamar',
            'desa_kel' => 'Cisimeut',
            'rt_rw' => 'RT 09/RW 09',
            'tps' => 'TPS 01',
            'relawan_id' => '1',
        ]);
        data_pemilih::create([
            'nik' => '3603',
            'nama' => 'Mang Ucup',
            'alamat' => 'Jl. Raya Ciboleger',
            'kota' => 'Kabupaten Lebak',
            'kec' => 'Kec. Leuwidamar',
            'desa_kel' => 'Cisimeut',
            'rt_rw' => 'RT 09/RW 09',
            'tps' => 'TPS 01',
            'relawan_id' => '1',
        ]);
        data_pemilih::create([
            'nik' => '3604',
            'nama' => 'Mang Asep',
            'alamat' => 'Jl. Raya Ciboleger',
            'kota' => 'Kabupaten Lebak',
            'kec' => 'Kec. Leuwidamar',
            'desa_kel' => 'Cisimeut',
            'rt_rw' => 'RT 09/RW 09',
            'tps' => 'TPS 01',
            'relawan_id' => '1',
        ]);
        data_rw::create([
            'nik' => '3605',
            'nama' => 'Mang Cecep',
            'kota' => 'Kabupaten Lebak',
            'kec' => 'Kec. Leuwidamar',
            'kel' => 'Cisimeut',
            'rw' => 'RW 09',
            'support' => '1',
            'relawan_id' => '1',
        ]);
        data_rt::create([
            'nik' => '3606',
            'nama' => 'Mang Bagas',
            'kota' => 'Kabupaten Lebak',
            'kec' => 'Kec. Leuwidamar',
            'kel' => 'Cisimeut',
            'rw' => 'RW 09',
            'rt' => 'RT 09',
            'support' => '1',
            'relawan_id' => '1',
        ]);
        pemuka_agama::create([
            'nik' => '3607',
            'nama' => 'Kyai Hari',
            'pesantren' => 'Ponpes Al-Hari',
            'alamat' => 'Jl. Raya Ciboleger',
            'kota' => 'Kabupaten Lebak',
            'kec' => 'Kec. Leuwidamar',
            'kel' => 'Cisimeut',
            'support' => '1',
            'relawan_id' => '1',
        ]);
        bantuan_relawan::create([
            'jenis_bantuan' => 'beras',
            'tanggal' => '2024-10-05',
            'sasaran' => 'warga',
            'harga_satuan' => '45000',
            'jumlah_penerima' => '1000',
            'jumlah_bantuan' => '45000000',
            'relawan_id' => '1',
        ]);
    }
}
