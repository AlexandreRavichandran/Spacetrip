<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * Show all users
     * @Route("/admin/users", name="app_admin_user_index")
     * @return Response
     */
    public function index(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * Show one selected user
     * @Route("/admin/users/{id}",name="app_admin_user_show")
     * @return Response
     */
    public function show(User $user, FeedbackRepository $repo): Response
    {
        $feedbacks = $repo->findBy(['user' => $user->getId()]);
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'feedbacks' => $feedbacks
        ]);
    }
}
