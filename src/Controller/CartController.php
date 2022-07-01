<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        //Créer les data
        $cart = $session->get('cart', []);
        $dataElements = [];
        $total = 0;
        // dd($cart);

        foreach ($cart as $id => $quantity ) {
            $product = $productRepository->find($id);
            $dataElements[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render(
            'cart/index.html.twig',
            compact('dataElements', 'total')
        );
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(SessionInterface $session, Request $request, $id): Response
    {
        $quantity = $request->get('quantity');
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            $cart[$id] = 1; 
        }

        $session->set('cart', $cart);
        return $this->redirectToRoute('app_cart_index');
    }
}
