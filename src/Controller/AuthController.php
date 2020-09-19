<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(LoginType::class, null, ['lastUsername' => $authenticationUtils->getLastUsername()]);

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
            'errors' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function registration(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        UserService $userService
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $user = new User();
        $user->setApiToken($userService->generateApiToken());

        $form = $this->createForm(RegistrationType::class, $user, ['validation_groups' => 'registration']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');
        }

        return $this->render('auth/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
