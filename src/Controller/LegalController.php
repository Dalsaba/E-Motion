<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/mentions_legales', name: 'app_mentions_legales')]
    public function mentions_legales(): Response
    {
        return $this->render('legal/mentions_legales.html.twig', [
            'controller_name' => 'MentionsLegalesController',
        ]);
    }

    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('legal/cgv.html.twig', [
            'controller_name' => 'MentionsLegalesController',
        ]);
    }

    #[Route('/politique_de_confidentialite', name: 'app_politique_confidentialite')]
    public function politique_confidentialite(): Response
    {
        return $this->render('legal/politique_confidentialite.html.twig', [
            'controller_name' => 'MentionsLegalesController',
        ]);
    }
}
