<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductsRepository;
use App\Entity\Products;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(EntityManagerInterface $entityManager,)
    {
        $categories = $entityManager->getRepository(Categories::class)->findAll();
        $products = $entityManager->getRepository(Products::class)->findAll();

        return $this->render('home/index.html.twig', [
            'categories'=>$categories,
            'products'=>$products,
        ]);
    }

    public function searchBar()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'width: 150px;',
                ]
            ])
            ->getForm();

        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/handleSearch", name: "handleSearch")]
public function handleSearch(Request $request, ProductsRepository $repo)
{
    $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('handleSearch'))
        ->add('query', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Rechercher'
            ]
        ])
        ->add('recherche', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $query = $form->get('query')->getData();

        if ($query) {
            $product = $repo->findProductByName($query);
        }

        return $this->render('search/index.html.twig', [
            'product' => $product
        ]);
    }

    return $this->render('search/searchBar.html.twig', [
        'form' => $form->createView()
    ]);
}

}
