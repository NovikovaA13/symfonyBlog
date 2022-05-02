<?php
declare(strict_types=1);

namespace App\DataFixtures;

use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use Faker\Factory;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;
    private $slug;
    public function __construct(Slugify $slug)
    {
        $this->faker = Factory::create();
        $this->slug = $slug;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadFixture($manager);
    }
    public function loadFixture(ObjectManager $manager){
        for($i = 0; $i < 500; $i++){
            $user = $this->getReference(UserFixtures::USER_REFFERENCE);
            $title = $this->faker->text(100);
            $slug = $this->slug->slugify($title);
            $body = $this->faker->text(1000);
            $post = Post::fromPost($user, $title, $slug, $body);
            $manager->persist($post);
        }

        $manager->flush();
    }
    public function getDependencies(){
        return [
            UserFixtures::class
        ];
    }
}
