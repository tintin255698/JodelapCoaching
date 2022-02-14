<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('confirmation_inscription', ['id' => $user->getId()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Merci, bienvenue chez Jodelap Coaching ! Votre adresse email a bien été vérifiée ');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/conditions-de-vente", name="condition")
     */
    public function index(): Response
    {
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/confirmation-inscription/{id}", name="confirmation_inscription")
     */
    public function confirmation(User $id): Response
    {
       if($this->getUser()){
           return $this->redirectToRoute('home');
       } else {
           $this->emailVerifier->sendEmailConfirmation('app_verify_email', $id,
               (new TemplatedEmail())
                   ->from(new Address('jodelap.coaching@gmail.com', 'Jodelap Coaching'))
                   ->to($id->getEmail())
                   ->subject('Merci de confirmer votre email')
                   ->htmlTemplate('registration/confirmation_email.html.twig')
           );
       }
        return $this->render('registration/check_registration.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
