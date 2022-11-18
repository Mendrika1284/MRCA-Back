<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr:FR');
        for($i = 0; $i < 10; $i++){
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->name())
            ->setPrenom($faker->firstName())
            ->setContact($faker->randomNumber())
            ->setAdresse($faker->word())
            ->setEmail($faker->email())
            ->setRoles(['ROLE_USER']);

            $hashed = $this->hasher->hashPassword(
                $utilisateur,
                'password'
            );

            $utilisateur->setPassword($hashed);
    
            $manager->persist($utilisateur);
        }

        $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->name())
            ->setPrenom($faker->firstName())
            ->setContact($faker->randomNumber())
            ->setAdresse($faker->word())
            ->setEmail($faker->email())
            ->setRoles(['ROLE_ADMIN']);

            $hashed = $this->hasher->hashPassword(
                $utilisateur,
                'password'
            );

            $utilisateur->setPassword($hashed);
    
            $manager->persist($utilisateur);

        $manager->flush();
    }
}
