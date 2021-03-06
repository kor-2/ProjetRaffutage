<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PDFController extends AbstractController
{
    /**
     * @Route("/pdf/vue/{id}", name="app_pdf")
     */
    public function index(Commande $commandes):Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);

        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
    
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/showCommande.html.twig', [
            'title' => "Facture raffutage",
            'commande' => $commandes
        ]);

        $num = $commandes->getFacture()->getNumero();
        
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Facture_Raffutage_".$num.".pdf", [
            "Attachment" => false
        ]);
        exit(0);
    }
    /**
     * @Route("/pdf/telechargement/{id}", name="app_pdf_telechargement")
     */
    public function telechargement(Commande $commandes):Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/showCommande.html.twig', [
            'title' => "Facture raffutage",
            'commande' => $commandes
        ]);
        $num = $commandes->getFacture()->getNumero();
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Facture_Raffutage_".$num.".pdf", [
            "Attachment" => true
        ]);

        exit(0);
    }
}
