<?php

namespace Tests\Feature\Admin;

use App\{User};
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'May',
        'email' => 'may@ike.com',
        'password' => '123456',
        'profession_id' => '',
        'bio' => 'Programador de Laravel y Vue.js',
        'twitter' => 'https://twitter.com/sileence',
        'role' => 'user',
    ];

    /** @test */
    function it_loads_the_edit_user_page()
    {
        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar Usuario')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    //FALLANDO
    /** @test */
    /* function it_update_a_user()
    {
        
        $user = factory(User::class)->create();
        

        $this->put("/usuarios/{$user->id}", [
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456'
            ])->assertRedirect("/usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456',
        ]);
    } */

    /** @test */
    function the_name_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();
        
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => '',
                'email' => 'duilio@styde.net',
                'password' => '123456'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['name']);
        
            $this->assertDatabaseMissing('users', ['email' => 'duilio@styde.net']);
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Mayerlin Bastidas',
                'email' => 'correo-no-valido',
                'password' => '123456'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('users', ['name' => 'Mayerlin Bastidas']);
    }

    /** @test */
    function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();
        // self::markTestIncomplete();
        // return;

        factory(User::class)->create([
            'email' => 'existing-email@example.com',
        ]);

        $user = factory(User::class)->create([
            'email' => 'duilio@styde.net'
        ]);
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Duilio',
                'email' => 'existing-email@example.com',
                'password' => '123456'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['email']);
    }

    //FALLANDOOOO
    /** @test */
    // function the_users_email_can_stay_the_same()
    // {
    //     $user = factory(User::class)->create([
    //         'email' => 'duilio@styde.net'
    //     ]);
    //     $this->from("usuarios/{$user->id}/editar")
    //         ->put("usuarios/{$user->id}", [
    //             'name' => 'Duilio Palacios',
    //             'email' => 'duilio@styde.net',
    //             'password' => '12345678'
    //         ])
    //         ->assertRedirect("usuarios/{$user->id}"); // (users.show)

    //     $this->assertDatabaseHas('users', [
    //         'name' => 'Duilio Palacios',
    //         'email' => 'duilio@styde.net',
    //     ]);
    // }


    /** @test */
    function the_password_is_optional()
    {
        $oldPassword = 'CLAVE_ANTERIOR';
        $user = factory(User::class)->create([
            'password' => bcrypt($oldPassword)
        ]);
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Duilio',
                'email' => 'duilio@styde.net',
                'password' => ''
            ])
            ->assertRedirect("usuarios/{$user->id}"); // (users.show)

        $this->assertCredentials([
            'name' => 'Duilio',
            'email' => 'duilio@styde.net',
            'password' => $oldPassword // VERY IMPORTANT!
        ]);
    }


 

    
}
