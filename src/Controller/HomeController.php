<?php

namespace App\Controller;

use App\Repository\FeedbackRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Show homepage
     * @Route("/", name="app_home")
     * @return Response
     */
    public function index(TripRepository $tripRepo, FeedbackRepository $feedbackRepo): Response
    {
        $trips = $tripRepo->findBy(['reserved' => false], ['departureAt' => 'DESC'], 2);
        $feedbacks = $feedbackRepo->findBy([], ['rating' => 'DESC'], 3);
        return $this->render('home/index.html.twig', [
            'trips' => $trips,
            'feedbacks' => $feedbacks
        ]);
    }
}
