<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Delivery;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShippingController extends AbstractController
{
    #[Route('/shipping', name: 'app_shipping')]
    public function Shipping(Request $request, EntityManagerInterface $entityManager): Response
    {

        $orderDetails = new OrderDetails();

        $form = $this->createFormBuilder()
        ->add('Adresse', EntityType::class, [
            'class' => Address::class,
            'choice_label' => function ($adresse) {
                return $adresse->getStreet() . ', ' . $adresse->getCp() . ', ' . $adresse->getCity();
            },
        ])
        ->add('Transporteur', EntityType::class, [
            'class' => Delivery::class,
            'choice_label' => 'name', // Remplacez 'name' par le champ que vous souhaitez afficher
        ])
        ->add('save', SubmitType::class, ['label' => 'Valider'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager->persist($orderDetails);
            $entityManager->flush();
        }

        return $this->render('shipping/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
