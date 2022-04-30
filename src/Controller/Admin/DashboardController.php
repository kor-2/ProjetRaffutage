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

    /**
     * @Route("/admin/planning", name="admin_planning")
     */
    public function planning(PrestationRepository $presRepo): Response
    {
        $plans = $presRepo->findAll();
        $rdvLibre = [];
        foreach ($plans as $plan) {
            $rdvLibre[] = [
                'title' => 'Créneau libre',
                'id' => $plan->getId(),
                'start' => $plan->getDebut()->format('Y-m-d H:i:s'),
                'end' => $plan->getFin()->format('Y-m-d H:i:s'),
                'backgroundColor' => '#80ffaa',
            ];
        }
        $data = json_encode($rdvLibre);
        dump($data);
        dump($rdvLibre);

        return $this->render('admin/planning.html.twig', [
            'data' => $data,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ProjetRaffutage');
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

    public function configureAssets(): Assets
    {
        $assets = Assets::new();

        return $assets
        // css pour CalendarBundle
        ->addHtmlContentToHead('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css" integrity="sha256-jLWPhwkAHq1rpueZOKALBno3eKP3m4IMB131kGhAlRQ=" crossorigin="anonymous">')
        // js pour CalendarBundle
        ->addHtmlContentToHead('<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js" integrity="sha256-XCdgoNaBjzkUaEJiauEq+85q/xi/2D4NcB3ZHwAapoM=" crossorigin="anonymous"></script>')
        ->addJsFile('js/scripts.js')
        ->addCssFile('css/admin.css')
        ;
    }
}
