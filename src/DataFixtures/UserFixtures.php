<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new Users();
        $user1->setEmail('user1@example.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setName('user');
        $user1->setFirstname('user');
        $user1->setPassword(password_hash('password', \PASSWORD_BCRYPT));

        $user2 = new Users();
        $user2->setEmail('user2@example.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setName('user');
        $user2->setFirstname('user');
        $user2->setPassword(password_hash('password', \PASSWORD_BCRYPT));

        $user3 = new Users();
        $user3->setEmail('admin@example.com');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setName('user');
        $user3->setFirstname('user');
        $user3->setPassword(password_hash('password', \PASSWORD_BCRYPT));


        $user4 = new Users();
        $user4->setEmail('banned@example.com');
        $user4->setRoles(['ROLE_BANNED']);
        $user4->setName('user');
        $user4->setFirstname('user');
        $user4->setPassword(password_hash('password', \PASSWORD_BCRYPT));

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);

        $manager->flush();
    }
}
