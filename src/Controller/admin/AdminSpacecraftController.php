<?php

namespace App\Controller\admin;

use App\Entity\Spacecraft;
use App\Form\SpacecraftType;
use App\Repository\TripRepository;
use App\Repository\SpacecraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSpacecraftController extends AbstractController
{
    private $em;
    private $spacecraftRepository;
    private $paginator;

    /**
     * Function construrct for AdminSpacecraftController
     */
    public function __construct(EntityManagerInterface $em, SpacecraftRepository $spacecraftRepository, PaginatorInterface $paginatorInterface)
    {
        $this->em = $em;
        $this->spacecraftRepository = $spacecraftRepository;
        $this->paginator = $paginatorInterface;
    }

    /**
     * Show all spacecrafts
     * @Route("/admin/spacecrafts",name="app_admin_spacecraft_index")
     * @param SpacecraftRepository $repo
     * @return Response
     */
    public function index(Request $request): Response
    { //Check roles
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $spacecrafts = $this->paginator->paginate($this->spacecraftRepository->findAll(), $request->query->getInt('page', 1), 11);
        return $this->render('admin/spacecraft/index.html.twig', [
            'spacecrafts' => $spacecrafts,
            'order' => null,
            'orderBy' => null
        ]);
    }
    /**
     * Create a new Spacecraft
     * @Route("/admin/spacecrafts/create", name="app_admin_spacecraft_create")
     * @return Response
     */
    public function create(Request $request): Response
    { //Check roles
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $form = $this->createForm(SpacecraftType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $spacecraft = new Spacecraft;
            $spacecraft = $form->getData();
            $spacecraft->setRating()
                ->setAvailable(true);
            $this->em->persist($spacecraft);
            $this->em->flush();
            $this->addFlash('success', 'L\'ajout du nouveau vaisseau a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_spacecraft_index');
        }
        return $this->render('admin/spacecraft/create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create'
        ]);
    }
    /**
     * Edit a spacecraft
     * @Route("/admin/spacecrafts/{id}/edit", name="app_admin_spacecraft_edit")
     * @return Response
     */
    public function edit(Spacecraft $spacecraft, Request $request): Response
    { //Check roles
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $form = $this->createForm(SpacecraftType::class, $spacecraft);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $spacecraft = $form->getData();
            $this->em->flush();
            $this->addFlash('success', 'La modification du vaisseau a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_spacecraft_index');
        }
        return $this->render('admin/spacecraft/edit.html.twig', [
            'form' => $form->createView(),
            'spacecraft' => $spacecraft,
            'action' => 'edit'
        ]);
    }

    /**
     * Delete a spacecraft
     * @Route("/admin/spacecrafts/{id}/delete", name="app_admin_spacecraft_delete") 
     * @return Reponse
     */
    public function delete(Spacecraft $spacecraft, TripRepository $repo): Response
    { //Check roles
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $trips = $repo->findBy(['spacecraft' => $spacecraft->getId()]);
        if ($trips) {
            $tripNames = [];
            foreach ($trips as $trip) {
                array_push($tripNames, $trip->getName());
            }
            $tripNames = implode(', ', $tripNames);
            $this->addFlash('warning', 'Echec de la suppression : Ce vaisseau est associé aux voyages suivants :' . $tripNames . '. Veuillez modifier ces voyages avant la suppression de ce vaisseau.');
            $this->redirectToRoute('app_admin_spacecraft_index');
        } elseif (!$trips) {
            $this->em->remove($spacecraft);
            $this->em->flush();
            $this->addFlash('success', 'La suppression du vaisseau a été effectué avec succès.');
            return $this->redirectToRoute('app_admin_spacecraft_index');
        }

        return $this->redirectToRoute('app_admin_spacecraft_index');
    }

    /**
     * Sort all showed spacecrafts
     * @Route("/admin/spacecrafts/{orderBy}/{order}",name="app_admin_spacecraft_sort")
     * @return Response
     */
    public function sort(Request $request, string $orderBy, string $order): Response
    { //Check roles
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");
        $spacecrafts = $this->paginator->paginate($this->spacecraftRepository->orderSpacecrafts($orderBy, $order), $request->query->getInt('page', 1), 11);
        return $this->render('admin/spacecraft/index.html.twig', [
            'spacecrafts' => $spacecrafts,
            'order' => $order,
            'orderBy' => $orderBy
        ]);
    }

    /**
     * Make a spacecraft available on the admin spacecraft index (AJAX request)
     * @Route("/admin/spacecraft/available",name="app_admin_spacecraft_available")
     * @return void
     */
    public function makeSpacecraftAvailable(Request $request, SpacecraftRepository $repo, EntityManagerInterface $em)
    {
        if ($request->isXmlHttpRequest()) {
            $spacecraft = $repo->findOneBy(['id' => $_POST['id']]);
            $spacecraft->setAvailable($_POST['value']);
            $em->flush();
        }
        return new JsonResponse();
    }
}
