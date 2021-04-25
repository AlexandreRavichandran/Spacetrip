<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    /**
     * Show all trips available
     * @Route("/trips", name="app_trips_show", methods={"GET"})
     * @return Response
     */
    public function index(TripRepository $repo): Response
    {
        $trips = $repo->findAll();

        return $this->render('trip/index.html.twig', [
            'trips' => $trips
        ]);
    }
}
