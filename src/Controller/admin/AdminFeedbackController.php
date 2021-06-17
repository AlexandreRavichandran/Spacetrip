<?php

namespace App\Controller\admin;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFeedbackController extends AbstractController
{
    private $repo;
    private $paginator;

    /**
     * Construct function of AdminFeedbackController
     */
    public function __construct(PaginatorInterface $paginator, FeedbackRepository $repo)
    {
        $this->paginator = $paginator;
        $this->repo = $repo;
    }

    /**
     * Show all feedbacks
     * @Route("/admin/feedbacks", name="app_admin_feedback_index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $feedbacks = $this->paginator->paginate($this->repo->findAll(), $request->query->getInt('page', 1), 11);

        return $this->render('admin/feedback/index.html.twig', [
            'feedbacks' => $feedbacks,
            'order' => null,
            'orderBy' => null
        ]);
    }

    /**
     * Delete a feedback
     * @Route("/admin/feedbacks/delete/{id}",name="app_admin_feedback_delete")
     * @return Response
     */
    public function delete(Feedback $feedback, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $em->remove($feedback);
        $em->flush();
        $this->addFlash('success', 'Ce commentaire a été supprimé avec succès.');
        return $this->redirectToRoute('app_admin_feedback_index');
    }

    /**
     * Sort all showed feedbacks
     * @Route("/admin/feedbacks/{orderBy}/{order}",name="app_admin_feedback_sort")
     * @return Response
     */
    public function sort(string $orderBy, string $order, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $feedbacks = $this->paginator->paginate($this->repo->orderFeedbacks($orderBy, $order), $request->query->getInt('page', 1), 11);
        return $this->render('admin/feedback/index.html.twig', [
            'feedbacks' => $feedbacks,
            'order' => $order,
            'orderBy' => $orderBy
        ]);
    }
}
