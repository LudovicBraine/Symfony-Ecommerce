<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class Cart
{
    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        $this->session->remove('cart');
    }

    public function removeOne($id)
    {
        $cart = $this->session->get('cart');

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);
        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }

    public function getAll()
    {
        $cartComplete = [];

        foreach ($this->get() as $id => $quantity) {
            $productObject =  $this->productRepository->findOneBy(['id' => $id]);

            if (!$productObject) {
                $this->removeOne($id);
                continue;
            }

            $cartComplete[] = [
                'product' => $productObject,
                'quantity' => $quantity
            ];
        }

        return $cartComplete;
    }
}
