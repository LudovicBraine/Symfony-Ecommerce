<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset/password', name: 'reset_password')]
    public function index(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }
        
        if($request->get('email')){
            $user = $userRepository->findOneByEmail($request->get('email'));

            if($user){
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new \DateTimeImmutable());

                $em->persist($resetPassword);
                $em->flush();

                $url = $this->generateUrl('update_password', [
                    'token' => $resetPassword->getToken()
                ]);

                $content = "Hello".' '.$user->getFirstname()."<br> Vous avez demandé à réinitialiser votre mot de passe sur le site La Boutique Française. <br>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'> mettre à jour votre mot de passe </a> <br>";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Reset your password on La Boutique Française', $content); 

                $this->addFlash('notice', "Vous allez recevoir dans quelques instant un mail pour réinitialiser votre mot de passe");

            } else {
                $this->addFlash('notice', "Cette adresse email est inconnu");
            }
        }


        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/reset/password/{token}', name: 'update_password')]
    public function updatePassword($token, ResetPasswordRepository $resetPasswordRepository, UserRepository $userRepository, EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $reset_Password = $resetPasswordRepository->findOneBy(['token' =>$token]);
        $user = $userRepository->findOneBy(['id' => $reset_Password->getUser()]);
        
        if(!$reset_Password)
        {
            return $this->redirectToRoute('reset_password');
        }
        
        $now = new \DateTimeImmutable();
        if($now > $reset_Password->getCreatedAt()->modify('+3 hour'))
        {
            $this->addFlash('notice', "Votre demande de réinitialisation de mot de passe à expiré. Merci de la renouveller");
            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $new_pwd = $form->get('new_password')->getData();

            $password = $encoder->hashPassword($user, $new_pwd);
            $user->setPassword($password);

            $em->flush();

            $this->addFlash('notice', 'Votre mot de passe à bien été mis à jour');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
