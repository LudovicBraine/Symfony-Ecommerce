<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    #[Route('/account/address', name: 'app_account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/account/address/add', name: 'app_account_address_add')]
    public function add(Cart $cart, Request $request, EntityManagerInterface $em): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $em->persist($address);
            $em->flush();

            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('app_account_address');
            }
        }

        return $this->render('account/addressForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/address/edit/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, EntityManagerInterface $em, $id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneBy(['id' => $id]);

        if (!$address || $address->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/addressForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/address/delete/{id}', name: 'app_account_address_delete')]
    public function delete(EntityManagerInterface $em, $id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneBy(['id' => $id]);

        if ($address && $address->getUser() === $this->getUser()) {
            $em->remove($address);
            $em->flush();
        }
        return $this->redirectToRoute('app_account_address');
    }
}
