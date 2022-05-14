<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/prise-de-rendez-vous", name="app_commande")
     */
    public function index(Request $request): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);

        return $this->render('commande/index.html.twig', [
            'titre' => ' - Prendre rendez-vous',
            'rdvForm' => $form->createView(),
        ]);
    }
}
