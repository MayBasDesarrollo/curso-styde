<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Profession;
use App\UserProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $professionID =  Profession::whereTitle('Back-End Developer')->value('id');

        $user = factory(User::class)->create([
            'name'=> 'Mayerlin Bastidas',
            'email'=> 'mbastidas@ike.com',
            'password'=> bcrypt('laravel'),
        ]);

        $user->profile()->create([
            'bio' =>'Programadora',
            'profession_id' => $professionID,

        ]);

        factory(User::class, 29)->create()->each(function ($user) {
            $user->profile()->create(
                factory(\App\UserProfile::class)->raw()
            );
        });
    }
}
