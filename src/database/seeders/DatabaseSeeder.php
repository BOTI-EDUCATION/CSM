<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('roles')->insert([
        //     'label' => 'Admin',
        //     'alias' => 'admin',
        // ]);

        // DB::table('users')->insert([
        //     'role_id' => 1,
        //     'nom' => 'semoud',
        //     'prenom' => 'ahmed',
        //     'email' => 'a.semoud@gmail.com',
        //     'password' => Hash::make('sysadmin'),
        //     'telephone' => '0600000000',
        //     'fonction' => 'Manager',
        //     'adresse' => 'adresse',
        //     'enabled' => 1,
        // ]);

        DB::table('users')->insert([
            'role_id' => 1,
            'nom' => 'gourchane',
            'prenom' => 'anas',
            'email' => 'anas',
            'password' => Hash::make('test'),
            'telephone' => '0600000000',
            'fonction' => 'Manager',
            'adresse' => 'adresse',
            'enabled' => 1,
        ]);
    }
}
