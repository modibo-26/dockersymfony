<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddCartFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'app_product_')]
class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
        
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Product $product): Response
    {
        $cartForm = $this->createForm(AddCartFormType::class, $product);
        $relatedProducts = $this->productRepository->findProductsInSameCategory($product);
        return $this->renderForm('product/details.html.twig', compact('product', 'relatedProducts', 'cartForm'));
    }
}
