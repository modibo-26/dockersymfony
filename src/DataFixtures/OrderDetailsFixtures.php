<?php

namespace App\DataFixtures;

use App\Entity\OrderDetails;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderDetailsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void {

        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 100; $i++) {
            $order = $this->getReference('order-' . rand(1, 10));
            $product =  $this->getReference('product-' . rand(1, 500));
            $orderDetails = new OrderDetails();
            $orderDetails
                ->setQuantity($faker->numberBetween(1, 10))
                ->setPrice($faker->randomFloat(2, 10, 150))
                ->setProduct($product)
                ->setOrder($order);
            $manager->persist($orderDetails);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrderFixtures::class,
            ProductFixtures::class
        ];
    }
    
}
