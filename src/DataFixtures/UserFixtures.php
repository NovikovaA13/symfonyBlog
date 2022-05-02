<?php
declare(strict_types=1);

namespace App\DataFixtures;

use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public const USER_REFFERENCE = 'user';
    private $faker;
    public function __construct()
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager){
        for($i = 0; $i < 10; $i++){

            $id = $this->faker->randomNumber();
            $email = $this->faker->email;
            $userName = $this->faker->userName;
            $user = new User($id, $email, $userName, User::GOOGLE_OAUTH, [User::ROLE_USER]);
            $manager->persist($user);
        }

        $manager->flush();
        $this->addReference(self::USER_REFFERENCE, $user);
    }
}
