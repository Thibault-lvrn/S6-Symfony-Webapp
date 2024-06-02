<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pdf;

class GeneratePdfController extends AbstractController
{
    private $client;
    private $filesystem;
    private $baseUrl;
    private $entityManager;

    public function __construct(HttpClientInterface $client, string $baseUrl, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->filesystem = new Filesystem();
        $this->baseUrl = $baseUrl;
        $this->entityManager = $entityManager;
    }

    #[Route('/generate/pdf', name: 'app_generate_pdf')]
    public function form(): Response
    {
        $user = $this->getUser();

        // dd($user);

        if (!$this->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour générer un PDF.');
        }

        return $this->render('generate_pdf/form.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/generate/pdf/submit', name: 'app_submit_pdf', methods: ['POST'])]
    public function submit(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $pdfLimit = $user->getSubscription()->getPdfLimit();

        if ($user->getPdfGenerated() >= $pdfLimit) {
            // throw new AccessDeniedException('Vous avez atteint la limite de PDFs autorisés.');
            return $this->render('error/exeded.html.twig');
        }

        $url = $request->request->get('url');
        $fileName = $request->request->get('fileName');

        $pdfPath = $this->generatePdf($url, $fileName);

        $user->incrementPdfGenerated();

        $entityManager->persist($user);
        $entityManager->flush();

        // Rediriger vers une page de succès avec le chemin du PDF
        return $this->redirectToRoute('app_success_pdf', ['pdfPath' => $pdfPath]);
    }

    private function generatePdf(string $url, string $fileName): string
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'http://web/S6-MicroService/public/index.php/generate/pdf', [
            'query' => [
                'url' => $url,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to generate PDF');
        }

        $content = $response->getContent();
        $publicPath = $this->getParameter('kernel.project_dir').'/public/pdf/'.$fileName.'.pdf';
        $pdfUrl = $this->baseUrl.'/public/pdf/'.$fileName.'.pdf';

        // Save the content to a file
        $this->filesystem->dumpFile($publicPath, $content);

        $pdf = new Pdf();
        $pdf->setTitle($fileName);
        $pdf->setCreatedAt(new \DateTimeImmutable());
        $pdf->setUserId($this->getUser());

        // Persist the Pdf entity
        $this->entityManager->persist($pdf);
        $this->entityManager->flush();

        return $pdfUrl;
    }

    #[Route('/generate/pdf/success', name: 'app_success_pdf')]
    public function success(Request $request): Response
    {
        $user = $this->getUser();
        $pdfPath = $request->query->get('pdfPath');

        return $this->render('generate_pdf/success.html.twig', [
            'pdfPath' => $pdfPath,
            'user' => $user
        ]);
    }
}
