<?php

namespace App\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * Show all users
     * @Route("/admin/users", name="app_admin_user_index")
     */
    public function index(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }
}
