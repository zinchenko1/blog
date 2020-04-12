<?php

use App\Entity\User\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behatch\Context\RestContext;
use Doctrine\Common\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements Context
{
    private $kernel;
    private $registry;
    private $jwtManager;
    private $restContext;
    private $response;

    public function __construct(KernelInterface $kernel, ManagerRegistry $registry, JWTTokenManagerInterface $jwtManager)
    {
        $this->kernel = $kernel;
        $this->registry = $registry;
        $this->jwtManager = $jwtManager;
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }

    public function login(BeforeScenarioScope $scope)
    {
        $user = $this->findUser('admin');
        $token = $this->jwtManager->create($user);
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();
        $this->restContext = $environment->getContext(RestContext::class);
        $this->restContext->iAddHeaderEqualTo('Authorization', "Bearer $token");
    }

    private function findUser($username)
    {
        $repository = $this->registry->getManager()->getRepository(User::class);

        return $repository->findBy(['username' => $username]);
    }
}
