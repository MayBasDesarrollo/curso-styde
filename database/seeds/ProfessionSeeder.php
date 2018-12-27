<?php

use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use App\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //DB::insert('INSERT INTO Profession (title) values (:title)', ['title'=>'Back-End Developer']);
        
        // DB::table('Profession')->insert([
        //     'title'=> 'Back-End Developer',
        // ]);

        Profession::create([
            'title'=> 'Back-End Developer',
        ]);

        Profession::create([
            'title'=> 'Front-End Developer',
        ]);

        Profession::create([
            'title'=> 'Web Analyst',
        ]);

        Profession::create([
            'title'=> 'Web Desing',
        ]);

        factory(Profession::class)->times(16)->create();

    }
}
