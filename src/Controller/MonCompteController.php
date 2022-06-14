<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\FactureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $test = $facture;
        dump($test);

        return $this->render('mon_compte/index.html.twig', [
            'titre' => ' - Mon compte',
            'maintenant' => $mtn,
        ]);
    }

    /**
     * @Route("/mon-compte/modif", name="app_modif_compte")
     */
    public function modif(): Response
    {
        return $this->render('mon_compte/modif.html.twig', [
            'titre' => ' - Modification mon compte',
        ]);
    }

    /**
     * @Route("/mon-compte/supp-commande/{id}", name="app_supp_commande")
     */
    public function suppCommande(ManagerRegistry $doctrine, Commande $cmd): Response
    {

        // pas bon
        $entityManager = $doctrine->getManager();
        $entityManager->remove($cmd);
        $entityManager->flush();

        return $this->redirectToRoute('app_mon_compte');
    }

}
