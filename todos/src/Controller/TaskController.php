<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(EntityManagerInterface $entityManager)
    {
        $tasks = $entityManager->getRepository(Tasks::class)->findBy(['user'=>$this->getUser()]);
        return $this->render('task/index.html.twig', [
            'tasks'=>$tasks
        ]);
    }

    #[Route('/task/add', name: 'app_add_task')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $task = new Tasks();
        $task->setDuedate(new \DateTimeImmutable('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('DueDate', DateType::class, ['label' => 'DueDate'])
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Haute' => 'Haute',
                    'Normale' => 'Normale',
                    'Basse' => 'Basse',
                ],
                ])
            ->add('Category', EntityType::class, [
                'class' => Categories::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')->where('c.user = :user')
                    ->setParameter('user', $this->getUser());
                },
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'CreateTask'])
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $task = $form->getData();
                $task->setUser($this->getUser());
                // ... perform some action, such as saving the task to the database
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($task);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();

                return $this->redirectToRoute('app_task');
            }

        return $this->render('task/add.html.twig', ['form'=>$form]);
    }

    #[Route('/task/edit/{id}', name: 'app_edit_task')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $task = $entityManager->getRepository(Tasks::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $form = $this->createFormBuilder($task)
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('duedate', DateType::class)
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Haute' => 'Haute',
                    'Normale' => 'Normale',
                    'Basse' => 'Basse',
                ],
                ])
            ->add('Category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Edit Task'])
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $task = $form->getData();
            
                // ... perform some action, such as saving the task to the database
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($task);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();

                return $this->redirectToRoute('app_task');
            }

        return $this->render('task/add.html.twig', ['form'=>$form]);
    }

    #[Route('/task/delete/{id}', name: 'app_delete_task')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $task = $entityManager->getRepository(Tasks::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('app_task');
    }

}

