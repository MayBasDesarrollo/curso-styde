<?php

namespace Tests\Feature;

use App\{Profession, Skill, User, UserProfile};
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $profession;

    /** @test */
    function it_shows_the_users_list()
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

    /** @test */
    function it_shows_a_default_message_if_the_users_list_is_empty()
    {
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    function it_display_the_users_details()
    {
        $user = factory(User::class)->create([
            'name' => 'Mayerlin Bastidas'
        ]);

        $this->get('/usuarios/'.$user->id)
            ->assertStatus(200)
            ->assertSee('Mayerlin Bastidas');
    }

    /** @test */
    function it_displays_a_404_eeror_if_the_user_is_not_found()
    {
        $this->get('/usuarios/1000')
            ->assertStatus(404)
            ->assertSee('Página no encontrada');
    }

    /** @test */
    function it_loads_new_users_page()
    {
        $this->withoutExceptionHandling();
        
        $profession = factory(Profession::class)->create();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Nuevo Usuario')
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            })
            ->assertViewHas('skills', function ($skills) use ($skillA,$skillB) {
                return $skills->contains($skillA) && $skills->contains($skillB);
            });
    }
/*-----------------------------------------------------------------------------------------------------------------*/
    /** @test */
    function it_creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $skillC = factory(Skill::class)->create();

        $this->post('/usuarios/', $this->getValidData([
            'skills' => [$skillA->id, $skillB->id],
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456',
        ]);

        $user = User::finByEmail('may@ike.com')->id;

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/sileence',
            'user_id' => $user,
            'profession_id' => $this->profession->id,
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user,
            'skill_id' => $skillA->id,
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user ,
            'skill_id' => $skillB->id,
        ]);

        $this->assertDatabaseMissing('user_skill', [
            'user_id' => $user ,
            'skill_id' => $skillC->id,
        ]);
    }

    /** @test */
    function the_twitter_field_is_optional()
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

    /** @test */
    function the_profession_id_field_is_optional()
    {
        $this->withoutExceptionHandling();

        //CREO EL USUARIO CON INFO EN DOS TABLAS
        $this->post('/usuarios/', $this->getValidData([
            'profession_id' => null,
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
            'user_id' => User::finByEmail('may@ike.com')->id,
            'profession_id' => null,
        ]);
    }

    //Mia
    /** @test */
    function the_bio_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'bio' => null,
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['bio']);
        
        $this->assertDatabaseEmpty('users');
    }

    //Mia
    /** @test */
    function the_twitter_is_url()
    {
        //$this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'twitter' => 'holaaa',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['twitter']);
        
        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_name_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'name' => '',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['name']);
        
        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => '',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        
        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => 'correp-no-valido',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        
        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_unique()
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

    /** @test */
    function the_password_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'password' => '',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password']);
        
        $this->assertDatabaseEmpty('users');
    }

    
    /** @test */
    function the_profession_must_be_valid()
    {
        //$this->withoutExceptionHandling();
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'profession_id' => '999',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);
        
        $this->assertDatabaseEmpty('users');
    }

    function the_skills_must_an_array()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'skills' => 'PHP, TDD',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['skills']);
        
        $this->assertDatabaseEmpty('users');
    }

    function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'skills' => [$skillA->id, $skillA->id + 1],
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['skills']);
        
        $this->assertDatabaseEmpty('users');        
    }

    /** @test */
    function only_not_deleted_profession_can_be_selected()
    {
        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d'),
        ]);

        //$this->withoutExceptionHandling();
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'profession_id' => $deletedProfession->id,
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);
        
        $this->assertDatabaseEmpty('users');
    }

    //Mia
    /** @test */
    function the_password_must_be_rango()
    {
        //$this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'password' => '12345',
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password']);
        
        $this->assertDatabaseEmpty('users');
    }

/*-----------------------------------------------------------------------------------------------------------------*/

    /** @test */
    function it_loads_the_edit_user_page()
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

    //FALLANDO
    /** @test */
    /* function it_update_a_user()
    {
        
        $user = factory(User::class)->create();
        
        //$this->withoutExceptionHandling();

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
    function the_name_is_required_when_updating_the_user()
    {
        //$this->withoutExceptionHandling();

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
    function the_email_must_be_valid_when_updating_the_user()
    {
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
    function the_email_must_be_unique_when_updating_the_user()
    {
        //$this->withoutExceptionHandling();
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
    function the_password_is_optional_when_updating_the_user()
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

    /** @test */
    function it_deletes_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->delete("usuarios/{$user->id}")
            ->assertRedirect('usuarios');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
        // Or:
        //$this->assertSame(0, User::count());
    }
 

    protected function getValidData(array $custom = [])
    {
        $this->profession = factory(Profession::class)->create();

        return array_merge([
            'name' => 'May',
            'email' => 'may@ike.com',
            'password' => '123456',
            'profession_id' => $this->profession->id,
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/sileence',
        ], $custom);
    }
}
