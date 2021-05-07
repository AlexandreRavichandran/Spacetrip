<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use App\Repository\SpacecraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{
    /**
     * Show all trips available
     * @Route("/trips", name="app_trip_index", methods={"GET"})
     * @return Response
     */
    public function index(TripRepository $repo): Response
    {
        $trips = $repo->findBy(['reserved' => false]);
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
        $trips = count($repo->findNumberOfTrips($user->getEmail())) + 1;
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = $form->getData();
            $trip->setReserved(true);
            $em->persist($trip);
            $em->flush();
            return $this->redirectToRoute('app_admin_trip_index');
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
    public function show(Trip $trip, SpacecraftRepository $repo): Response
    {
        $spacecraft = $repo->findOneById($trip->getSpacecraft());
        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
            'spacecraft' => $spacecraft
        ]);
    }
}
