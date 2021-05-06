<?php

namespace App\Controller\admin;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFeedbackController extends AbstractController
{
    /**
     * Show all feedbacks
     * @Route("/admin/feedbacks", name="app_admin_feedback_index")
     * @return Response
     */
    public function index(FeedbackRepository $repo): Response
    {
        $feedbacks = $repo->findAll();

        return $this->render('admin/feedback/index.html.twig', [
            'feedbacks' => $feedbacks,
        ]);
    }

    /**
     * Delete a feedback
     * @Route("/admin/feedbacks/delete/{id}",name="app_admin_feedback_delete")
     * @return Response
     */
    public function delete(Feedback $feedback, EntityManagerInterface $em): Response
    {
        $em->remove($feedback);
        $em->flush();
        $this->addFlash('success', 'Ce commentaire a été supprimé avec succès.');
        return $this->redirectToRoute('app_admin_feedback_show');
    }
}
