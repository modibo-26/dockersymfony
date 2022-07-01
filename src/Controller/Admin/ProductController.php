<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products', name: 'admin_products_')]
class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository) {}

    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render(
            'admin/products/index.html.twig',
            compact('products')
        );
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $product->setName( $form->get('name')->getData());
            $product->setDescription( $form->get('description')->getData());
            $product->setPrice( $form->get('price')->getData());
            $product->setQuantity( $form->get('quantity')->getData());
            $product->setSoldPrice( $form->get('soldPrice')->getData());
            $product->setSlug($slugger->slug($product->getName())->lower());

            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('admin_products_index');
            // do anything else you need here, like send an email
        }

        return $this->render(
            'admin/products/create.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(productFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slug($product->getName())->lower());
            $entityManager->persist($product);
            $entityManager->flush();
            
            return $this->redirectToRoute('admin_products_index');
        }

        $form = $form->createView();
        return $this->render(
            'admin/products/edit.html.twig', 
            compact('product', 'form')
        );
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $productRepository->remove($product);

        $entityManager->flush();
        
        return $this->redirectToRoute('admin_products_index');  
    }
}
