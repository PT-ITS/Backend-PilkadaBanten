<?php

namespace Database\Seeders;

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
            'name' => 'disparekrafdki',
            'email' => 'disparekrafdki@surveiwisatadki.com',
            'password' => Hash::make('W1547a!&$1k'),
            'alamat' => 'test',
            'level' => '1',
            'status' => '1',
            'noHP' => '0812345',
            'created_at' => Carbon::create(2024, 4, 24, 12, 0, 0) // Format: (year, month, day, hour, minute, second)
        ]);
        User::create([
            'name' => 'surveyor',
            'email' => 'instincsurveyor@surveiwisatadki.com',
            'password' => Hash::make('W1547a!&$1k'),
            'alamat' => 'test',
            'level' => '0',
            'status' => '1',
            'noHP' => '0812345'
        ]);
        User::create([
            'name' => 'akmalnugra15',
            'email' => 'akmalnugra15@surveiwisatadki.com',
            'password' => Hash::make('akmalnugra15'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'ramadhan123.rar',
            'email' => 'ramadhan123.rar@surveiwisatadki.com',
            'password' => Hash::make('ramadhan123.rar'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'sadewaprawirodihardjo',
            'email' => 'sadewaprawirodihardjo@surveiwisatadki.com',
            'password' => Hash::make('sadewaprawirodihardjo'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'kevinhadi.saputra3',
            'email' => 'kevinhadi.saputra3@surveiwisatadki.com',
            'password' => Hash::make('kevinhadi.saputra3'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'Birrulwalidain814',
            'email' => 'Birrulwalidain814@surveiwisatadki.com',
            'password' => Hash::make('Birrulwalidain814'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'mhdjfri.08',
            'email' => 'mhdjfri.08@surveiwisatadki.com',
            'password' => Hash::make('mhdjfri.08'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'bimo.adhirajasa',
            'email' => 'bimo.adhirajasa@surveiwisatadki.com',
            'password' => Hash::make('bimo.adhirajasa'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'valenevlds',
            'email' => 'valenevlds@surveiwisatadki.com',
            'password' => Hash::make('valenevlds'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'nayakhaanin',
            'email' => 'nayakhaanin@surveiwisatadki.com',
            'password' => Hash::make('nayakhaanin'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
        User::create([
            'name' => 'cavanisse',
            'email' => 'cavanisse@surveiwisatadki.com',
            'password' => Hash::make('cavanisse'),
            'alamat' => 'alamat surveyor',
            'level' => '0',
            'status' => '1',
            'noHP' => '08123456789'
        ]);
    }
}
