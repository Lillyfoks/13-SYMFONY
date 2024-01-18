<?php

namespace App\Controller;


use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entityManager)
    {
        $categories = $entityManager->getRepository(Categories::class)->findBy(['user'=>$this->getUser()]);
        return $this->render('category/index.html.twig', [
            'categories'=>$categories
        ]);
    }

    #[Route('/category/add', name: 'app_add_category')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $category = new Categories();

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('categoryColor', ColorType::class, [
                'label' => 'Category Color',
            ])
            ->add('save', SubmitType::class, ['label' => 'CreateCategory'])
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $category = $form->getData();
            $category->setUser($this->getUser());
            // ... perform some action, such as saving the task to the database
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($category);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/add.html.twig', ['form'=>$form]);
    }

    #[Route('/category/edit/{id}', name: 'app_edit_category')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id '.$id
            );
        }

        $form = $this->createFormBuilder($category)
        ->add('name', TextType::class)
        ->add('categoryColor', ColorType::class, [
            'label' => 'Category Color',
        ])
        ->add('save', SubmitType::class, ['label' => 'Edit Category'])
        ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $category = $form->getData();
    
                // ... perform some action, such as saving the task to the database
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($category);
    
                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
    
                return $this->redirectToRoute('app_category');
            }

            return $this->render('category/add.html.twig', ['form'=>$form]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // configurez vos options ici
        ]);
    }
    
    #[Route('/category/delete/{id}', name: 'app_delete_category')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $entityManager->getRepository(Categories::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_category');
    }

}
