<?php

namespace App\DataFixtures;

use App\Entity\Spacecraft;
use Faker\Factory;
use App\Entity\Trip;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr-FR");

        for ($i = 0; $i <= 6; $i++) {
            $spacecraft = new Spacecraft;
            $spacecraft
                ->setName($faker->word)
                ->setPrice($faker->randomFloat(3))
                ->setPossibleDestination($faker->sentence(3))
                ->setBrand($faker->word())
                ->setNumberOfSeat(mt_rand(0, 6))
                ->setNationality($faker->country)
                ->setDescription($faker->sentence(6))
                ->setSpeed($faker->randomFloat(3, 1000, 100000))
                ->setRating(mt_rand(0, 5));

            for ($j = 0; $j <= mt_rand(1, 5); $j++) {
                $trip = new Trip;
                $trip
                    ->setName($faker->word)
                    ->setDescription($faker->text(30))
                    ->setDestination($faker->country)
                    ->setDepartureAt($faker->dateTimeAD())
                    ->setArrivalAt($faker->dateTimeAD())
                    ->setAvailableSeatNumber(mt_rand(0, 9))
                    ->setReserved(false)
                    ->setSpacecraft($spacecraft);

                $manager->persist($trip);
            }
            $manager->persist($spacecraft);
        }

        $manager->flush();
    }
}
