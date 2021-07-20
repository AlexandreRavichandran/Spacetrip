<?php

namespace App\Controller\admin;

use App\Entity\Destination;
use App\Form\DestinationType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DestinationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDestinationController extends AbstractController
{
    private $repo;
    private $em;
    private $paginator;

    public function __construct(DestinationRepository $repo, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->repo = $repo;
        $this->em = $em;
        $this->paginator = $paginator;
    }
    /**
     * Show all destinations
     * @Route("/admin/destinations", name="app_admin_destination_index")
     */
    public function index(Request $request): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $destinations = $this->paginator->paginate($this->repo->findAll(), $request->query->getInt('page', 1), 11);

        return $this->render('admin/destination/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }

    /**
     * Create a new destination
     * @Route("/admin/destinations/create",name="app_admin_destination_create")
     * @return Response
     */
    public function create(Request $request): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $form = $this->createForm(DestinationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $destination = new Destination;
            $destination = $form->getData();
            $this->em->persist($destination);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_destination_index');
        }
        return $this->render('admin/destination/create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create'
        ]);
    }

    /**
     * Edit a selected destination
     * @Route("/admin/destinations/{id}/edit",name="app_admin_destination_edit")
     * @return Response
     */
    public function edit(Destination $destination, Request $request): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $destination = $form->getData();
            $this->em->flush();
            $this->addFlash('success', 'La modification de la destination a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_destination_index');
        }
        return $this->render('admin/destination/edit.html.twig', [
            'form' => $form->createView(),
            'destination' => $destination,
            'action' => 'edit'
        ]);
    }

    /**
     * Delete a selected destination
     * @Route("/admin/destinations/{id}/delete",name="app_admin_destination_delete")
     * @return Response
     */
    public function delete(Destination $destination, TripRepository $tripRepo): Response
    {
        //Check if destination is related to a existant trip
        $checkIfTripExists = $tripRepo->findBy(['destination' => $destination->getId()]);

        if ($checkIfTripExists) {
            $tripNames = [];
            //Set all existant trips into an array to convert to string and add to flash message
            foreach ($checkIfTripExists as $trip) {
                array_push($tripNames, $trip->getName());
            }
            $tripNames = implode(', ', $tripNames);
            $this->addFlash('warning', 'Cette destination est liée aux voyages ' . $tripNames . ' . Veuillez modifier ces voyages avant de supprimer cette destination.');
        } else {
            $this->em->remove($destination);
            $this->em->flush();
            $this->addFlash('success', 'La destination ' . $destination->getName() . ' a été supprimé.');
        }

        return $this->redirectToRoute('app_admin_destination_index');
    }
}
