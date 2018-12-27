<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /*@test*/
    function test_it_welcomes_users_with_nickname()
    {
        $this->get('/saludos/mayerlin/may')
            ->assertStatus(200)
            ->assertSee('Bienvenido Mayerlin, tu apodo es may');
    }

    /*@test*/
    function test_it_welcomes_users_without_nickname()
    {
        $this->get('/saludos/mayerlin')
            ->assertStatus(200)
            ->assertSee('Bienvenido Mayerlin');
    }
}
