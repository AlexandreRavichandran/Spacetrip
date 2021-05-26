<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use App\Repository\SpacecraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{
    /**
     * Show all trips available
     * @Route("/trips", name="app_trip_index", methods={"GET"})
     * @return Response
     */
    public function index(TripRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $trips = $paginator->paginate($repo->findAvailableTrips(), $request->query->getInt('page', 1), 12);
        return $this->render('trip/index.html.twig', [
            'trips' => $trips
        ]);
    }

    /**
     * Create a reserved trip for customers
     * @Route("/trips/create",name="app_trip_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, TripRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, "Veuillez vous connecter.");
        $trip = new Trip;
        $user = $this->getUser();
        $trips = count($repo->findUserTrips($user->getEmail())) + 1;
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = $form->getData();
            $trip->setName('VR - ' . $user->getEmail() . ' - ' . $trips);
            $trip->setDescription('Voyage reservé par ' . $user->getEmail());
            $trip->setAvailableSeatNumber(0);
            $trip->setReserved(true);
            $trip->setPrice();
            $em->persist($trip);
            $em->flush();
            return $this->redirectToRoute('app_user_profile');
        }
        return $this->render('trip/create.html.twig', [
            'form' => $form->createView(),
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

    /**
     * show a recap of the trip and proceed to payment
     * @Route("/trips/{id}/payment", name="app_trip_payment")
     */
    public function reserveTrip(Trip $trip): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Veuillez vous connecter');
        $user = $this->getUser();
        return $this->render('trip/reserve.html.twig', [
            'trip' => $trip,
            'user' => $user
        ]);
    }

    /**
     * Page called when a trip is successfully reserved
     * @Route("/trips/{id}/succeed",name="app_trip_succeed")
     * @return Response
     */
    public function onReservationSuccess(Trip $trip, EntityManagerInterface $em, Request $request, TCPDFController $tcpdf): Response
    {
        $token = $request->request->get('token');
        if ($this->isCsrfTokenValid('purchasing', $token)) {
            //$trip->setAvailableSeatNumber($trip->getAvailableSeatNumber() - 1);
            //$em->flush();

            return $this->render('trip/recap.html.twig', [
                'trip' => $trip,
            ]);
        }
        $this->addFlash('danger', 'Une erreur s\'est produite. Le paiement a echoué. Veuillez recommencer.');
        return $this->render('trip/reserve.html.twig', [
            'trip' => $trip
        ]);
    }
}
