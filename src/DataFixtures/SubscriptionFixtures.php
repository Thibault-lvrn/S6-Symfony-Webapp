<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubscriptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        foreach (range(1,5) as $i){
            $subscription = new Subscription();
            $subscription->setTitle($faker->sentence(3));
            $subscription->setDescription($faker->text(200));
            $subscription->setPdfLimit($faker->numberBetween(1, 10));
            $subscription->setPrice($faker->numberBetween(10, 100));
            $subscription->setMedia($faker->imageUrl());

            $manager->persist($subscription);
            $this->addReference('subscription_'.$i, $subscription);
        }

        $manager->flush();
    }
}