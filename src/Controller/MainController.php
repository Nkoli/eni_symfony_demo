<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

//    #[Route('/main', name: 'main_home', methods: ['GET'])]
//    public function main(): Response {
//        return new Response("<h1>Hello main page</h1>");
//    }

    #[Route('/test', name: 'main_test', methods: ['GET'])]
    public function test(): Response {
        $serie = [
            "title" => "<h1>Game of Thrones</h1>",
            "year" => 2010
        ];
//        return new Response("<h1>Hello test page</h1>");
        return $this->render(
            "main/test.html.twig",
            [
                "mySerie"=>$serie,
                "anotherVar"=>4242
            ]
        );
    }
}
