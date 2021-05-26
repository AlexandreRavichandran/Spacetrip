<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\TripRepository;
use App\Repository\FeedbackRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Show homepage
     * @Route("/", name="app_home")
     * @return Response
     */
    public function index(TripRepository $tripRepo, FeedbackRepository $feedbackRepo): Response
    {
        $trips = $tripRepo->findBy(['reserved' => false], ['departureAt' => 'DESC'], 2);
        $feedbacks = $feedbackRepo->findBy([], ['rating' => 'DESC'], 3);
        return $this->render('home/index.html.twig', [
            'trips' => $trips,
            'feedbacks' => $feedbacks
        ]);
    }

    /**
     * Generate PDF reservation ticket
     * @Route("/{trip}/tripticket.pdf", name="app_get_ticket")
     * @return void
     */
    public function generateReservationTicket(Trip $trip, Request $request, TCPDFController $tcpdf)
    {
        // creating pdf 
        $token = $request->request->get('token');
        if ($this->isCsrfTokenValid('getTicket', $token)) {
            $user = $this->getUser();

            $pdf = $tcpdf->create();
            $pdf->SetAuthor('Spacetrip');
            $pdf->SetTitle('Ticket de voyage de ' . $user->getFirstName() . ' ' . $user->getLastName());
            $pdf->AddPage();
            $pdf->SetMargins(25, 70, 25);
            $html =
                " <h1 class='ml-auto mr-auto'> Ticket de reservation de " .  $user->getFirstName() . " " . $user->getLastName() . "</h1>
             <h2 class='ml-auto mr-auto'> Voyage " . $trip->getName() . "</h2>
             <p class='ml-auto mr-auto mb-4'><strong> Gardez ce ticket precieusement.</strong> Il vous sera demandé avant l'embarquement. </p>
         ";
            $pdf->WriteHTML($html, true, false, true, false, '');
            $style = [
                'position' => 'C',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => true,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 4
            ];
            $pdf->write1DBarcode($trip->getName() . $user->getFirstName() . $user->getLastName(), 'C39', '', '', '', 18, 0.4, $style, 'N');

            $response = new Response($pdf->Output('voyage_' . $trip->getName() . '_' . mt_rand(0, 1000000) . '.pdf'));
            $response->headers->set('Content-type', 'application/pdf');
            return $response;
        }
        return $this->redirectToRoute('app_home');
    }
}
