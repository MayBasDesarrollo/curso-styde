<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeUserController extends Controller
{
    public function __invoke($name, $nickname = null)
    {
        $name = ucfirst($name);
        if ($nickname) {
            return "Bienvenido {$name}, tu apodo es {$nickname}";
        } else {
            return "Bienvenido {$name}";
        }
    }

    // public function showName ($name){
    //     $name = ucfirst($name);
    //     return "Bienvenido {$name}";
    // }

    // public function showNameNickname ($name, $nickname){
    //     $name = ucfirst($name);
    //     return "Bienvenido {$name}, tu apodo es {$nickname}";
    // }
}
