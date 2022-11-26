<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StripeController extends AbstractController
{

    public function __construct(HttpClientInterface $client, ProductRepository $productRepository)
    {
        $this->client = $client;
        $this->productRepository = $productRepository;
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
