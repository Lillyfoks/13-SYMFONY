<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_productCards')]
    public function index(EntityManagerInterface $entityManager)
    {
        $products = $entityManager->getRepository(Products::class)->findAll();


        return $this->render('product/productCards.html.twig', [
            'products' => $products,
        ]);
    }



    #[Route('/product/add', name: 'app_add_product')]
    public function addproduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => ' ',
                    'required' => true,
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => ' ',
                    'required' => true,
                ],
            ])
            ->add('promotion', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => false,
                'attr' => [],
            ])
            ->add('discount', IntegerType::class, [
                'label' => 'Remise',
                'attr' => [
                    'placeholder' => ' ',
                    'required' => true,
                    'max' => 100,
                ],
                'data' => 0,
            ])

            // ->add('file', FileType::class, [
            //     'label' => 'Upload file',
            //     'required' => false,
            // ])

            ->add('Category', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionnez une catégorie',
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter un Produit',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/edit/{id}', name: 'app_edit_product')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => ' ',
                    'required' => true,
                ],
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => ' ',
                    'required' => true,
                ],
            ])
            ->add('promotion', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => false,
                'attr' => [],
            ])
            ->add('discount', IntegerType::class, [
                'label' => 'Remise',
                'attr' => [
                    'placeholder' => ' ',
                    'required' => true,
                    'max' => 100,
                ],
                'data' => 0,
            ])

            // ->add('file', FileType::class, [
            //     'label' => 'Upload file',
            //     'required' => false,
            // ])

            ->add('Category', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionnez une catégorie',
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $product = $form->getData();

            // ... perform some action, such as saving the task to the database
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/add.html.twig', ['form' => $form]);
    }

    #[Route('/product/delete/{id}', name: 'app_delete_product')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/product/{id}', name: 'app_product_details')]
    public function showProductDetails(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->findOneBy(['id' => $id]);

        return $this->render('product/details.html.twig', ['product' => $product]);
    }
}
