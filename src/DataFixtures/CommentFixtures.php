<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(Users::class)->findAll();
        $articles = $manager->getRepository(Article::class)->findAll();

        if (empty($users)) {
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $comment = new Comment();
            $comment->setTitle('Comment Title ' . ($i + 1));
            $comment->setContent('This is the content of comment ' . ($i + 1));

            $comment->setUser($users[array_rand($users)]);
            $comment->setArticle($articles[array_rand($articles)]);

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ArticleFixtures::class,
        ];
    }
}
