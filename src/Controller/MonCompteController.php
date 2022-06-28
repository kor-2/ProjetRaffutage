<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Repository\TypageRepository;
use App\Repository\CommandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonCompteController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="app_mon_compte")
     */
    public function index(): Response
    {
        $mtn = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        

        return $this->render('mon_compte/index.html.twig', [
            'titre' => ' - Mon compte',
            'maintenant' => $mtn,
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
     * @Route("/mon-compte/supp_commande/{id}", name="app_supp_cmd")
     */
    public function suppCommande(ManagerRegistry $doctrine , Commande $commande): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($commande);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_mon_compte');
    }

     /**
     * @Route("/mon-compte/supp_compte", name="app_supp_compte")
     */
    public function suppCompte(ManagerRegistry $doctrine, CommandeRepository $cmdRepo): Response
    {
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();
        
        // suppression du user dans les commandes passé
        $cmdPasse = $cmdRepo->findByUser($user->getId());
        foreach ($cmdPasse as $cmdPass) {
            $user->removeCommande($cmdPass);
        }

        // suppression des commande future
        $cmdFuture = $cmdRepo->findByUser($user->getId(), false);
        foreach ($cmdFuture as $cmdFut) {
            $entityManager->remove($cmdFut);
        }
        // supprime la session utilisateur
        $session = new Session();
        $session->invalidate();

        $entityManager->remove($user);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_home');
    }


}
