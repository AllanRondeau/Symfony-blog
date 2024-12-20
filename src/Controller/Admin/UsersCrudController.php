<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName', 'Prénom'),
            TextField::new('name', 'Nom'),
            TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class),
            EmailField::new('email', 'email')->hideOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Users) {
            $plainPassword = $entityInstance->getPassword();
            $entityInstance->setPassword(password_hash($plainPassword, \PASSWORD_BCRYPT));
        }

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
