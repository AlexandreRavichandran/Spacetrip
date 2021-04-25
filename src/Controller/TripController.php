<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    /**
     * Show all trips available
     * @Route("/trips", name="app_trip_index", methods={"GET"})
     * @return Response
     */
    public function index(TripRepository $repo): Response
    {
        $trips = $repo->findAll();

        return $this->render('trip/index.html.twig', [
            'trips' => $trips
        ]);
    }

    /**
     * Show one trip by his name
     * @Route("/trips/{name}", name="app_trip_show", methods={"GET"})
     * @return Response
     */
    public function show(Trip $trip): Response
    {
        return $this->render('trip/show.html.twig', [
            'trip' => $trip
        ]);
    }
}
