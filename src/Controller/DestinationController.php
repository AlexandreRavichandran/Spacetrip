<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Repository\TripRepository;
use App\Repository\DestinationRepository;
use App\Service\CallDestinationApi;
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
    public function show(Destination $destination, TripRepository $repo, CallDestinationApi $callDestinationApi): Response
    {
        //API request to have more informations about the selected destination 
        $informations = $callDestinationApi->getDestinationData($destination->getName());

        $trips = $repo->AvailableTripByDestination($destination->getId(), 3);

        return $this->render('destination/show.html.twig', [
            'destination' => $destination,
            'trips' => $trips,
            'informations' => $informations
        ]);
    }

    /**
     * Ajax response to show spacecraft characteristics when creating a trip (AJAX Request)
     * @Route("/destinations/getAjaxData/{id}",name="app_destination_ajax_request")
     */
    public function select(Destination $destination, Request $request, CallDestinationApi $response): Response
    {

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $destinationData = $response->getDestinationData($destination->getName());
            $jsonData = [
                'name' => $destination->getName(),
                'distance' => $destination->getDistance(),
                'gravity' => $destinationData['gravity'],
                'description' => $destination->getDescription()

            ];

            return new JsonResponse($jsonData);
        }
    }
}
