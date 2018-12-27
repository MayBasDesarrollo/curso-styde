<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModuleTest extends TestCase
{
    use RefreshDatabase;

    /*@test*/
    function test_it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Joel',
        ]);

        factory(User::class)->create([
            'name' => 'Ellie',
        ]);

        $this->withoutExceptionHandling();
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('Listado de Usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /*@test*/
    function test_it_shows_a_default_message_if_the_users_list_is_empty()
    {
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('No hay usuarios registrados');
    }

    /*@test*/
    function test_it_display_the_users_details()
    {
        $user = factory(User::class)->create([
            'name' => 'Mayerlin Bastidas'
        ]);

        $this->get('/usuarios/'.$user->id)
            ->assertStatus(200)
            ->assertSee('Mayerlin Bastidas');
    }

    /*@test*/
    function test_it_displays_a_404_eeror_if_the_user_is_not_found()
    {
        $this->get('/usuarios/1000')
            ->assertStatus(404)
            ->assertSee('Página no encontrada');
    }

    /*@test*/
    function test_it_loads_new_users_page()
    {
        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Nuevo Usuario');
    }

    /*@test*/
    function test_it_creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $this->post('/usuarios/', $this->getValidData())->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/mayerlin_19',
            'user_id' => User::finByEmail('may@ike.com')->id,
        ]);
    }

    /*@test*/
    function test_the_twitter_field_is_optional()
    {
        $this->withoutExceptionHandling();

        //CREO EL USUARIO CON INFO EN DOS TABLAS
        $this->post('/usuarios/', $this->getValidData([
            'twitter' => null,
        ]))->assertRedirect('usuarios');
        
        //vERIFICO QUE SE HAYA CREADO EL USUARIO
        $this->assertCredentials([
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456',
            ]);
            
        //vERIFICO QUE SE HAYA CREADO EL PERFIL DEL USUARIO
        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => null,
            'user_id' => User::finByEmail('may@ike.com')->id,
        ]);
    }

    //Mia
    /*@test*/
    function test_the_bio_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'bio' => null,
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['bio']);
        
        $this->assertEquals(0,User::count());
    }

    /*@test*/
    /*function test_the_twitter_is_url()
    {
        //$this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'Mayerlin',
                'email' => 'may@ike.com',
                'password' => '123456',
                'bio' => 'Development php and Vue.js',
                'twitter' => 'Development',
            ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['twitter']);
        
        $this->assertEquals(0,User::count());
    }*/

    /*@test*/
    function test_the_name_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => '',
                'email' => 'may@ike.com',
                'password' => '123456'
            ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['name']);
        
        $this->assertDatabaseEmpty('users');
    }

    /*@test*/
    function test_the_email_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => '',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        
        $this->assertDatabaseEmpty('users');
    }

    /*@test*/
    function test_the_email_must_be_valid()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => 'correp-no-valido',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        
        $this->assertDatabaseEmpty('users');
    }

    /*@test*/
    function test_the_email_must_be_unique()
    {
        factory(User::class)->create([
            'email' => 'may@ike.com',
        ]);

        $this->from('usuarios/nuevo')
            ->post('/usuarios/',  $this->getValidData([
                'email' => 'may@ike.com',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        
        $this->assertEquals(1,User::count());
    }

    /*@test*/
    function test_the_password_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'password' => '',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password']);
        
        $this->assertDatabaseEmpty('users');
    }

    //Mia
    /*@test*/
    function test_the_password_must_be_rango()
    {
        //$this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'Mayerlin',
                'email' => 'may@ike.com',
                'password' => '12345',
            ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password']);
        
        $this->assertEquals(0,User::count());
    }

    /*@test*/
    function test_it_loads_the_edit_user_page()
    {
        //$this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar Usuario')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /*@test*/
    function test_it_update_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->put("/usuarios/{$user->id}", [
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456'
            ])->assertRedirect("/usuarios/{$user->id}");
            //])->assertRedirect(route('users.index'));

        $this->assertCredentials([
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456',
        ]);
    }

    /** @test */
    // function the_name_is_required_when_updating_the_user()
    // {
    //     $user = factory(User::class)->create();
    //     $this->from("usuarios/{$user->id}/editar")
    //         ->put("usuarios/{$user->id}", [
    //             'name' => '',
    //             'email' => 'duilio@styde.net',
    //             'password' => '123456'
    //         ])
    //         ->assertRedirect("usuarios/{$user->id}/editar")
    //         ->assertSessionHasErrors(['name']);
    //     $this->assertDatabaseMissing('users', ['email' => 'duilio@styde.net']);
    // }
    /** @test */
    // function the_email_must_be_valid_when_updating_the_user()
    // {
    //     $user = factory(User::class)->create();
    //     $this->from("usuarios/{$user->id}/editar")
    //         ->put("usuarios/{$user->id}", [
    //             'name' => 'Duilio Palacios',
    //             'email' => 'correo-no-valido',
    //             'password' => '123456'
    //         ])
    //         ->assertRedirect("usuarios/{$user->id}/editar")
    //         ->assertSessionHasErrors(['email']);
    //     $this->assertDatabaseMissing('users', ['name' => 'Duilio Palacios']);
    // }
    /** @test */
    // function the_email_must_be_unique_when_updating_the_user()
    // {
    //     //$this->withoutExceptionHandling();
    //     factory(User::class)->create([
    //         'email' => 'existing-email@example.com',
    //     ]);
    //     $user = factory(User::class)->create([
    //         'email' => 'duilio@styde.net'
    //     ]);
    //     $this->from("usuarios/{$user->id}/editar")
    //         ->put("usuarios/{$user->id}", [
    //             'name' => 'Duilio',
    //             'email' => 'existing-email@example.com',
    //             'password' => '123456'
    //         ])
    //         ->assertRedirect("usuarios/{$user->id}/editar")
    //         ->assertSessionHasErrors(['email']);
    //     //
    // }
    /** @test */
    // function the_users_email_can_stay_the_same_when_updating_the_user()
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
    // function the_password_is_optional_when_updating_the_user()
    // {
    //     $oldPassword = 'CLAVE_ANTERIOR';
    //     $user = factory(User::class)->create([
    //         'password' => bcrypt($oldPassword)
    //     ]);
    //     $this->from("usuarios/{$user->id}/editar")
    //         ->put("usuarios/{$user->id}", [
    //             'name' => 'Duilio',
    //             'email' => 'duilio@styde.net',
    //             'password' => ''
    //         ])
    //         ->assertRedirect("usuarios/{$user->id}"); // (users.show)
    //     $this->assertCredentials([
    //         'name' => 'Duilio',
    //         'email' => 'duilio@styde.net',
    //         'password' => $oldPassword // VERY IMPORTANT!
    //     ]);
    // }
    /** @test */
    // function it_deletes_a_user()
    // {
    //     $this->withoutExceptionHandling();
    //     $user = factory(User::class)->create();
    //     $this->delete("usuarios/{$user->id}")
    //         ->assertRedirect('usuarios');
    //     $this->assertDatabaseMissing('users', [
    //     'id' => $user->id
    //     ]);
    //     // Or:
    //     //$this->assertSame(0, User::count());
    // }
 

    protected function getValidData(array $custom = [])
    {
        return array_filter(array_merge([
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456',
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/mayerlin_19'
        ], $custom));
    }
}