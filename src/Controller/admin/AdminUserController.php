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
    private $userRepository;
    private $paginator;

    /**
     * Function construrct for AdminUserController
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginatorInterface)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginatorInterface;
    }
    /**
     * Show all users
     * @Route("/admin/users", name="app_admin_user_index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $users = $this->paginator->paginate($this->userRepository->filterByRoles('ROLE_USER'), $request->query->getInt('page', 1), 11);

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
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $feedbacks = $repo->findBy(['user' => $user->getId()]);
        $userTrips = $user->getTrip();

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'feedbacks' => $feedbacks,
            'userTrips' => $userTrips
        ]);
    }

    /**
     * Sort all showed users
     * @Route("/admin/users/{orderBy}/{order}",name="app_admin_user_sort")
     * @return Response
     */
    public function sort(Request $request, string $orderBy, string $order): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $users = $this->paginator->paginate($this->userRepository->orderUsers($orderBy, $order), $request->query->getInt('page', 1), 11);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'order' => $order,
            'orderBy' => $orderBy
        ]);
    }
}
