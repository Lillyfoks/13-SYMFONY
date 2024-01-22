<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $productsRepository->find($id),
                'quantity' => $quantity
            ];
        };

        $total = 0;

        foreach ($panierWithData as $item) {
            $isPromotion = $item['product']->isPromotion();

            if ($isPromotion == 1) {
                $totalItem = ($item['product']->getPrice() * ($item['product']->isPromotion() - (($item['product']->getDiscount()) / 100))) * $item['quantity'];
            } else {
                $totalItem = $item['product']->getPrice() * $item['quantity'];
            }

            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => round($total, 2),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("app_cart");
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(int $id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("app_cart");
    }

    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function dete(int $id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("app_cart");
    }
}
