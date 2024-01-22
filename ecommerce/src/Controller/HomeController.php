<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProductsRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, ProductsRepository $productsRepository, PaginatorInterface $paginator, Request $request)
    {
        $categories = $entityManager->getRepository(Categories::class)->findAll();
        $products = $entityManager->getRepository(Products::class)->findAll();

        $pagination = $paginator->paginate(
            $productsRepository->paginationQuery(),
            $request->query->get('page', 1),
            5 // Nombre d'éléments par page
        );

        return $this->render('home/index.html.twig', [
            'categories'=>$categories,
            'products'=>$products,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_product')]
    public function getCategoryById(int $id, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categories::class)->findAll();
        $category = $entityManager->getRepository(Categories::class)->find($id);
        $products = $entityManager->getRepository(Products::class)->findBy(['Category' => $category]);
    
        return $this->render('product/productByCategory.html.twig', [
            'categories' => $categories,
            'category' => $category,
            'products' => $products,
        ]);
    }

    
}
