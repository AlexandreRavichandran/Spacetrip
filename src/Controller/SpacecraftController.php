<?php

namespace App\Controller;

use App\Entity\Spacecraft;
use App\Repository\SpacecraftRepository;
use App\Repository\TripRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SpacecraftController extends AbstractController
{
    /**
     * Show all spacecrafts
     * @Route("/spacecrafts",name="app_spacecraft_index")
     * @return Response
     */
    public function index(SpacecraftRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $spacecrafts = $repo->findAll();
        $spacecrafts = $paginator->paginate($spacecrafts, $request->query->getInt('page', 1), 9);
        return $this->render('spacecraft/index.html.twig', [
            'spacecrafts' => $spacecrafts
        ]);
    }

    /**
     * Show one spacecraft
     * @Route("/spacecrafts/{id}",name="app_spacecraft_show")
     * @return Response
     */
    public function show(Spacecraft $spacecraft, TripRepository $repo): Response
    {
        $trips = $repo->findBy(['spacecraft' => $spacecraft->getId()]);
        return $this->render('spacecraft/show.html.twig', [
            'spacecraft' => $spacecraft,
            'trips' => $trips
        ]);
    }

    /**
     * Ajax response to show spacecraft characteristics when creating a trip
     * @Route("/trips/create/{id}",name="app_trip_create_ajax")
     */
    public function select(Spacecraft $spacecraft, Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = [
                'brand' => $spacecraft->getBrand(),
                'nationality' => $spacecraft->getNationality(),
                'price' => $spacecraft->getPrice(),
                'rating' => $spacecraft->getRating(),
                'possibleDestination' => $spacecraft->getPossibleDestination(),
                'numberOfSeat' => $spacecraft->getNumberOfSeat(),
                'speed' => $spacecraft->getSpeed()
            ];

            return new JsonResponse($jsonData);
        }
    }
}
