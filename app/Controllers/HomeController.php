<?php

namespace App\Controllers;

use App\Core\Controller;
use JetBrains\PhpStorm\NoReturn;


class HomeController extends Controller
{
    public function home(): string
    {
        return $this->renderView('home/index.php', [ 'title' => 'Accueil' ]);
    }

    #[NoReturn]
    public function contact()
    {
        return $this->redirectToRoute('home', [ 'state' => 'success' ]);
    }
}