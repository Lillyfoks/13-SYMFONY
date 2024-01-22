<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entityManager)
    {
        $categories = $entityManager->getRepository(Categories::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories'=>$categories
        ]);
    }

    #[Route('/category/add', name: 'app_add_category')]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Categories();

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => ' ',
                    'required' => true,
                ],
            ])
            ->add('color', ColorType::class, [
                'label' => 'Couleur',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            // $this->addFlash(
            //     'success',
            //     $this->translator->trans('alert.add')
            // );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/edit/{id}', name: 'app_edit_category')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id ' . $id
            );
        }

        $form = $this->createFormBuilder($category)
        ->add('name', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'placeholder' => ' ',
                'required' => true,
            ],
        ])
        ->add('color', ColorType::class, [
            'label' => 'Couleur',
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Modifier',
            'attr' => ['class' => 'btn btn-success'],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $category = $form->getData();

            // ... perform some action, such as saving the task to the database
            // tell Doctrine you want to (eventually) save the category (no queries yet)
            $entityManager->persist($category);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/add.html.twig', ['form' => $form]);
    }

    #[Route('/category/delete/{id}', name: 'app_delete_category')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
    }
}