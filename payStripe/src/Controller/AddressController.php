<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/address', name: 'app_address')]
    public function index(Request $request, AddressType $address, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur actuellement authentifié
        $user = $this->getUser();

        $address = new Address();

        // Définissez l'utilisateur pour l'addresse
        $address->setUserId($user);

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/address.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
