<?php

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $user = new User();
        $email = 'user@example.com';
        $roles = ['ROLE_USER'];
        $password = 'password';
        $lastname = 'Doe';
        $firstname = 'John';
        $createdAt = new \DateTimeImmutable();

        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($password);
        $user->setLastname($lastname);
        $user->setFirstname($firstname);
        $user->setCreatedAt($createdAt);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($roles, $user->getRoles());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($lastname, $user->getLastname());
        $this->assertEquals($firstname, $user->getFirstname());
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }
}