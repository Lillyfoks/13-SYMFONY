<?php

namespace App\Controller;

use App\Form\DeliveryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/create', name: 'app_order')]
    public function index(): Response
    {

        if(!$this->getUser()){
                return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(DeliveryType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
