<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => '1',
            'reg_id' => 'root',
            'password' => Hash::make('ghfjdk'),
            'name' => '최고 관리자',
            'tel' => '01037635613',
            'email' =>  'seongs70@naver.com',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'role_id' => '2',
            'reg_id' => 'admin',
            'password' => Hash::make('000000'),
            'name' => '관리자',
            'tel' => '01037635613',
            'email' => 'seongs156@gmail.com',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
