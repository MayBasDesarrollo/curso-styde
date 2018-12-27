<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeletUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::delete('delete users where name = :name', ['name' =>'Diego']);
        DB::table('users')->where('name', 'Diego')->delete();
    }
}
