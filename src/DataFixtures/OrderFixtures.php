<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void {

        $faker = Factory::create('fr_FR');

        for ($o = 1; $o <= 10; $o++) {
            $user = $this->getReference('user-' . rand(1, 10));
            $order = new Order();
            $order
            ->setReference($faker->numberBetween(1, 110000))
            ->setUser($user);
            $manager->persist($order);
            $this->addReference('order-' . $o, $order);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
    
}
