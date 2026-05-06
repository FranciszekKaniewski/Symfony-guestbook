<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutController extends AbstractController
{
    #[Route('/o-nas', name: 'about')]
    public function index(): Response
    {
        return new Response('<h1>O naszych konferencjach</h1>');
    }
}
