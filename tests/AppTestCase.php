<?php

namespace App\Tests;

use App\Command\TestFixturesLoaderCommand;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class AppTestCase extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->doctrine = $this->client->getContainer()->get('doctrine');

        TestFixturesLoaderCommand::runCommand(
            $this->client->getKernel(),
            ['command' => 't:f:l', '-e' => 'test', '-f' => true]
        );
    }

    protected function tearDown(): void
    {
        $this->doctrine = null;
        $this->client = null;

        parent::tearDown();
    }

    protected function logIn(string $email, string $password)
    {
        $this->client->setServerParameters(['PHP_AUTH_USER' => $email, 'PHP_AUTH_PW' => $password]);
    }

    protected function logOut()
    {
        $this->client->setServerParameters([]);
    }
}
