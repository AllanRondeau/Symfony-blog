<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Languages;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(Users::class)->findAll();
        $languages = $manager->getRepository(Languages::class)->findAll();
        $categories = $manager->getRepository(Category::class)->findAll();

        if (empty($users) || empty($languages) || empty($categories)) {
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . ($i + 1));
            $article->setContent('This is the content of article ' . ($i + 1));

            $article->setUser($users[array_rand($users)]);
            $article->setLanguage($languages[array_rand($languages)]);
            $article->setCategory($categories[array_rand($categories)]);

            $manager->persist($article);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            LanguageFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
