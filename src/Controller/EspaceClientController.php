<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceClientController extends AbstractController
{
    #[Route('/espace/client', name: 'app_espace_client')]
    public function index(): Response
    {
        return $this->render('espace_client/index.html.twig', [
            'controller_name' => 'EspaceClientController',
        ]);
    }
}
