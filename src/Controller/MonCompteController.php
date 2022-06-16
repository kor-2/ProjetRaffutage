<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Repository\FactureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonCompteController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="app_mon_compte")
     */
    public function index(FactureRepository $facRepo): Response
    {
        $mtn = new \DateTime('now', new \DateTimeZone('Europe/Paris'));

        $user =$this->getUser();
        $facture = $facRepo->getFactureByUserId($user);

        // test 
        $test = $facture;
        dump($test);

        return $this->render('mon_compte/index.html.twig', [
            'titre' => ' - Mon compte',
            'maintenant' => $mtn,
            'factures' => $facture
        ]);
    }

    /**
     * @Route("/mon-compte/modif", name="app_modif_compte")
     */
    public function modif(ManagerRegistry $doctrine ,Request $request): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            
    
            return $this->redirectToRoute('app_mon_compte');
        }
        return $this->render('mon_compte/modif.html.twig', [
            'titre' => ' - Modification mon compte',
            'formUser' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/mon-compte/supp-commande/{id}", name="app_supp_facture")
     */
    public function suppCommande(ManagerRegistry $doctrine, Facture $facture): Response
    {

        // pas bon
        $entityManager = $doctrine->getManager();
        $entityManager->remove($facture);
        $entityManager->flush();

        return $this->redirectToRoute('app_mon_compte');
    }

}
