<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Trip;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr-FR");

        for ($i = 0; $i <= 30; $i++) {
            $trip = new Trip;
            $trip
                ->setName($faker->word)
                ->setDescription($faker->text(30))
                ->setDestination($faker->country)
                ->setDepartureAt($faker->dateTimeAD())
                ->setArrivalAt($faker->dateTimeAD())
                ->setAvailableSeatNumber(mt_rand(0, 9))
                ->setReserved(false);

            $manager->persist($trip);
        }


        $manager->flush();
    }
}
