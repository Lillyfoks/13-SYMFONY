<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends AbstractController
{

    #[Route('/commande', name: 'app_add_order')]
    public function add(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('app_home');
        }

        $order = new Order();

        $order->setUser($this->getUser());
        $order->getId(uniqId());


        foreach($panier as $item => $quantity){
            $orderDetails = new OrderDetails();

            $product = $productsRepository->find($item);
            $price = $product->getPrice();
            
            $orderDetails->addProduct($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->setOrderDetails($orderDetails);
        }

        $entityManagerInterface->persist($order);
        $entityManagerInterface->flush();

        $session->remove('panier');

        return $this->render('order/index.html.twig', [
            'order' => $order,,
        ]);
    }
}
