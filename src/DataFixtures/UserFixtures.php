<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordEncoder){}

    public function load(ObjectManager $manager): void
    {
        $admin = $this->generateAdmin('admin@m2i.com', 'John', 'Doe', '1 rue de la paix', '75001', 'Paris', 'France', $manager);

    
        $faker = Factory::create('fr_FR');
        for($u= 1; $u <= 10; $u++) {
            $user = new User();
            $user
            ->setEmail($faker->email)
            ->setFirstname($faker->firstname)
            ->setLastname($faker->lastname)
            ->setAddress($faker->streetAddress)
            ->setZipcode(str_replace(" ", "", $faker->postcode))
            ->setCity($faker->city)
            ->setCountry('France')
            ->setPassword(
            $this->passwordEncoder->hashPassword($user, 'password')
            );
            $manager->persist($user);
            $this->addReference('user-' . $u, $user);
        }

        $manager->flush();
    }

    private function generateAdmin(string $email, $firstname, $lastnane, $address, $zipcode, $city, $country, ObjectManager $manager) {
        $user = new User();
        $user->setEmail($email)
        ->setFirstname($firstname)
        ->setLastname($lastnane)
        ->setAddress($address)
        ->setZipcode($zipcode)
        ->setCity($city)
        ->setCountry($country)
        ->setPassword(
            $this->passwordEncoder->hashPassword($user, 'admin')
        )
        ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        return $user;
    }

    private function generateUser(string $email, $firstname, $lastnane, $address, $zipcode, $city, $country, ObjectManager $manager) {
        $user = new User();
        $user->setEmail($email)
        ->setFirstname($firstname)
        ->setLastname($lastnane)
        ->setAddress($address)
        ->setZipcode($zipcode)
        ->setCity($city)
        ->setCountry($country)
        ->setPassword(
            $this->passwordEncoder->hashPassword($user, 'admin')
        );
        $manager->persist($user);
        return $user;
    }
}
