<?php

namespace App\Controller\admin;

use App\Entity\Trip;
use App\Entity\Spacecraft;
use App\Form\SpacecraftType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSpacecraftController extends AbstractController
{

    /**
     * Create a new Spacecraft
     * @Route("/admin/spacecrafts/create", name="app_admin_spacecraft_create")
     * @return Response
     */
    public function create(request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SpacecraftType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $spacecraft = new Spacecraft;
            $spacecraft = $form->getData();
            $spacecraft->setRating(3);
            $em->persist($spacecraft);
            $em->flush();
            $this->addFlash('success', 'L\'ajout du nouveau vaisseau a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_spacecraft_home');
        }
        return $this->render('admin/spacecraft/create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create'
        ]);
    }
    /**
     * Edit a spacecraft
     * @Route("/admin/spacecrafts/{id}/edit", name="app_admin_spacecraft_edit")
     * @return Response
     */
    public function edit(Spacecraft $spacecraft, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SpacecraftType::class, $spacecraft);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $spacecraft = $form->getData();
            $em->flush();
            $this->addFlash('success', 'La modification du vaisseau a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_spacecraft_home');
        }
        return $this->render('admin/spacecraft/edit.html.twig', [
            'form' => $form->createView(),
            'spacecraft' => $spacecraft,
            'action' => 'edit'
        ]);
    }

    /**
     * Delete a spacecraft
     * @Route("/admin/spacecrafts/{id}/delete", name="app_admin_spacecraft_delete") 
     * @return Reponse
     */
    public function delete(Spacecraft $spacecraft, EntityManagerInterface $em, TripRepository $repo): Response
    {
        $trips = $repo->findBy(['spacecraft' => $spacecraft->getId()]);
        if ($trips) {
            $tripNames = [];
            foreach ($trips as $trip) {
                array_push($tripNames, $trip->getName());
            }
            $tripNames = implode(', ', $tripNames);
            $this->addFlash('warning', 'Echec de la suppression : Ce vaisseau est associé aux voyages suivants :' . $tripNames . '. Veuillez modifier ces voyages avant la suppression de ce vaisseau.');
            $this->redirectToRoute('app_admin_spacecraft_home');
        } elseif (!$trips) {
            $em->remove($spacecraft);
            $em->flush();
            $this->addFlash('success', 'La suppression du vaisseau a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_spacecraft_home');
        }

        return $this->redirectToRoute('app_admin_spacecraft_home');
    }
}
