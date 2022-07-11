<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Controller to manage users.
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\Controller
 */
class UserController extends AbstractController
{
    private ManagerRegistry $doctrine;

    /**
     * The constructor.
     *
     * @return void
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * User list page.
     *
     * @Route("/users", name="user_list")
     * @IsGranted("ROLE_ADMIN" , message="Cette page est réservée aux administrateurs")
     */
    public function listAction(): Response
    {
        return $this->render('user/list.html.twig', ['users' => $this->doctrine->getRepository('App\Entity\User')->findAll()]);
    }

    /**
     * User creation page.
     *
     * @Route("/users/create", name="user_create")
     * @IsGranted("ROLE_ADMIN" , message="Cette page est réservée aux administrateurs")
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->doctrine->getManager()->persist($user);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * User edition page.
     *
     * @Route("/users/{id}/edit", name="user_edit")
     * @IsGranted("ROLE_ADMIN" , message="Cette page est réservée aux administrateurs")
     *
     * @return Response|RedirectResponse
     */
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->doctrine->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
