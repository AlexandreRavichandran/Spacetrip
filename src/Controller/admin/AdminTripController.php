<?php

namespace App\Controller\admin;


use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTripController extends AbstractController
{
    private $tripRepository;
    private $paginator;
    private $em;

    /**
     * Function construrct for AdminTripController
     */
    public function __construct(Triprepository $tripRepository, PaginatorInterface $paginatorInterface, EntityManagerInterface $em)
    {
        $this->tripRepository = $tripRepository;
        $this->paginator = $paginatorInterface;
        $this->em = $em;
    }
    /**
     * Show all trips
     * @Route("/admin/trips",name="app_admin_trip_index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $trips = $this->paginator->paginate($this->tripRepository->findBy(['reserved' => false]), $request->query->getInt('page', 1), 11);
        return $this->render('admin/trip/index.html.twig', [
            'trips' => $trips,
            'order' => null,
            'orderBy' => null
        ]);
    }
    /**
     * Create a new Trip
     * @Route("/admin/trips/create", name="app_admin_trip_create")
     * @return Response
     */
    public function create(Request $request): Response
    {
        $trip = new Trip;
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = new Trip();
            $trip = $form->getData();
            $trip->setPrice()
                ->setStatus(2);
            $this->em->persist($trip);
            $this->em->flush();
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
    public function delete(Trip $trip): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $this->em->remove($trip);
        $this->em->flush();
        $this->addFlash('success', 'La suppression du voyage ' . $trip->getName() . ' a été effectué avec succès.');
        return $this->redirectToRoute('app_admin_trip_index');
    }
    /**
     * Edit a trip
     * @Route("/admin/trips/{id}/edit", name="app_admin_trip_edit")
     * @return Response
     */
    public function edit(Trip $trip, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $reserved = $trip->getReserved();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = $form->getData();
            $trip->setReserved($reserved);
            $this->em->flush();
            $this->addFlash('success', 'La modification du voyage a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_trip_index');
        }
        return $this->render('admin/trip/edit.html.twig', [
            'action' => 'edit',
            'form' => $form->createView(),
            'trip' => $trip
        ]);
    }

    /**
     * Sort all showed trips
     * @Route("/admin/trips/{orderBy}/{order}",name="app_admin_trip_sort")
     * @return Response
     */
    public function sort(Request $request, string $orderBy, string $order): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $trips = $this->paginator->paginate($this->tripRepository->orderTrips($orderBy, $order), $request->query->getInt('page', 1), 11);
        return $this->render('admin/trip/index.html.twig', [
            'trips' => $trips,
            'order' => $order,
            'orderBy' => $orderBy
        ]);
    }
    /* CRUD FOR RESERVED TRIPS */

    /**
     * Show all reserved trips
     * @Route("/admin/reserved_trips", name="app_admin_reserved_trips_index")
     * @return Response
     */
    public function resIndex(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $trips = $this->paginator->paginate($this->tripRepository->findBy(['reserved' => true]), $request->query->getInt('page', 1), 11);
        return $this->render('admin/trip/index.html.twig', [
            'trips' => $trips,
            'order' => null,
            'orderBy' => null
        ]);
    }
}
