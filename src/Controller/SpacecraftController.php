<?php

namespace App\Controller;

use App\Entity\Spacecraft;
use App\Repository\SpacecraftRepository;
use App\Repository\TripRepository;
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
        $ratings = [];
        foreach ($spacecrafts as $spacecraft) {
            $rating = [];
            foreach ($spacecraft->getFeedback() as $rates) {
                array_push($rating, $rates->getRating());
            }
            $average =  round(array_sum($rating) / count($rating));
            array_push($ratings, $average);
        }
        return $this->render('spacecraft/index.html.twig', [
            'spacecrafts' => $spacecrafts,
            'ratings' => $ratings
        ]);
    }

    /**
     * Show one spacecraft
     * @Route("/spacecrafts/{id}",name="app_spacecraft_show")
     * @return Response
     */
    public function show(Spacecraft $spacecraft, TripRepository $repo): Response
    {
        $trips = $repo->findBy(['spacecraft' => $spacecraft->getId()]);
        $rating = [];
        foreach ($spacecraft->getFeedback() as $rates) {
            array_push($rating, $rates->getRating());
        }
        $average = round(array_sum($rating) / count($rating));
        return $this->render('spacecraft/show.html.twig', [
            'spacecraft' => $spacecraft,
            'average' => $average,
            'trips' => $trips
        ]);
    }
}
