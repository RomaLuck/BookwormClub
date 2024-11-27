<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Route('/books', name: 'vue_books_list')]
    #[Route('/books/{number}', name: 'vue_books_show')]
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
}
