<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //sql:insert into user (col,col) value ();
        //query builder
        DB::table('users')->insert([
            'name'=>'yousef dalloul',
            'email'=>'yousef@test.com',
            'password'=>Hash::make('password'),
        ]);

    }
}
