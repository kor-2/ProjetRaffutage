<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\FactureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    /**
     * @Route("/prise-de-rendez-vous", name="app_commande")
     */
    public function index(ManagerRegistry $doctrine ,Request $request, Commande $commande = null): Response
    {
        // test
        $test ="oui";
        dump($test);

        if (!$commande) {

            $commande = new Commande();
        }
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commande = $form->getData();
            $entityManager->persist($commande);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_home');
        }

        return $this->render('commande/index.html.twig', [
            'titre' => ' - Prendre rendez-vous',
            'rdvForm' => $form->createView(),
            'commandeId' => $commande->getId(),
        ]);
    }
}
