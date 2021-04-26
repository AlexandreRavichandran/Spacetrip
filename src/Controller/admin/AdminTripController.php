<?php

namespace App\Controller\admin;


use App\Entity\Trip;
use App\Form\TripType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTripController extends AbstractController
{
    /**
     * Create a new Trip
     * @Route("/admin/trips/create", name="app_admin_trip_create")
     * @return Response
     */
    public function createTrip(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TripType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = new Trip();
            $trip = $form->getData();
            $em->persist($trip);
            $em->flush();
            return $this->redirectToRoute('app_trip_show', ['name' => $trip->getName()]);
        }
        return $this->render('admin/trip/create.html.twig', ['form' => $form->createView()]);
    }
}
