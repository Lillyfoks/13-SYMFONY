<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Form\AdressFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdressController extends AbstractController
{
    #[Route('/adress', name: 'app_adress')]
    public function adress(Request $request, AdressFormType $adress, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur actuellement authentifié
        $user = $this->getUser();

        $adress = new Adresses();

        // Définissez l'utilisateur pour l'adresse
        $adress->setUser($user);

        $form = $this->createForm(AdressFormType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
        }

        return $this->render('registration/adress.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/adress/edit', name: 'app_adress_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérez l'utilisateur actuel
        $user = $this->getUser();

        $adress = $entityManager->getRepository(Adresses::class)->find($user);
    
        if (!$adress) {
            throw $this->createNotFoundException('Adresse non trouvée');
        }
    
        $adress->setUser($user);
    
        $form = $this->createForm(AdressFormType::class, $adress);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adress);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user');
        }
    
        return $this->render('registration/adress.html.twig', ['form' => $form->createView()]);
    }
    
}
