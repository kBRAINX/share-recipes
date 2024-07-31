<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const ADMIN = 'ADMIN_USER';


    public function __construct(private readonly UserPasswordHasherInterface $hasher){
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User());
        $user->setRoles(['ROLE_ADMIN'])
            ->setUsername('admin')
            ->setEmail('admin@localhost.com')
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setVerified(true)
            ->setApiToken('admin_token');
        $this->addReference(self::ADMIN, $user);
        $manager->persist($user);

        for ($i = 1; $i <= 10; $i++) {
            $user = (new User());
            $user->setRoles(['ROLE_USER'])
                ->setUsername('user' . $i)
                ->setEmail('user' . $i . '@localhost.com')
                ->setPassword($this->hasher->hashPassword($user, 'user' . $i))
                ->setVerified(true)
                ->setApiToken('user' . $i . '_token');
            $this->addReference('USER' . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
