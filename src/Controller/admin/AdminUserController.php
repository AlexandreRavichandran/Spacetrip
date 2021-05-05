<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * Show all users
     * @Route("/admin/users/show/", name="app_admin_ser_show")
     */
    public function index(): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'controller_name' => 'AdminUserController',
        ]);
    }
}
