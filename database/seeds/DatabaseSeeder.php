<?php

use Illuminate\Database\Seeder;
use App\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin','operator posyandu','petugas kesehatan','peserta'];
        foreach ($roles as $key => $value) {
            Role::create([
                'role' => $value
            ]);
        }
    }
}
