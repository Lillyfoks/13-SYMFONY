<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if($panier === []){
            $this->addFlash('message', 'panier vide');
            return $this->redirectToRoute('app_home');
        }

        //panier remplit, créa cde
        $order = new Order();

        // remplissage cde
        $order->setUserId($this->getUser());
        $order->setDate(new DateTime());
        
        //parcours du panier pour créer détails cde
        foreach($panier as $item => $quantity){
            $orderDetails = new OrderDetails();

            //récup produit
            $product = $productRepository->find($item);

            //créa détail de cde (orderDetails)
            $orderDetails->setProductId($product);
            $orderDetails->setQuantity($quantity);

            $order->addOrderDetail($orderDetails);
        }

        //persist + flush
        $em->persist($order);
        $em->flush();

        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
