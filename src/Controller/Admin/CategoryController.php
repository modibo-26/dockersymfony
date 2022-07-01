<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/categories', name: 'admin_categories_')]
class CategoryController extends AbstractController
{    

    public function __construct(private CategoryRepository $categoryRepository) {}

    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render(
            'admin/categories/index.html.twig',
            compact('categories')
        );
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $category->setName( $form->get('name')->getData());
            $category->setParent( $form->get('parent')->getData());
            $category->setSlug($slugger->slug($category->getName())->lower());

            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('admin_categories_index');
            // do anything else you need here, like send an email
        }

        return $this->render(
            'admin/categories/create.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager, SluggerInterface $slugger, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($slugger->slug($category->getName())->lower());
            $entityManager->persist($category);
            $entityManager->flush();
            
            return $this->redirectToRoute('admin_categories_index');
        }

        $form = $form->createView();
        return $this->render(
            'admin/categories/edit.html.twig', compact('category', 'form'));
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
    
        $categoryRepository->remove($category);

        $entityManager->flush();
        
        return $this->redirectToRoute('admin_categories_index');
    }
}
