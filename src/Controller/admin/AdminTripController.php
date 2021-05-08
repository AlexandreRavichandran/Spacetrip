<?php

namespace App\Controller\admin;


use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTripController extends AbstractController
{
    /**
     * Show all trips
     * @Route("/admin/trips",name="app_admin_trip_index")
     * @return Response
     */
    public function index(TripRepository $repo): Response
    {
        $trips = $repo->findBy(['reserved' => false]);
        return $this->render('admin/trip/index.html.twig', [
            'trips' => $trips
        ]);
    }
    /**
     * Create a new Trip
     * @Route("/admin/trips/create", name="app_admin_trip_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TripType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = new Trip();
            $trip = $form->getData();
            $em->persist($trip);
            $em->flush();
            $this->addFlash('success', 'La création du nouveau voyage a été effectué avec succès.');
            return $this->redirectToRoute('app_trip_show', ['name' => $trip->getName()]);
        }
        return $this->render('admin/trip/create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create'
        ]);
    }

    /**
     * Delete a trip
     * @Route("/admin/trips/{id}/delete", name="app_admin_trip_delete")
     * @return Response
     */
    public function delete(Trip $trip, EntityManagerInterface $em): Response
    {
        $em->remove($trip);
        $em->flush();
        $this->addFlash('success', 'La suppression du voyage ' . $trip->getName() . ' a été effectué avec succès.');
        return $this->redirectToRoute('app_admin_trip_index');
    }
    /**
     * Edit a trip
     * @Route("/admin/trips/{id}/edit", name="app_admin_trip_edit")
     * @return Response
     */
    public function edit(Trip $trip, Request $request, EntityManagerInterface $em): Response
    {
        $reserved = $trip->getReserved();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = $form->getData();
            $trip->setReserved($reserved);
            $em->flush();
            $this->addFlash('success', 'La modification du voyage a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_trip_index');
        }
        return $this->render('admin/trip/edit.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
            'trip' => $trip
        ]);
    }
}
