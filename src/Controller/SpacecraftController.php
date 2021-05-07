<?php

namespace App\Controller;

use App\Repository\SpacecraftRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpacecraftController extends AbstractController
{
    /**
     * Show all spacecrafts
     * @Route("/spacecrafts",name="app_spacecraft_index")
     * @return Response
     */
    public function index(SpacecraftRepository $repo): Response
    {
        $spacecrafts = $repo->findAll();
        return $this->render('spacecraft/index.html.twig', [
            'spacecrafts' => $spacecrafts
        ]);
    }
}
