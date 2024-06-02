<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SubscriptionType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SubscriptionController extends AbstractController
{
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    #[Route('/Subscription', name: 'app_subscription')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Create the form
        $form = $this->createForm(SubscriptionType::class, $user);

        // Handle the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the user with the new subscription
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('app_account');
        }

        // Get all subscriptions
        $subscriptions = $this->subscriptionRepository->findAll();

        return $this->render('subscription/index.html.twig', [
            'user' => $user,
            'subscriptions' => $subscriptions,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/Subscription/{id}', name: 'subscription_change')]
    public function change($id, EntityManagerInterface $entityManager): Response
    {
        // dd($id);
        $user = $this->getUser();
        $subscription = $this->subscriptionRepository->find($id);

        $user->setSubscription($subscription);
        $user->setSubscriptionEndAt((new \DateTimeImmutable())->modify('+1 month'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_account');
    }
}