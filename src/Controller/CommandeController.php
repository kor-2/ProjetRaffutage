<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
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
    public function index(PrestationRepository $presRepo, ManagerRegistry $doctrine ,Request $request, Commande $commande = null , CommandeRepository $cmdRepo): Response
    {
        ///////////////////////////////////////////////////////
        // table pour ful calendar
        //////////////////////////////////////////////////////

        // prend les créneaux libre
        $plans = $presRepo->getCreneau(true, true, false);
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
        $pris = $presRepo->getCreneau(true, false, false);
        foreach ($pris as $pri) {
            $rdvLibre[] = [
                'title' => 'Créneau réservé',
                'id' => $pri->getId(),
                'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                'backgroundColor' => '#808080',
                'code' => 'INDISPO'
            ];
        }
        // prend les créneaux libre mais dans le passé donc indisponible
        $pris = $presRepo->getCreneau(true, true, true);
        foreach ($pris as $pri) {
            $rdvLibre[] = [
                'title' => 'Créneau indisponible',
                'id' => $pri->getId(),
                'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                'backgroundColor' => '#808080',
                'code' => 'INDISPO'
                
            ];
        }
         // prend les créneaux pris et dans le passé
        
         $pris = $presRepo->getCreneau(true, false, true);
         foreach ($pris as $pri) {
             $rdvLibre[] = [
                 'title' => 'Créneau réservé',
                 'id' => $pri->getId(),
                 'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                 'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                 'backgroundColor' => '#808080',
                 'code' => 'INDISPO'
             ];
         }
        $data = json_encode($rdvLibre);
        /////////////////////////////////////////////////

        ////////////////////////////////////////////////////////
        // envoi du formulaire
        /////////////////////////////////////////////////////////
        if (!$commande) {

            $commande = new Commande();
        }
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mtn = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $user =$this->getUser();
            $idPresta = $request->request->get('presta'); // recupere l'id de la prestation 
            $prestaObj = $presRepo->findOneBy(['id'=> $idPresta]);// cherche la prestation avec l'id trouver si dessus        

            // genere la facture
            $facture = new Facture();
            $facture->setClient($user->nomEntier());
            $facture->setPaye(false);

            // création du numéro de commande
            $prt1 = mb_substr($user->nomEntier(),0,1).strtoupper(mb_substr($user->nomEntier(),-1,1));
            $prt2 = $mtn->format('Y');
            $prt3 = count($cmdRepo->findAll()) + 1;

            $numCmd = $prt1.$prt2.$prt3;
            $facture->setNumero($numCmd);
            
            $commande = $form->getData();

            $typages = $commande->getTypages();


            $details = []; 
            foreach ($typages as $type ) {
                $details[] = [
                    'type' => $type->getTypeCouteau()->getNom(),
                    'tarif' => $type->getTypeCouteau()->getTarif(),
                    'nbCouteau' => $type->getNbCouteau(),
                    'remise' => 0
                ];
            }

            $commande->setDetails($details);
            $commande->setPrestation($prestaObj);
            $commande->setDateFacturation($prestaObj->getDebut());
            $commande->setUser($user);
            $commande->setFacture($facture);

            $entityManager->persist($facture);
            $entityManager->persist($commande);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_mon_compte');
        }
        /////////////////////////////////////////////////////

        return $this->render('commande/index.html.twig', [
            'titre' => ' - Prendre rendez-vous',
            'rdvForm' => $form->createView(),
            'commandeId' => $commande->getId(),
            'data' => $data,
        ]);
    }
}
