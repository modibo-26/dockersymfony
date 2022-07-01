<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $product = $this->getReference('product-' . rand(1, 10));
        for ($i = 1; $i <= 10000; $i++) {
            $image = new Image();
            $image
                ->setName($faker->imageUrl(640, 480))
                ->setProduct($product);
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class
        ];
    }
}
