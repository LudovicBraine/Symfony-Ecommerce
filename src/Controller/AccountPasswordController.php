<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    #[Route('/account/edit-password', name: 'app_account_edit_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $em): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(EditPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $oldPassword)) {
                $newPassword = $encoder->hashPassword($user, $form->get('new_password')->getData());
                $user->setPassword($newPassword);
                $em->persist($user);
                $em->flush();
                $notification = "Your password is update";
            } else {
                $notification = "Your current password is wrong";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
