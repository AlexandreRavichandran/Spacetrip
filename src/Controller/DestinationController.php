<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Repository\TripRepository;
use App\Repository\DestinationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * Ajax response to show spacecraft characteristics when creating a trip
     * @Route("/destinations/show/{id}",name="app_destination_show_ajax")
     */
    public function select(Destination $destination, Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = [
                'distance' => $destination->getDistance()
            ];

            return new JsonResponse($jsonData);
        }
    }
}
