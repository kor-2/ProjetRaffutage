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
use App\Repository\PrestationRepository;

class CommandeController extends AbstractController
{
    /**
     * @Route("/prise-de-rendez-vous", name="app_commande")
     */
    public function index(PrestationRepository $presRepo, ManagerRegistry $doctrine ,Request $request, Commande $commande = null): Response
    {
        // test
        $test ="oui";
        dump($test);
        ///////////////////////////////////////////////////////
        // prend les créneaux libre/
        $plans = $presRepo->getCreneau(false, false, true);
        $rdvLibre = [];
        foreach ($plans as $plan) {
            $rdvLibre[] = [
                'title' => 'Créneau libre',
                'id' => $plan->getId(),
                'start' => $plan->getDebut()->format('Y-m-d H:i:s'),
                'end' => $plan->getFin()->format('Y-m-d H:i:s'),
                'backgroundColor' => '#009933',
            ];
        }
        // prend les créneaux pris
        $pris = $presRepo->getCreneau(false, false, false);
        foreach ($pris as $pri) {
            $rdvLibre[] = [
                'title' => 'Créneau indisponible',
                'id' => $pri->getId(),
                'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                'backgroundColor' => '#808080',
            ];
        }
        $data = json_encode($rdvLibre);
        /////////////////////////////////////////////////

        if (!$commande) {

            $commande = new Commande();
        }
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mtn = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $user =$this->getUser();

            $commande = $form->getData();
            $commande->setDateFacturation($mtn);
            $commande->setPaye(false);
            $commande->setUser($user);


            $entityManager->persist($commande);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_home');
        }

        return $this->render('commande/index.html.twig', [
            'titre' => ' - Prendre rendez-vous',
            'rdvForm' => $form->createView(),
            'commandeId' => $commande->getId(),
            'data' => $data,
        ]);
    }
}
