<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{

    private $counter = 1;

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {

        $parent = $this->generateCategory('Hommes', null, $manager);

        $this->generateCategory('Pantalons', $parent, $manager);
        $this->generateCategory('Chemises', null, $manager);

        $parent = $this->generateCategory('Femmes', null, $manager);

        $this->generateCategory('Robes', $parent, $manager);
        $this->generateCategory('Chaussures', $parent, $manager);
        $this->generateCategory('Talons', $parent, $manager);

        $manager->flush();
    }

    private function generateCategory(string $name, Category $parent = null, ObjectManager $manager) {
        $category = new Category();
        $category
        ->setName($name)
        ->setSlug($this->slugger->slug($category->getName())->lower())
        ->setParent($parent);
        $manager->persist($category);

        $this->addReference('category-' . $this->counter, $category);
        $this->counter++;
        return $category;
    }
}
