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
        $boolean = [true, false];

        /**
         * Stocker tout les objets destinations dans une variable
         * et ensuite les utiliser un par un quand on le voudra
         * ca servira a pas avoir de for imbriqués dans des for 
         */

        $destinations = [];
        $spacecrafts = [
            ['name' => 'Mercury', 'nationality' => 'Etats-Unis', 'brand' => 'McDonnell Aircraft Corporation'],
            ['name' => 'Gemini', 'nationality' => 'Etats-Unis', 'brand' => 'McDonnell Aircraft Corporation'],
            ['name' => 'Soyouz', 'nationality' => 'Russie', 'brand' => 'RKK Energuia'],
            ['name' => 'Vostok', 'nationality' => 'Russie', 'brand' => 'Russie'],
            ['name' => 'Voskhod', 'nationality' => 'Russie', 'brand' => 'Russie'],
            ['name' => 'TKS', 'nationality' => 'Russie', 'brand' => 'Russie'],
            ['name' => 'Shenzhou', 'nationality' => 'Chine', 'brand' => 'Chine'],
            ['name' => 'Startliner', 'nationality' => 'Etats-Unis', 'brand' => 'Boeing'],
            ['name' => 'Crew Dragon', 'nationality' => 'Etats-Unis', 'brand' => 'SpaceX'],
            ['name' => 'Dream Chaser', 'nationality' => 'Etats-Unis', 'brand' => 'Sierra Nevada Corporation'],
            ['name' => 'Orion', 'nationality' => 'Etats-Unis', 'brand' => 'Sierra Nevada Corporation'],
        ];
        $spacecrafts_object = [];

        for ($i = 0; $i < 13; $i++) {
            $distance = abs(149597870 - ($response['bodies'][$i]['aphelion'] * (1 - $response['bodies'][$i]['eccentricity'])));
            $destination = new Destination;
            $destination->setName($response['bodies'][$i]['name'])
                ->setDescription($faker->sentence(6))
                ->setDistance($distance);
            $manager->persist($destination);
            $manager->flush();
            array_push($destinations, $destination);
        }

        for ($h = 0; $h < count($spacecrafts); $h++) {
            $spacecraft = new Spacecraft;
            $spacecraft
                ->setName($spacecrafts[$h]['name'])
                ->setBrand($spacecrafts[$h]['brand'])
                ->setNumberOfSeat(mt_rand(1, 6))
                ->setNationality($spacecrafts[$h]['nationality'])
                ->setDescription($faker->sentence(6))
                ->setSpeed($faker->randomFloat(3, 1000, 100000))
                ->setReservationPrice($faker->randomFloat(2, 1000, 10000))
                ->setPricePerDistance($faker->randomFloat(5, 0.00001, 0.0002))
                ->setAvailable($boolean[mt_rand(0, 1)]);
            for ($i = 0; $i <= mt_rand(1, 6); $i++) {
                $spacecraft->addPossibleDestination($destinations[mt_rand(0, count($destinations) - 1)]);
            }
            $manager->persist($spacecraft);
            $manager->flush();
            array_push($spacecrafts_object, $spacecraft);
        }

        for ($i = 0; $i < mt_rand(15, 20); $i++) {
            $trip = new Trip;
            $trip
                ->setName($faker->word)
                ->setDescription($faker->text(30))
                ->setDepartureAt($faker->dateTimeAD())
                ->setArrivalAt($faker->dateTimeAD())
                ->setAvailableSeatNumber(mt_rand(0, 9))
                ->setReserved(0)
                ->setSpacecraft($spacecrafts_object[mt_rand(0, count($spacecrafts) - 1)])
                ->setDestination($destinations[mt_rand(0, count($destinations) - 1)]);
            if ($trip->getAvailableSeatNumber() === 0) {
                $status = 3;
            } else {
                $random = [2, 4];
                $status = $random[mt_rand(0, 1)];
            }
            $trip->setStatus($status)
                ->setPrice($trip->getDestination()->getDistance() * $trip->getSpacecraft()->getPricePerDistance()
                    + $trip->getSpacecraft()->getReservationPrice());
            $manager->persist($trip);
            $manager->flush();
        }

        for ($v = 0; $v <= mt_rand(8, 13); $v++) {
            $user = new User;
            $user->setEmail($faker->email)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordEncoder->encodePassword($user, 'demo'));
            $manager->persist($user);

            for ($m = 0; $m <= mt_rand(0, 2); $m++) {
                $feedback = new Feedback;
                $feedback
                    ->setSpacecraft($spacecrafts_object[mt_rand(1, count($spacecrafts) - 1)])
                    ->setUser($user)
                    ->setContent($faker->sentence(6))
                    ->setRating(mt_rand(0, 5));
                $manager->persist($feedback);
                $feedback->getSpacecraft()->addFeedback($feedback)
                    ->setRating();
                $manager->flush();
            }
            $manager->flush();
        }

        $admin = new User;
        $admin->setFirstName('Alex')
            ->setLastName('Ravi')
            ->setEmail('admin@spacetrip.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($user, 'demo'));
        $manager->persist($admin);
        $manager->flush();
    }
}
