<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\GotenbergClient;

class GeneratePdfController extends AbstractController
{
    #[Route('/generate/pdf', name: 'app_generate_pdf')]
    public function form(): Response
    {
        return $this->render('generate_pdf/form.html.twig');
    }

    #[Route('/generate/pdf/view', name: 'app_view_pdf')]
    public function view(Request $request, GotenbergClient $gotenbergClient): Response
    {
        $url = $request->query->get('url');
        $pdfContent = $gotenbergClient->convertUrlToPdf($url);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
    }

    #[Route('/generate/pdf/submit', name: 'app_submit_pdf', methods: ['POST'])]
    public function submit(Request $request): RedirectResponse
    {
        $url = $request->request->get('url');
        return $this->redirect('http://localhost:8088/S6-MicroService/public/index.php/generate/pdf?url=' . urlencode($url));
    }
}