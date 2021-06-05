<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\FeedbackRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * Show all users
     * @Route("/admin/users", name="app_admin_user_index")
     * @return Response
     */
    public function index(UserRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $users = $paginator->paginate($repo->findAll(), $request->query->getInt('page', 1), 11);
        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'order' => null,
            'orderBy' => null
        ]);
    }

    /**
     * Show one selected user
     * @Route("/admin/users/{id}",name="app_admin_user_show")
     * @return Response
     */
    public function show(User $user, FeedbackRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $feedbacks = $repo->findBy(['user' => $user->getId()]);
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'feedbacks' => $feedbacks
        ]);
    }

    /**
     * Sort all showed users
     * @Route("/admin/users/{orderBy}/{order}",name="app_admin_user_sort")
     * @return Response
     */
    public function sort(UserRepository $repo, PaginatorInterface $paginator, Request $request, string $orderBy, string $order): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $users = $paginator->paginate($repo->orderUsers($orderBy, $order), $request->query->getInt('page', 1), 11);
        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'order' => $order,
            'orderBy' => $orderBy
        ]);
    }
}
