<?php

namespace App\DataFixtures;

use App\Entity\Destination;
use App\Entity\Feedback;
use Faker\Factory;
use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Spacecraft;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    private $client;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, HttpClientInterface $client)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->client = $client;
    }
    public function load(ObjectManager $manager)
    {
        $response = $this->client->request('GET', 'https://api.le-systeme-solaire.net/rest/bodies/?filter=isPlanet,neq,false');
        $response = $response->toArray();
        $faker = Factory::create("fr-FR");
        $reserved = [true, false];


        for ($i = 0; $i < 13; $i++) {

            $distance = abs(149597870 - ($response['bodies'][$i]['aphelion'] * (1 - $response['bodies'][$i]['eccentricity'])));
            $destination = new Destination;
            $destination->setName($response['bodies'][$i]['name'])
                ->setDescription($faker->sentence(6))
                ->setDistance($distance);
            $manager->persist($destination);


            for ($h = 0; $h < 6; $h++) {
                $spacecraft = new Spacecraft;
                $spacecraft
                    ->setName($faker->word)
                    ->setBrand($faker->word())
                    ->setNumberOfSeat(mt_rand(1, 6))
                    ->setNationality($faker->country)
                    ->setDescription($faker->sentence(6))
                    ->setSpeed($faker->randomFloat(3, 1000, 100000))
                    ->setReservationPrice($faker->randomFloat(2, 1000, 10000))
                    ->setPricePerDistance($faker->randomFloat(5, 0.00001, 0.002))
                    ->addPossibleDestination($destination);


                for ($j = 0; $j < mt_rand(3, 5); $j++) {
                    $trip = new Trip;
                    $trip
                        ->setName($faker->word)
                        ->setDescription($faker->text(30))
                        ->setDepartureAt($faker->dateTimeAD())
                        ->setArrivalAt($faker->dateTimeAD())
                        ->setAvailableSeatNumber(mt_rand(0, 9))
                        ->setReserved(0)
                        ->setSpacecraft($spacecraft)
                        ->setDestination($destination)
                        ->setPrice($destination->getDistance() * $spacecraft->getPricePerDistance() + $spacecraft->getReservationPrice());
                    $manager->persist($trip);
                }

                for ($l = 0; $l <= mt_rand(0, 1); $l++) {
                    $user = new User;
                    $user->setEmail($faker->email)
                        ->setFirstName($faker->firstName)
                        ->setLastName($faker->lastName)
                        ->setPassword($this->passwordEncoder->encodePassword($user, 'demo'));
                    $manager->persist($user);
                    for ($k = 1; $k < mt_rand(1, 3); $k++) {
                        $trip = new Trip;
                        $trip
                            ->setName('VR - ' . $user->getEmail() . ' - ' . $k)
                            ->setDescription('Voyage reservé par ' . $user->getEmail())
                            ->setDepartureAt($faker->dateTimeAD())
                            ->setArrivalAt($faker->dateTimeAD())
                            ->setAvailableSeatNumber(0)
                            ->setReserved(1)
                            ->setSpacecraft($spacecraft)
                            ->setDestination($destination)
                            ->setPrice($destination->getDistance() * $spacecraft->getPricePerDistance() + $spacecraft->getReservationPrice());
                        $manager->persist($trip);
                    }
                    for ($m = 0; $m <= mt_rand(4, 6); $m++) {
                        $feedback = new Feedback;
                        $feedback
                            ->setSpacecraft($spacecraft)
                            ->setUser($user)
                            ->setContent($faker->sentence(6))
                            ->setRating(mt_rand(0, 5));
                        $manager->persist($feedback);
                        $spacecraft->addFeedback($feedback)
                            ->setRating();
                        $manager->persist($spacecraft);
                    }
                }
            }
        }



        $manager->flush();
    }
}
