<?php

namespace App\Security;

use App\Entity\Users;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Users) {
            return;
        }

        if (in_array('ROLE_BANNED', $user->getRoles())) {
            throw new CustomUserMessageAccountStatusException('Votre compte est banni');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (in_array('ROLE_BANNED', $user->getRoles())) {
            throw new AccessDeniedException('Votre compte a été banni.');
        }
    }
}