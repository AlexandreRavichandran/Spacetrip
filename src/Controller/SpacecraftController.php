<?php

namespace App\Controller;

use App\Entity\Spacecraft;
use App\Repository\FeedbackRepository;
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
    public function show(Spacecraft $spacecraft, TripRepository $tripRepo, FeedbackRepository $feedbackrepo, Request $request): Response
    {

        $trips = $tripRepo->findBy(['spacecraft' => $spacecraft->getId()]);
        if ($spacecraft->getAvailable() === false) {
            $this->addFlash('warning', 'Ce vaisseau est actuellement en maintenance et non utilisable.');
        }
        $feedbacks = $feedbackrepo->findBy(['spacecraft' => $spacecraft->getId()]);

        return $this->render('spacecraft/show.html.twig', [

            'spacecraft' => $spacecraft,
            'trips' => $trips,
            'orderBy' => null,
            'order' => null,
            'feedbacks' => $feedbacks
        ]);
    }

    /**
     * Ajax response to show spacecraft characteristics when creating a trip (AJAX Request)
     * @Route("/spacecrafts/getAjaxData/{id}",name="app_spacecraft_ajax_request")
     */
    public function select(Spacecraft $spacecraft, Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $possibleDestinationsArray = [];
            foreach ($spacecraft->getPossibleDestination() as $possibleDestinations) {
                array_push($possibleDestinationsArray, $possibleDestinations->getName());
            }
            $possibleDestinationsArray = implode(', ', $possibleDestinationsArray);
            $jsonData = [
                'name' => $spacecraft->getName(),
                'brand' => $spacecraft->getBrand(),
                'nationality' => $spacecraft->getNationality(),
                'reservationPrice' => $spacecraft->getReservationPrice(),
                'pricePerDistance' => $spacecraft->getPricePerDistance(),
                'rating' => $spacecraft->getRating(),
                'possibleDestination' => $possibleDestinationsArray,
                'numberOfSeat' => $spacecraft->getNumberOfSeat(),
                'speed' => $spacecraft->getSpeed()
            ];

            return new JsonResponse($jsonData);
        }
    }
}
