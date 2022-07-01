<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($p= 1; $p <= 500; $p++) {
            $category = $this->getReference('category-'. rand(1, 7));
            $product = new Product();
            $product
                ->setName($faker->text(20))
                ->setDescription($faker->text())
                ->setSlug($this->slugger->slug($product->getName())->lower())
                ->setPrice($faker->randomFloat(2, 10, 150))
                ->setQuantity($faker->numberBetween(3, 10))
                ->setCategory($category);
                if($p % 2 == 0) $product->setSoldPrice($faker->randomFloat(0, 15, 80));
            $manager->persist($product);
            $this->addReference('product-' . $p, $product);

            $manager->flush();
        }
    }
}
