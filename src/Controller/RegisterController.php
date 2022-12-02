<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $encoder, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $notification = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $seach_email = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if(!$seach_email){
                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $em->persist($user);
                $em->flush();
                $notification = "Your registration went well, you can now log in to your account";

                $mail = new Mail();
                $content = "Hello".$user->getFirstname()."Bienvenue sur la boutique made in france et de test parce que j'en ai marre de faire des mails de merde";
                $mail->send($user->getEmail(), $user->getFirstname(), "Welcome to La Boutique Française", $content);
            } else {
                $notification = "The email you provided already exists";
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
