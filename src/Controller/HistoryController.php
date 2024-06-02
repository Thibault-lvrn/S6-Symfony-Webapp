<?php

// src/Controller/HistoryController.php

namespace App\Controller;

use App\Repository\PdfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HistoryController extends AbstractController
{
    #[Route('/history', name: 'app_history')]
    public function index(PdfRepository $pdfRepository, PaginatorInterface $paginator, Request $request): Response
{
    $user = $this->getUser();
    $pdfs = $pdfRepository->findBy(['user' => $user]);
    $pagination = $paginator->paginate(
        $pdfs,
        $request->query->getInt('page', 1),
        10,
        [
            'defaultSortFieldName' => 'pdf.createdAt',
            'defaultSortDirection' => 'desc'
        ]
    );

    return $this->render('history/index.html.twig', [
        'user' => $user,
        'pdfs' => $pdfs,
        'pagination' => $pagination,
    ]);
}
}