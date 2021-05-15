<?php

namespace App\DataFixtures;

use App\Entity\Feedback;
use Faker\Factory;
use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Spacecraft;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr-FR");
        $reserved = [true, false];
        for ($i = 0; $i < 10; $i++) {
            $spacecraft = new Spacecraft;
            $spacecraft
                ->setName($faker->word)
                ->setPrice($faker->randomFloat(3))
                ->setPossibleDestination($faker->sentence(3))
                ->setBrand($faker->word())
                ->setNumberOfSeat(mt_rand(0, 6))
                ->setNationality($faker->country)
                ->setDescription($faker->sentence(6))
                ->setSpeed($faker->randomFloat(3, 1000, 100000));




            for ($j = 0; $j < mt_rand(3, 5); $j++) {
                $trip = new Trip;
                $trip
                    ->setName($faker->word)
                    ->setDescription($faker->text(30))
                    ->setDestination($faker->country)
                    ->setDepartureAt($faker->dateTimeAD())
                    ->setArrivalAt($faker->dateTimeAD())
                    ->setAvailableSeatNumber(mt_rand(0, 9))
                    ->setReserved(0)
                    ->setSpacecraft($spacecraft);
                $manager->persist($trip);
            }

            for ($l = 0; $l <= mt_rand(0, 1); $l++) {
                $user = new User;
                $user->setEmail($faker->email)
                    ->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setPassword($this->passwordEncoder->encodePassword($user, 'demo'));
                $manager->persist($user);

                for ($m = 0; $m <= mt_rand(4, 6); $m++) {
                    $feedback = new Feedback;
                    $feedback
                        ->setSpacecraft($spacecraft)
                        ->setUser($user)
                        ->setContent($faker->sentence(6))
                        ->setRating(mt_rand(1, 4));
                    $manager->persist($feedback);
                    $spacecraft->addFeedback($feedback)
                        ->setRating();
                    $manager->persist($spacecraft);
                }
            }
        }



        $manager->flush();
    }
}
