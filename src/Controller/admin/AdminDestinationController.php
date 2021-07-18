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
    public function edit(Destination $destination): Response
    {
        $form = $this->createForm(DestinationType::class, $destination);

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
        $checkIfTripExists = $tripRepo->findBy(['destination' => $destination->getId()]);
        if ($checkIfTripExists) {
            $tripNames = [];
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
