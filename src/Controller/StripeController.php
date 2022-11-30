<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{

    public function __construct(HttpClientInterface $client, ProductRepository $productRepository, OrderRepository $orderRepository)
    {
        $this->client = $client;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {

        $products = $this->productRepository->findAll();

        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
            'products' => $products
        ]);
    }

    #[Route('/stripe/create-session/{reference}', name: 'stripe_create_session')]
    public function StripeCreateSession(Cart $cart, $reference, EntityManagerInterface $em ): Response
    {

        $order = $this->orderRepository->findOneBy(['reference' => $reference]);

        if(!$order){
            return $this->redirect('order');
        }

        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8000';

        foreach ($cart->getAll() as $product) {
            $products_for_stripe[] = [
                'price' => $product['product']->getStripePriceId(),
                'quantity' => $product['quantity'],
            ];
        }

        Stripe::setApiKey('sk_test_51M4Q9SITNoLSnaU2lCuvj6KcrK7MvIHSbuWHU9LwxpNq5ytBrq5cGLLMXQRqscvA8ats1TdbEjZUJGiaeGtmDL1I002Ov9nc4r');

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'shipping_address_collection' => ['allowed_countries' => ['FR', 'CA']],
            'shipping_options' => [
                [
                  'shipping_rate_data' => [
                    'fixed_amount' => ['amount' => 0, 'currency' => 'eur'],
                    'display_name' => $order->getCarrierName(),
                    'delivery_estimate' => [
                      'minimum' => ['unit' => 'business_day', 'value' => 2],
                      'maximum' => ['unit' => 'business_day', 'value' => 7],
                    ],
                  ],
                ],
              ],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        return $this->redirect($checkout_session->url);
    }

    #[Route('/stripe/add/{id}', name: 'app_stripe_add')]
    public function add($id, EntityManagerInterface $em): Response
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        $response = $this->client->request(
            'POST',
            'https://api.stripe.com/v1/products',
            [
                'auth_bearer' => "sk_test_51M4Q9SITNoLSnaU2lCuvj6KcrK7MvIHSbuWHU9LwxpNq5ytBrq5cGLLMXQRqscvA8ats1TdbEjZUJGiaeGtmDL1I002Ov9nc4r",
                'body' => [
                    'name' => 'TestDepuisApi3',
                    'default_price_data[currency]' => 'eur',
                    'default_price_data[unit_amount]' => 900
                ]
            ]
        );

        $stripeId = $response->toArray()['id'];
        $stripePriceId = $response->toArray()['default_price'];

        $product->setStripeId($stripeId);
        $product->setStripeId($stripePriceId);
        $em->persist($product);
        $em->flush();


        return $this->redirectToRoute('app_stripe');
    }
}
