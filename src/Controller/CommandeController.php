<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\PrestationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    /**
     * @Route("/prise-de-rendez-vous", name="app_commande")
     */
    public function index(ManagerRegistry $doctrine ,Request $request, PrestationRepository $prp): Response
    {
        // test
        $test = $prp->getCreneau(false,false, false);
        dump($test);

        ////////////////////////////////////
        // ajoutcommande
        // ne pas oublier de generer la facture et de bind un type de couteau
        // https://symfony.com/doc/current/doctrine/associations.html
        ////////////////////////////////////
        $commande = new Commande();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comm = $form->getData();
            $entityManager->persist($comm);
            $entityManager->flush();
            
    
            return $this->redirectToRoute('app_home');
        }

        return $this->render('commande/index.html.twig', [
            'titre' => ' - Prendre rendez-vous',
            'rdvForm' => $form->createView(),
        ]);
    }
}
