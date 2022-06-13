<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonCompteController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="app_mon_compte")
     */
    public function index(): Response
    {
        return $this->render('mon_compte/index.html.twig', [
            'titre' => ' - Mon compte',
        ]);
    }
}
