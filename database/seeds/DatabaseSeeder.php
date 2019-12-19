<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTable([
            'users',
            'skills',
            'professions',
        ]);

        // $this->call(UsersTableSeeder::class);
        $this->call([
            ProfessionSeeder::class,
            SkillSeeder::class,
            UserSeeder::class,
            ]);
    }

    public function truncateTable(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); //Para desactivar las claves foraneas

        foreach ($tables as $table){
            DB::table($table)->truncate();
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
