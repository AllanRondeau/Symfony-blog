<?php

namespace App\DataFixtures;

use App\Entity\Languages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $language1 = new Languages();
        $language1->setName('English');

        $language2 = new Languages();
        $language2->setName('French');

        $language3 = new Languages();
        $language3->setName('German');

        $language4 = new Languages();
        $language4->setName('Spanish');

        $manager->persist($language1);
        $manager->persist($language2);
        $manager->persist($language3);
        $manager->persist($language4);

        $manager->flush();
    }
}
