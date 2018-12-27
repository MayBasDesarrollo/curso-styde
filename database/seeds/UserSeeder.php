<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Profession;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //$profession = DB::select('SELECT id FROM profession WHERE title = "Back-End Develope"');
        //$profession = DB::select('SELECT id FROM profession WHERE title = :title', ['title'=>'Back-End Develope']);
        
        // $profession = DB::table('profession')->select('id')->take(1)->get();
        // dd($profession->first()->id);

        // $profession = DB::table('profession')
        //     ->select('id')
        //     ->where(['title' => 'Back-End Developer'])
        //     ->first();

        $professionID =  Profession::whereTitle('Back-End Developer')->value('id');

        // DB::table('Users')->insert([
        //     'name'=> 'Mayerlin Bastidas',
        //     'email'=> 'mbastidas@ike.com',
        //     'password'=> bcrypt('laravel'),
        //     'profession_id' => DB::table('professions')->whereTitle('Back-End Developer')->value('id'),
        // ]);

        // User::create([
        //     'name'=> 'Mayerlin Bastidas',
        //     'email'=> 'mbastidas@ike.com',
        //     'password'=> bcrypt('laravel'),
        //     'profession_id' => $professionID,
        // ]);

        factory(User::class)->create([
            'name'=> 'Mayerlin Bastidas',
            'email'=> 'mbastidas@ike.com',
            'password'=> bcrypt('laravel'),
            'profession_id' => $professionID,
        ]);

        factory(User::class)->create([
            'profession_id' => $professionID,
        ]);

        factory(App\User::class, 48)->create();

        // DB::table('Users')->insert([
        //     'name'=> 'Matias',
        //     'email'=> 'matias@ike.com',
        //     'password'=> bcrypt('laravel'),
        // ]);
    }
}
