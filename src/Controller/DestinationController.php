<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use App\Repository\TripRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DestinationController extends AbstractController
{
    /**
     * Show all destinations 
     * @Route("/destinations", name="app_destination_index")
     * @return Response
     */
    public function index(DestinationRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $destinations = $paginator->paginate($repo->findAll(), $request->query->getInt('page', 1), 6);
        return $this->render('destination/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }
    /**
     * Show a specific destination
     * @Route("/destinations/{name}",name="app_destination_show")
     * @return Response
     */
    public function show(Destination $destination, TripRepository $repo): Response
    {
        $trips = $repo->AvailableTripByDestination($destination->getId());
        return $this->render('destination/show.html.twig', [
            'destination' => $destination,
            'trips' => $trips
        ]);
    }
}
