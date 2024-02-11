<?php

use App\Model\Admin;
use Illuminate\Support\Str;
use App\Model\BusinessSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('admins')->insert([
        //     'id' => 1,
        //     'f_name' => 'Master Admin',
        //     'l_name' => 'Khandakar',
        //     'phone' => '01759412381',
        //     'email' => 'admin@admin.com',
        //     'image' => 'def.png',
        //     'password' => bcrypt(12345678),
        //     'remember_token' =>Str::random(10),


        // ]);
        // Admin::create(['name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => bcrypt('password'),'remember_token' =>Str::random(10)]);
        // BusinessSetting::create(['key'=>'admin','value'=>'admin']);
    }
}
