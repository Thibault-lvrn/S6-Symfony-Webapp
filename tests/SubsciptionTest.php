<?php

use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $subscription = new Subscription();
        $media = 'Media';
        $users = new ArrayCollection([
            (new User())->setEmail('user1@example.com'),
            (new User())->setEmail('user2@example.com'),
        ]);

        $subscription->setMedia($media);
        $subscription->setUsers($users);

        $this->assertEquals($media, $subscription->getMedia());
        $this->assertEquals($users, $subscription->getUsers());

        $newUser = (new User())->setEmail('user3@example.com');
        $subscription->addUser($newUser);

        $this->assertTrue($subscription->getUsers()->contains($newUser));
    }
}
?>