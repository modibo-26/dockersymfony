<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/orders', name: 'admin_orders_')]
class OrderController extends AbstractController
{

    public function __construct(private OrderRepository $orderRepository) {}

    #[Route('/', name: 'index')]
    public function index( OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAll();
        return $this->render(
            'admin/order/index.html.twig',
            compact('orders')
        );
    }

    #[Route('/{reference}', name: 'details')]
    public function details(Order $orders, OrderDetailsRepository $orderDetailsRepository): Response    
    {
        $orderDetails = $orderDetailsRepository->findBy(['order' => $orders]);
        return $this->render('admin/order/details.html.twig',
        compact('orderDetails', 'orders')
        );
    }
}
