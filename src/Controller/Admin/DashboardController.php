<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\Prestation;
use App\Form\CommandeType;
use App\Entity\TypeCouteau;
use App\Form\CommandeAdminType;
use App\Repository\UserRepository;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository(User::class)->findAll();
        $commandes = $em->getRepository(Commande::class)->findAll();


        return $this->render('admin/dashboard.html.twig',[
            'users' => $users,
            'commandes' => $commandes
        ]);
    }

    ////////////////////////////
    // Page du planning
    /////////////////////////////

    /**
     * @Route("/admin/planning", name="admin_planning")
     */
    public function planning(PrestationRepository $presRepo): Response
    {
        // prend les prestations libres
        $plans = $presRepo->getCreneau(true, true, false ,false);
        $rdvLibre = [];
        foreach ($plans as $plan) {
            $rdvLibre[] = [
                'title' => 'Libre',
                'id' => $plan->getId(),
                'start' => $plan->getDebut()->format('Y-m-d H:i:s'),
                'end' => $plan->getFin()->format('Y-m-d H:i:s'),
                'color' => '#009933',
            ];
        }
        // prend les prestations réservées
        $pris = $presRepo->getCreneau(true, false, false);
        foreach ($pris as $pri) {
            // 
            $commande = $pri->getCommandes();
            $client ='';
            $email ='';
            $details = [];
            foreach ($commande as $cmd) {
                $client .= $cmd->getUser()->nomEntier();
                $email .= $cmd->getUser()->getEmail();
                $tel = $cmd->getUser()->getTelephone();
                $details = $cmd->getDetails();
            }
            $rdvLibre[] = [
                'title' => 'Réservé',
                'id' => $pri->getId(),
                'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                'color' => '#008ae6',
                'client'=> $client,
                'email'=> $email,
                'tel'=> $tel,
                'details' => $details
            ];
        }
        // prestations libres mais dans le passé donc indisponible
        $pris = $presRepo->getCreneau(true, true, true, false);
        foreach ($pris as $pri) {
            $rdvLibre[] = [
                'title' => 'Indispo',
                'id' => $pri->getId(),
                'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                'color' => '#808080',
            ];
        }
        // prestations réservées et dans le passé
        
        $pris = $presRepo->getCreneau(true, false, true);
        foreach ($pris as $pri) {
            $commande = $pri->getCommandes();
            $client ='';
            $email ='';
            $details = [];
            foreach ($commande as $cmd) {
                $client = $cmd->getUser()->nomEntier();
                $email = $cmd->getUser()->getEmail();
                $tel = $cmd->getUser()->getTelephone();
                $details = $cmd->getDetails();
            }
            $rdvLibre[] = [
                'title' => 'Réservé',
                'id' => $pri->getId(),
                'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                'color' => '#008ae6',
                'client'=> $client,
                'email'=> $email,
                'tel'=> $tel,
                'details' => $details
            ];
        }

        // encode en json le tableau de toute les prestations
        $data = json_encode($rdvLibre);
        // envoi dans la vue
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
           'commandes' => $commandes,
        ]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Page modif des commandes 
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/admin/commande/modification/{id}", name="admin_modif_commande")
     * 
     */
    public function modifCommande(Request $request ,ManagerRegistry $doctrine, Commande $commande ): Response
    {

        return $this->render('admin/modifCommande.html.twig', [
            'commande' => $commande,
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Page modif des commandes 
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/admin/commande/modif/{id_cmd}/{id_user}/{id_presta}/{id_fact}", name="admin_modif_cmd_proc")
     * 
     * @ParamConverter("commande", options={"mapping" = {"id_cmd" : "id"}})
     * @ParamConverter("user", options={"mapping" = {"id_user" : "id"}})
     * @ParamConverter("prestation", options={"mapping" = {"id_presta" : "id"}})
     * @ParamConverter("facture", options={"mapping" = {"id_fact" : "id"}})
     */
    public function modifCmdProc(Request $request ,ManagerRegistry $doctrine, Commande $commande , User $user, Prestation $prestation, Facture $facture): Response
    {
        $entityManager = $doctrine->getManager();

        $dateFacturation = $prestation->getDebut();
        
        $newDetails = [];
        $oldDetails = $commande->getDetails();
        $i = 0;
        
        

        foreach ($oldDetails as $old ) {
            ++$i;
            $newdetails[] = [
                'type' => $request->request->get('type'.$i),
                'tarif' => $request->request->get('tarif'.$i),
                'remise' => $request->request->get('remise'.$i),
                'nbCouteau' => $request->request->get('nbCouteau'.$i),
            ];

        }

        $commande->setDetails($newdetails);
        $entityManager->persist($commande);
        $entityManager->flush();


        return $this->redirectToRoute('admin_commande');
        
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // Page affichae des commandes 
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/admin/commande/show/{id}", name="admin_show_commande")
     */
    public function showCommande(Request $request ,ManagerRegistry $doctrine, Commande $commande ): Response
    {

        return $this->render('admin/showCommande.html.twig', [
            'commande' => $commande,

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
        MenuItem::linkToRoute('Commandes clients','fas fa-file','admin_commande'),
        MenuItem::linkToCrud('Facture', 'fas fa-file-invoice-dollar', Facture::class),
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
