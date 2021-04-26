<?php

namespace App\Controller\admin;

use App\Entity\Spacecraft;
use App\Form\SpacecraftType;
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
    public function createSpacecraft(request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SpacecraftType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $spacecraft = new Spacecraft;
            $spacecraft = $form->getData();
            $spacecraft->setRating(3);
            $em->persist($spacecraft);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('admin/spacecraft/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
