<?php

namespace App\Security;

use App\Entity\User\Author;
use App\Entity\User\User;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class UserProvider extends EntityUserProvider implements AccountConnectorInterface
{

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        $serviceName = $response->getResourceOwner()->getName();
        $setterId = 'set' . ucfirst($serviceName) . 'ID';
        $setterAccessToken = 'set' . ucfirst($serviceName) . 'AccessToken';

        $username = $response->getUsername();
        $userEmail = $response->getEmail();
        if (null === $user = $this->em->getRepository(User::class)->findOneBy(['email' => $userEmail])) {

            $user = new Author();
            $user->setFirstName($response->getFirstName());
            $user->setLastName($response->getLastName());
            $user->setEmail($userEmail);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setPassword($response->getEmail());
            $user->$setterId($username);
            $user->$setterAccessToken($response->getAccessToken());

            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }

        $user->setFacebookAccessToken($response->getAccessToken());

        return $user;
    }


    /**
     * @inheritDoc
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        if (!$user instanceof User) {
            throw new UnsupportedException(sprintf("Expected an instanse on App\Model\User ..........."));
        }

        $property = $this->getProperty($response);
        $username = $response->getUsername();
        $userEmail = $response->getEmail();

        if (null !== $previousUser = $this->em->getRepository(User::class)->findOneBy(['email' => $userEmail])) {
            // 'disconnect' previously connected users
            $this->disconnect($previousUser, $response);
        }


        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set'. ucfirst($serviceName) . 'AccessToken';

        $user->$setter($response->getAccessToken());

        $this->updateUser($user, $response);

    }

    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        return $this->properties[$resourceOwnerName];
    }

    public function disconnect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $accessor = PropertyAccess::createPropertyAccessor();

        $accessor->setValue($user, $property, null);

        $this->updateUser($user, $response);
    }

    /**
     * @param UserInterface $user
     * @param UserResponseInterface $response
     */
    private function updateUser(UserInterface $user, UserResponseInterface $response)
    {
        $user->setFirstName($response->getFirstName());
        $user->setLastName($response->getLastName());
        $user->setEmail($response->getEmail());

        $this->em->persist($user);
        $this->em->flush();
    }


}