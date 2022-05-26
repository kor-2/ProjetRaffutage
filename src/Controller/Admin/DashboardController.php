<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use App\Entity\User;
use App\Repository\PrestationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        // prend les créneaux libre
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
                'title' => 'Créneau pris',
                'id' => $pri->getId(),
                'start' => $pri->getDebut()->format('Y-m-d H:i:s'),
                'end' => $pri->getFin()->format('Y-m-d H:i:s'),
                'backgroundColor' => '#1a8cff',
            ];
        }
        $data = json_encode($rdvLibre);

        return $this->render('admin/planning.html.twig', [
            'data' => $data,
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
        MenuItem::section('Planning'),
        MenuItem::linkToCrud('Créneaux horaires ', 'fas fa-clock', Prestation::class),
        MenuItem::linkToRoute('Planning', 'fas fa-calendar', 'admin_planning'),
        MenuItem::section('Gestion entité'),
        MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
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
        // js pour CalendarBundle
        ->addHtmlContentToHead('<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js" integrity="sha256-XCdgoNaBjzkUaEJiauEq+85q/xi/2D4NcB3ZHwAapoM=" crossorigin="anonymous"></script>')
        ->addJsFile('js/scripts.js')

        ;
    }
}
