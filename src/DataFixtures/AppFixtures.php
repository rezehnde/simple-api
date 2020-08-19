<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName('Marcos');
        $user->setLastName('Rezende');
        $user->setEmail('rezehnde@gmail.com');
        $manager->persist($user);
        $manager->flush();
    }
}
