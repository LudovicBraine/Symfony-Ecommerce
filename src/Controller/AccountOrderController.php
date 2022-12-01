<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    #[Route('/account/orders', name: 'account_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        $orders = $orderRepository->findSuccessOrders($user);

        return $this->render('account_order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/account/orders/show/{reference}', name: 'account_order_show')]
    public function show(OrderRepository $orderRepository, $reference): Response
    {
        $order = $orderRepository->findOneBy(['reference' => $reference]);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account_order/show.html.twig', [
            'order' => $order
        ]);
    }
}
