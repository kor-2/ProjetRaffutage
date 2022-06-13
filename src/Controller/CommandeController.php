<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\TypeRepository;
use App\Repository\PrestationRepository;
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
    public function index(UserInterface $user, ManagerRegistry $doctrine ,Request $request, TypeRepository $typeRepo): Response
    {
        // test
        $test = $typeRepo->findOneBy(["id"=> 1]);
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


            // crée une facture
            $facture = new Facture();
            $jour = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $facture->setDateFacturation($jour);
            $facture->setPaye(false);

            // recupere le type de couteau

            $typeCouteau = $typeRepo->findOneBy(["id"=> 1]); ;

            // crée une commande
            $comm = new Commande();
            $comm = $form->getData();
            $comm->setUser($user);
            $comm->setFacture($facture);
            $comm->setType($typeCouteau);
            
            $entityManager->persist($facture);
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
