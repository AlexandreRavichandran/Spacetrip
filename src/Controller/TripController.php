<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use App\Repository\SpacecraftRepository;
use App\Service\CallWeatherApi;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class TripController extends AbstractController
{
    /**
     * Show all trips available
     * @Route("/trips", name="app_trip_index", methods={"GET"})
     * @return Response
     */
    public function index(TripRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $trips = $paginator->paginate($repo->findAvailableTrips(), $request->query->getInt('page', 1), 12);
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
        $trips = count($repo->findUserTrips($user->getEmail())) + 1;
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = $form->getData();
            $trip->setName('VR - ' . $user->getEmail() . ' - ' . $trips);
            $trip->setDescription('Voyage reservé par ' . $user->getEmail());
            $trip->setAvailableSeatNumber(0);
            $trip->setReserved(true);
            $trip->setPrice();
            $trip->setStatus('1');
            $em->persist($trip);
            $em->flush();
            return $this->redirectToRoute('app_trip_payment', [
                'id' => $trip->getId()
            ]);
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
    public function show(Trip $trip, Request $request): Response
    {
        if ($trip->getReserved() === true) {
            $this->addFlash('danger', 'Une erreur s\'est produite.');
            return $this->redirectToRoute('app_trip_index');
        }
        $previousUrl = $request->headers->get('referer');
        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
            'previousUrl' => $previousUrl
        ]);
    }

    /**
     * show a recap of the trip and proceed to payment
     * @Route("/trips/{id}/payment", name="app_trip_payment")
     */
    public function reserveTrip(Trip $trip, Request $request): Response
    {
        if ($trip->getReserved() === false && $trip->getAvailableSeatNumber() === 0) {
            $this->addFlash('danger', 'Ce voyage n\'a malheureusement plus de places disponibles.');
            return $this->redirectToRoute('app_trip_index');
        }
        $previousUrl = $request->headers->get('referer');
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Veuillez vous connecter');
        $user = $this->getUser();
        return $this->render('trip/reserve.html.twig', [
            'price' => $trip->getPrice(),
            'trip' => $trip,
            'user' => $user,
            'previousUrl' => $previousUrl
        ]);
    }

    /**
     * Page called when a trip is successfully reserved
     * @Route("/trips/{id}/succeed",name="app_trip_succeed")
     * @return Response
     */
    public function onReservationSuccess(Trip $trip, EntityManagerInterface $em, Request $request): Response
    {
        $token = $request->request->get('token');
        if ($trip->getReserved() === false && $trip->getAvailableSeatNumber() === 0) {
            $this->addFlash('danger', 'Ce voyage n\'a malheureusement plus de places disponibles.');
            return $this->redirectToRoute('app_trip_index');
        }
        if ($this->isCsrfTokenValid('purchasing', $token)) {

            if ($trip->getReserved() === false) {
                $trip->setAvailableSeatNumber($trip->getAvailableSeatNumber() - 1);
                if ($trip->getAvailableSeatNumber() === 0) {
                    $trip->setStatus(3);
                }
                $trip->addUser($this->getUser());
            } else {
                $trip->setStatus(2);
            }
            $em->flush();

            return $this->render('trip/recap.html.twig', [
                'trip' => $trip,
            ]);
        }
        $this->addFlash('danger', 'Une erreur s\'est produite. Le paiement a echoué. Veuillez recommencer.');
        return $this->render('trip/reserve.html.twig', [
            'trip' => $trip
        ]);
    }

    /**
     * Delete a created trip 
     * @Route("/trips/{id}/delete", name="app_trip_delete")
     * @return Response
     */
    public function delete(Trip $trip, EntityManagerInterface $em, TripRepository $repo): Response
    {
        $user = $this->getUser();

        if ($trip->getReserved() === false) {
            $user->removeTrip($trip);
            $em->flush();
            $this->addFlash('success', 'Vous avez été désisté de ce voyage.');
        } else {
            $tripName = explode(' - ', $trip->getName());
            if ($tripName[1] !== $user->getEmail()) {
                $this->addFlash('warning', 'Une erreur s\'est produite lors de la suppression.');
            } else {
                $em->remove($trip);
                $em->flush();
                $this->addFlash('success', 'Votre voyage " ' . $trip->getName() . ' " a été supprimé. Vous serez remboursé ulterieurement.');
            }
        }
        return $this->redirectToRoute('app_user_profile');
    }

    /**
     * Edit a trip by his user
     * @Route("/trips/{id}/edit",name="app_trip_edit")

     * @return Response
     */
    public function edit(Trip $trip, EntityManagerInterface $em, TripRepository $repo, Request $request): Response
    {
        if ($trip->getReserved() === false) {
            $this->addFlash('danger', 'Une erreur s\'est produite. Vous ne disposez pas des autorisations pour la modification de ce voyage.');
            return $this->redirectToRoute('app_home');
        }

        $tripName = explode(' - ', $trip->getName());
        $user = $this->getUser();

        if (!$tripName || $tripName[1] !== $user->getEmail()) {
            $this->addFlash('danger', 'Une erreur s\'est produite. Vous ne ddddisposez pas des autorisations pour la modification de ce voyage.');
            return $this->redirectToRoute('app_home');
        }
        $trips = count($repo->findUserTrips($user->getEmail()));
        $previousPrice = $trip->getPrice();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trip = $form->getData();
            $trip->setReserved(true);
            $trip->setPrice();
            $em->flush();
            if ($trip->getPrice() > $previousPrice) {
                $trip->setStatus(1);
                $em->flush();
                $this->addFlash('success', 'Votre précédent voyage a été annulé et un remboursement sera effectué ulterieurement si necessaire. Veuillez procéder au paiement du nouveau voyage.');
                return $this->redirectToRoute('app_user_profile');
            } else {
                $this->addFlash('success', 'Votre voyage a été modifié. Si un remboursement est necessaire, il sera effectué ulterieurement.');
                return $this->redirectToRoute('app_user_profile');
            }
        }
        return $this->render('trip/edit.html.twig', [
            'form' => $form->createView(),
            'trips' => $trips
        ]);
    }

    /**
     * Get the weather forecast when creating a trip
     * @Route("/trips/create/weather/{city}/{date}",name="app_ajax_trip_create_ajax")
     */
    public function getWeather(string $date, string $city, CallWeatherApi $callWeatherApi, Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $date = str_replace('-', '/', $date);
            $date = strtotime($date);
            $weatherData =  $callWeatherApi->getWeatherData($city, $date);
            $jsonData = [
                'weather' => $weatherData

            ];
        }

        return new JsonResponse($jsonData);
    }
}
