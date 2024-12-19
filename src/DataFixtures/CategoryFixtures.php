<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category1 = new Category();
        $category1->setName('Technology');

        $category2 = new Category();
        $category2->setName('Health');

        $category3 = new Category();
        $category3->setName('Education');

        $category4 = new Category();
        $category4->setName('Entertainment');

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);
        $manager->persist($category4);

        $manager->flush();
    }
}
