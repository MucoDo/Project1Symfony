<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher=$passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<3; $i++){
            $user= new User();
            $user->setEmail("user".$i."@gmail.com");
            $user->setNom("user".$i);
            $user->setPassword($this->passwordHasher->hashPassword($user,'hello'.$i) );
            $manager->persist($user);
        }

        $manager->flush();
    }
}
