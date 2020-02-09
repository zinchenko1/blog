<?php

namespace App\DataFixtures\Faker;

use App\Entity\Post;
use App\Entity\User\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AppFaker
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * EncodePasswordProvider constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param UserInterface $user
     * @param $plainPassword
     *
     * @return string
     */
    public function encodePassword(UserInterface $user, $plainPassword)
    {
        return $this->encoder->encodePassword($user, $plainPassword);
    }

    public function getUserStatusActive()
    {
        return User::STATUS_ACTIVE;
    }

    public function getRandomPostStatus()
    {
        return array_rand(Post::STATUS_OPTIONS);
    }
}
