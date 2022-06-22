<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\Prestation;
use App\Entity\TypeCouteau;
use App\Form\CommandeAdminType;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use App\Repository\PrestationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    ////////////////////////////
    // Page du planning
    /////////////////////////////

    /**
     * @Route("/admin/planning", name="admin_planning")
     */
    public function planning(PrestationRepository $presRepo): Response
    {
        // prend les créneaux libre/
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

        return $this->render('admin/planning.html.twig', [
            'data' => $data,
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Page des commandes 
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/admin/commande", name="admin_commande")
     */
    public function listCommandee(CommandeRepository $cmdRepo): Response
    {

        $commandes = $cmdRepo->findBy([],['date_facturation' => 'DESC']);
        
        return $this->render('admin/commande.html.twig',[
           'commandes' => $commandes
        ]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Page modif des commandes 
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/admin/commande/modif/{id}", name="admin_modif_commande")
     */
    public function modifCommande(Request $request ,ManagerRegistry $doctrine, Commande $commande ): Response
    {
        $commande = new Commande();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CommandeAdminType::class, $commande);
        $form->handleRequest($request);

       
        
        if ($form->isSubmitted() && $form->isValid()) {
            $commande = $form->getData();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('admin_commande');
        }

        return $this->render('admin/modifCommande.html.twig', [
            'formCmd' => $form->createView(),
            'commande' => $commande->getId(),
        ]);
    }




    ////////////////////////////////////
    // configuration du dashboard
    ////////////////////////////////////
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Raffutage');
    }

    public function configureMenuItems(): iterable
    {
        return[
        MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
        MenuItem::linkToRoute('Home','fa fa-store','app_home'),
        MenuItem::section('Planning'),
        MenuItem::linkToCrud('Créneaux horaires ', 'fas fa-clock', Prestation::class),
        MenuItem::linkToRoute('Planning', 'fas fa-calendar', 'admin_planning'),
        MenuItem::section('Gestion'),
        MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
        MenuItem::linkToCrud('Type de couteau', 'fas fa-ruler-horizontal', TypeCouteau::class),
        MenuItem::section('Comptabilité'),
        MenuItem::linkToRoute('Factures clients','fas fa-file-invoice-dollar','admin_commande'),
        ];
    }
    /////////////////////////////////////////
    // ajout de balise dans la balise head
    //////////////////////////////////////////

    public function configureAssets(): Assets
    {
        $assets = Assets::new();

        return $assets
        // css pou le dashboard admin
        ->addCssFile('css/admin.css')
        // css pour CalendarBundle
        ->addHtmlContentToHead('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css" integrity="sha256-jLWPhwkAHq1rpueZOKALBno3eKP3m4IMB131kGhAlRQ=" crossorigin="anonymous">')
        ->addHtmlContentToHead('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">')
        // js pour CalendarBundle
        ->addHtmlContentToHead('<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.js" integrity="sha256-bFpArdcNM5XcSM+mBAUSDAt4YmEIeSAdUASB2rrSli4=" crossorigin="anonymous"></script>')
        ->addHtmlContentToHead('<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.js" integrity="sha256-Mu1bnaszjpLPWI+/bY7jB6JMtHj5nn9zIAsXMuaNxdk=" crossorigin="anonymous"></script>')
        ->addJsFile('js/scriptsAdmin.js')
        ->addJsFile('js/gsf.js')

        ;
    }
}
