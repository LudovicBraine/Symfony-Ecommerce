<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StripeController extends AbstractController
{

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

    public function add(): Response
    {
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

        return $response->toArray()['id'];
    }
}
